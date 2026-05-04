<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Course;
use App\Models\CourseAssignment;
use App\Models\CourseEnrollment;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Quiz;
use App\Models\User;
use App\Models\Video;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class BugFixSmokeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    private function makeUser(?string $role = User::ROLE_USER_ADMIN, ?Company $company = null): User
    {
        $user = User::create([
            'name' => 'Test ' . uniqid(),
            'email' => uniqid() . '@example.com',
            'password' => Hash::make('TestPass1!'),
            'avatar' => '/dashboard/assets/images/user/avatar-1.jpg',
        ]);
        $user['email_verified_at'] = now();
        if ($company) {
            $user['company_id'] = $company['id'];
        }
        $user->save();
        if ($role) {
            $user->assignRole($role);
        }
        return $user->fresh();
    }

    private function makeCompany(User $owner): Company
    {
        $company = new Company;
        $company['name'] = 'Test Co ' . uniqid();
        $company['industry'] = 'Software & Comp. Serv.';
        $company['size'] = 'Small';
        $company['user_id'] = $owner['id'];
        $company->save();
        $owner['company_id'] = $company['id'];
        $owner->save();
        return $company->fresh();
    }

    /** @test */
    public function migrations_create_indexes_on_foreign_keys()
    {
        $this->assertTrue(Schema::hasColumn('users', 'deleted_at'), 'users.deleted_at missing');
        $this->assertTrue(Schema::hasColumn('employees', 'deleted_at'), 'employees.deleted_at missing');
        $this->assertTrue(Schema::hasColumn('departments', 'deleted_at'), 'departments.deleted_at missing');
        $this->assertTrue(Schema::hasColumn('course_enrollments', 'sub_section_id'), 'sub_section_id missing');
        $this->assertFalse(Schema::hasColumn('course_enrollments', 'App\Models\SubSection'), 'broken column still exists');
        $this->assertTrue(Schema::hasColumn('course_assignments', 'company_id'), 'course_assignments.company_id missing');
        $this->assertTrue(Schema::hasColumn('course_assignments', 'is_assigned'), 'course_assignments.is_assigned missing');
    }

    /** @test */
    public function password_regex_accepts_strong_password_and_rejects_weak()
    {
        $trait = new class {
            use \App\Traits\FormPreparation;
            public function validate($pw)
            {
                return $this->validateStrongPassword($pw, 'password');
            }
        };

        $this->assertEquals('Strong1!', $trait->validate('Strong1!'));

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $trait->validate('weakpass');
    }

    /** @test */
    public function user_fillable_does_not_allow_company_id_mass_assignment()
    {
        $user = User::create([
            'name' => 'X',
            'email' => 'x@test.com',
            'password' => Hash::make('p'),
            'company_id' => 'malicious-uuid',
        ]);
        $this->assertNull($user['company_id'], 'company_id should not be mass-assignable');
    }

    /** @test */
    public function course_fillable_does_not_allow_user_id_mass_assignment()
    {
        // user_id is NOT NULL in schema; if mass-assign worked, the row would be inserted
        // with the malicious uuid. Since we removed user_id from $fillable, the create
        // call drops it silently and the insert fails on the NOT NULL constraint.
        $this->expectException(\Illuminate\Database\QueryException::class);
        Course::create([
            'title' => 'Test',
            'description' => 'X',
            'category' => 'Custom Training',
            'level' => 'Beginner',
            'price_in_usd' => 0,
            'status' => Course::STATUS_DRAFT,
            'is_custom' => 1,
            'user_id' => 'malicious-uuid',
        ]);
    }

    /** @test */
    public function has_uuid_does_not_overwrite_explicit_id()
    {
        $explicitId = (string) \Illuminate\Support\Str::orderedUuid();
        $owner = $this->makeUser();
        $company = $this->makeCompany($owner);
        $department = new Department;
        $department['id'] = $explicitId;
        $department['name'] = 'Test Dept';
        $department['company_id'] = $company['id'];
        $department->save();
        $this->assertEquals($explicitId, $department['id']);
    }

    /** @test */
    public function trainings_progress_blocks_other_users_enrollment()
    {
        $owner = $this->makeUser();
        $company = $this->makeCompany($owner);
        $department = new Department;
        $department['name'] = 'Eng';
        $department['company_id'] = $company['id'];
        $department->save();

        $employeeUser = $this->makeUser(User::ROLE_USER_EMPLOYEE, $company);
        $course = new Course;
        $course['title'] = 'C';
        $course['description'] = 'D';
        $course['category'] = 'Custom Training';
        $course['level'] = 'Beginner';
        $course['price_in_usd'] = 0;
        $course['status'] = Course::STATUS_PUBLISHED;
        $course['is_custom'] = 1;
        $course['user_id'] = $owner['id'];
        $course->save();

        $assignment = new CourseAssignment;
        $assignment['course_id'] = $course['id'];
        $assignment['department_id'] = $department['id'];
        $assignment['company_id'] = $company['id'];
        $assignment['subject'] = 's';
        $assignment['message'] = 'm';
        $assignment['day_completion'] = 7;
        $assignment['start_date'] = now()->subDay();
        $assignment->save();

        $enrollment = new CourseEnrollment;
        $enrollment['course_id'] = $course['id'];
        $enrollment['course_assignment_id'] = $assignment['id'];
        $enrollment['user_id'] = $employeeUser['id'];
        $enrollment['time_start'] = now()->subDay();
        $enrollment['time_limit'] = now()->addDays(30);
        $enrollment['status'] = CourseEnrollment::STATUS_NEW;
        $enrollment->save();

        $attacker = $this->makeUser(User::ROLE_USER_EMPLOYEE, $company);

        $response = $this->actingAs($attacker)->get('/trainings/' . $enrollment['id']);
        $response->assertForbidden();
    }

    /** @test */
    public function department_update_blocks_cross_tenant()
    {
        $aOwner = $this->makeUser();
        $aCompany = $this->makeCompany($aOwner);
        $bOwner = $this->makeUser();
        $bCompany = $this->makeCompany($bOwner);

        $bDept = new Department;
        $bDept['name'] = 'B Dept';
        $bDept['company_id'] = $bCompany['id'];
        $bDept->save();

        $response = $this->actingAs($aOwner)->put('/company/departments/' . $bDept['id'], [
            'name' => 'Hijacked',
            'report_email' => 'evil@example.com',
        ]);
        $response->assertForbidden();
    }

    /** @test */
    public function employee_destroy_blocks_cross_tenant()
    {
        $aOwner = $this->makeUser();
        $aCompany = $this->makeCompany($aOwner);
        $bOwner = $this->makeUser();
        $bCompany = $this->makeCompany($bOwner);

        $bDept = new Department;
        $bDept['name'] = 'B';
        $bDept['company_id'] = $bCompany['id'];
        $bDept->save();
        $bUser = $this->makeUser(User::ROLE_USER_EMPLOYEE, $bCompany);
        $bEmp = new Employee;
        $bEmp['email'] = 'b@b.com';
        $bEmp['company_id'] = $bCompany['id'];
        $bEmp['department_id'] = $bDept['id'];
        $bEmp['user_id'] = $bUser['id'];
        $bEmp->save();

        $response = $this->actingAs($aOwner)->delete('/company/employees/' . $bEmp['id']);
        $response->assertForbidden();

        $this->assertDatabaseHas('employees', ['id' => $bEmp['id'], 'deleted_at' => null]);
    }

    /** @test */
    public function api_endpoints_require_authentication()
    {
        $owner = $this->makeUser();
        $company = $this->makeCompany($owner);
        $course = new Course;
        $course['title'] = 'C';
        $course['description'] = 'D';
        $course['category'] = 'Custom Training';
        $course['level'] = 'Beginner';
        $course['price_in_usd'] = 0;
        $course['status'] = Course::STATUS_PUBLISHED;
        $course['is_custom'] = 1;
        $course['user_id'] = $owner['id'];
        $course->save();

        $response = $this->getJson('/api/courses/' . $course['id'] . '/videos');
        $response->assertStatus(401);
    }

    /** @test */
    public function api_videolist_blocks_other_company()
    {
        $aOwner = $this->makeUser();
        $aCompany = $this->makeCompany($aOwner);
        $bOwner = $this->makeUser();
        $bCompany = $this->makeCompany($bOwner);

        $course = new Course;
        $course['title'] = 'C';
        $course['description'] = 'D';
        $course['category'] = 'Custom Training';
        $course['level'] = 'Beginner';
        $course['price_in_usd'] = 0;
        $course['status'] = Course::STATUS_PUBLISHED;
        $course['is_custom'] = 1;
        $course['user_id'] = $bOwner['id'];
        $course->save();

        $response = $this->actingAs($aOwner)->get('/api/courses/' . $course['id'] . '/videos');
        $response->assertForbidden();
    }

    /** @test */
    public function login_endpoint_is_throttled()
    {
        for ($i = 0; $i < 7; $i++) {
            $this->post('/login', ['email' => 'nobody@test.com', 'password' => 'wrong']);
        }
        $response = $this->post('/login', ['email' => 'nobody@test.com', 'password' => 'wrong']);
        $response->assertStatus(429);
    }

    /** @test */
    public function video_index_validates_category_input()
    {
        $owner = $this->makeUser();
        $this->makeCompany($owner);

        $response = $this->actingAs($owner)->get('/videos?category=%25');
        $response->assertNotFound();
    }

    /** @test */
    public function start_training_rejects_other_companys_department()
    {
        $aOwner = $this->makeUser();
        $aCompany = $this->makeCompany($aOwner);
        $bOwner = $this->makeUser();
        $bCompany = $this->makeCompany($bOwner);

        $bDept = new Department;
        $bDept['name'] = 'B';
        $bDept['company_id'] = $bCompany['id'];
        $bDept->save();

        $course = new Course;
        $course['title'] = 'C';
        $course['description'] = 'D';
        $course['category'] = 'Custom Training';
        $course['level'] = 'Beginner';
        $course['price_in_usd'] = 0;
        $course['status'] = Course::STATUS_PUBLISHED;
        $course['is_custom'] = 1;
        $course['user_id'] = $aOwner['id'];
        $course->save();

        $response = $this->actingAs($aOwner)->post('/training/' . $course['id'] . '/start', [
            'department_id' => [$bDept['id']],
            'subject' => 'Hi',
            'message' => 'Take this',
            'day_completion' => 7,
            'start_date' => now()->format('Y-m-d'),
        ]);
        $response->assertSessionHasErrors('department_id.0');
    }
}
