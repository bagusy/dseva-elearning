<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Course;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Section;
use App\Models\SubSection;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        $this->call(VideoSeeder::class);

        $admin = User::role(User::ROLE_ADMIN)->first();
        if (is_null($admin)) {
            $this->command->error('Admin user not found. Run AdminUserSeeder first.');
            return;
        }

        $this->seedUserAdmins();
        $this->seedCourses($admin);
        $this->seedEmployeesForAdminCompany($admin);

        $this->command->info('users:       ' . User::count());
        $this->command->info('companies:   ' . Company::count());
        $this->command->info('departments: ' . Department::count());
        $this->command->info('employees:   ' . Employee::count());
        $this->command->info('videos:      ' . Video::count());
        $this->command->info('courses:     ' . Course::count());
    }

    private function seedUserAdmins(): void
    {
        $companies = [
            ['name' => 'PT Sinar Teknologi', 'industry' => 'Software & Comp. Serv.', 'size' => 'Medium High'],
            ['name' => 'Cipta Karya Mandiri', 'industry' => 'Constr. & Materials',     'size' => 'Large Low'],
            ['name' => 'Nusantara Bank',      'industry' => 'Banks',                   'size' => 'Large High'],
            ['name' => 'Garuda Logistik',     'industry' => 'Indust. Transp.',         'size' => 'Medium Low'],
            ['name' => 'Sehat Prima Farma',   'industry' => 'Pharm & Biotech.',        'size' => 'Medium High'],
            ['name' => 'Cendana Media',       'industry' => 'Media',                   'size' => 'Small'],
            ['name' => 'Wira Otomotif',       'industry' => 'Automobiles & Parts',     'size' => 'Medium High'],
            ['name' => 'Buana Retail Group',  'industry' => 'General Retailers',       'size' => 'Large Low'],
            ['name' => 'Anugerah Tambang',    'industry' => 'Mining',                  'size' => 'Large High'],
            ['name' => 'Selaras Insurance',   'industry' => 'Non-life Insurance',      'size' => 'Medium Low'],
            ['name' => 'Bina Pangan Sejati',  'industry' => 'Food Producers',          'size' => 'Medium High'],
            ['name' => 'Mega Travel & Tour',  'industry' => 'Travel & Leisure',        'size' => 'Small'],
        ];

        $admins = [
            ['name' => 'Andi Pratama',     'email' => 'andi.pratama@sinartek.id'],
            ['name' => 'Budi Santoso',     'email' => 'budi.santoso@ciptakarya.id'],
            ['name' => 'Citra Lestari',    'email' => 'citra.lestari@nusantarabank.co.id'],
            ['name' => 'Dewi Anggraini',   'email' => 'dewi.anggraini@garudalog.co.id'],
            ['name' => 'Eko Wibowo',       'email' => 'eko.wibowo@sehatprima.id'],
            ['name' => 'Fitri Hasanah',    'email' => 'fitri.hasanah@cendanamedia.id'],
            ['name' => 'Gilang Saputra',   'email' => 'gilang.saputra@wiraotomotif.id'],
            ['name' => 'Hana Permata',     'email' => 'hana.permata@buanaretail.id'],
            ['name' => 'Irfan Maulana',    'email' => 'irfan.maulana@anugerahtambang.id'],
            ['name' => 'Jihan Aulia',      'email' => 'jihan.aulia@selaras-ins.co.id'],
            ['name' => 'Kurnia Wijaya',    'email' => 'kurnia.wijaya@binapangan.id'],
            ['name' => 'Larasati Putri',   'email' => 'larasati.putri@megatravel.id'],
        ];

        foreach ($companies as $i => $c) {
            $a = $admins[$i];
            if (User::where('email', $a['email'])->exists()) {
                continue;
            }

            $user = User::create([
                'name' => $a['name'],
                'email' => $a['email'],
                'password' => Hash::make('password'),
                'avatar' => '/dashboard/assets/images/user/avatar-' . (($i % 10) + 1) . '.jpg',
                'email_verified_at' => now()->subDays(rand(5, 60)),
                'status' => User::STATUS_ACTIVE,
            ]);
            $user->assignRole(User::ROLE_USER_ADMIN);

            $company = new Company;
            $company['user_id'] = $user['id'];
            $company['name'] = $c['name'];
            $company['industry'] = $c['industry'];
            $company['size'] = $c['size'];
            $company['description'] = $c['name'] . ' — sample tenant for demo.';
            $company->save();

            $user['company_id'] = $company['id'];
            $user->save();

            foreach (Department::DEFAULT_LIST as $deptName) {
                $dept = new Department;
                $dept['company_id'] = $company['id'];
                $dept['name'] = $deptName;
                $dept->save();
            }

            $departments = Department::where('company_id', $company['id'])->whereNot('name', Department::NO_DEPARTMENT)->get();
            $employeeCount = rand(4, 9);
            for ($j = 1; $j <= $employeeCount; $j++) {
                $emp = new Employee;
                $emp['company_id'] = $company['id'];
                $emp['department_id'] = $departments->random()['id'];
                $emp['email'] = 'employee' . $j . '@' . explode('@', $a['email'])[1];
                $emp['user_id'] = null;
                $emp->save();
            }
        }
    }

    private function seedCourses(User $admin): void
    {
        if (Course::count() > 0) {
            return;
        }

        $videos = Video::orderBy('created_at')->get();
        if ($videos->count() === 0) {
            $this->command->warn('No videos available — courses will be created without subsections.');
        }

        $courses = [
            [
                'title' => 'Security Awareness Foundations',
                'description' => 'Pelatihan dasar untuk meningkatkan kesadaran keamanan informasi seluruh karyawan. Mencakup phishing, password hygiene, dan social engineering.',
                'category' => 'Security Awareness',
                'level' => 'Beginner',
                'price_in_usd' => 0,
                'images' => 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'title' => 'Phishing & Email Threats Deep Dive',
                'description' => 'Memahami beragam teknik phishing modern, BEC, spear phishing, dan cara mengenalinya sebelum menjadi korban.',
                'category' => 'Security Awareness',
                'level' => 'Intermediate',
                'price_in_usd' => 19.00,
                'images' => 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'title' => 'Compliance Essentials: GDPR, HIPAA, PCI',
                'description' => 'Ringkasan praktis kewajiban kepatuhan utama yang relevan bagi perusahaan global maupun lokal.',
                'category' => 'Compliance',
                'level' => 'Intermediate',
                'price_in_usd' => 29.00,
                'images' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'title' => 'Secure Coding for Developers',
                'description' => 'Praktik penulisan kode yang aman: validasi input, autentikasi, manajemen rahasia, dan mitigasi OWASP Top 10.',
                'category' => 'Specialized',
                'level' => 'Advanced',
                'price_in_usd' => 49.00,
                'images' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'title' => 'Ransomware Response Playbook',
                'description' => 'Langkah-langkah operasional menghadapi insiden ransomware: containment, recovery, dan komunikasi krisis.',
                'category' => 'Specialized',
                'level' => 'Advanced',
                'price_in_usd' => 39.00,
                'images' => 'https://images.unsplash.com/photo-1614064641938-3bbee52942c7?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'title' => 'Insider Threat Awareness',
                'description' => 'Mengenali pola perilaku berisiko dari dalam organisasi serta kontrol pencegahan dan deteksi yang efektif.',
                'category' => 'Security Awareness',
                'level' => 'Professional',
                'price_in_usd' => 24.00,
                'images' => 'https://images.unsplash.com/photo-1563013544-824ae1b704d3?auto=format&fit=crop&w=600&q=80',
            ],
        ];

        foreach ($courses as $c) {
            $course = Course::create([
                'user_id' => $admin['id'],
                'title' => $c['title'],
                'description' => $c['description'],
                'images' => $c['images'] ?? null,
                'category' => $c['category'],
                'level' => $c['level'],
                'price_in_usd' => $c['price_in_usd'],
                'status' => Course::STATUS_PUBLISHED,
                'is_custom' => false,
            ]);

            $sectionTitles = ['Introduction', 'Core Concepts', 'Real-World Scenarios'];
            foreach ($sectionTitles as $idx => $title) {
                $section = new Section;
                $section['course_id'] = $course['id'];
                $section['title'] = $title;
                $section['index'] = $idx + 1;
                $section->save();

                $subsetVideos = $videos->shuffle()->take(2);
                $subIndex = 1;
                foreach ($subsetVideos as $video) {
                    $sub = new SubSection;
                    $sub['section_id'] = $section['id'];
                    $sub['type'] = SubSection::TYPE_VIDEO;
                    $sub['video_id'] = $video['id'];
                    $sub['index'] = $subIndex++;
                    $sub->save();
                }
            }
        }
    }

    private function seedEmployeesForAdminCompany(User $admin): void
    {
        $admin->load('company.departments');
        $company = $admin['company'];
        if (is_null($company)) {
            $this->command->warn('Admin has no company; skipping employee seed.');
            return;
        }

        $departments = $company->departments->where('name', '<>', Department::NO_DEPARTMENT)->values();
        if ($departments->count() === 0) {
            $this->command->warn('Admin company has no departments; skipping employee seed.');
            return;
        }

        $existingCount = Employee::where('company_id', $company['id'])->count();
        if ($existingCount >= 18) {
            return;
        }

        $people = [
            ['name' => 'Rizky Pratama',    'email' => 'rizky.pratama@dseva.id'],
            ['name' => 'Sarah Putri',      'email' => 'sarah.putri@dseva.id'],
            ['name' => 'Tegar Nugraha',    'email' => 'tegar.nugraha@dseva.id'],
            ['name' => 'Umar Hidayat',     'email' => 'umar.hidayat@dseva.id'],
            ['name' => 'Vina Maharani',    'email' => 'vina.maharani@dseva.id'],
            ['name' => 'Wahyu Setiawan',   'email' => 'wahyu.setiawan@dseva.id'],
            ['name' => 'Xena Kusuma',      'email' => 'xena.kusuma@dseva.id'],
            ['name' => 'Yoga Pranata',     'email' => 'yoga.pranata@dseva.id'],
            ['name' => 'Zahra Aulia',      'email' => 'zahra.aulia@dseva.id'],
            ['name' => 'Adit Permadi',     'email' => 'adit.permadi@dseva.id'],
            ['name' => 'Bella Anjani',     'email' => 'bella.anjani@dseva.id'],
            ['name' => 'Cahyo Nugroho',    'email' => 'cahyo.nugroho@dseva.id'],
            ['name' => 'Dini Kartika',     'email' => 'dini.kartika@dseva.id'],
            ['name' => 'Egi Saputra',      'email' => 'egi.saputra@dseva.id'],
            ['name' => 'Fani Oktaviani',   'email' => 'fani.oktaviani@dseva.id'],
            ['name' => 'Galih Ramadhan',   'email' => 'galih.ramadhan@dseva.id'],
            ['name' => 'Hilda Safitri',    'email' => 'hilda.safitri@dseva.id'],
            ['name' => 'Indra Yusuf',      'email' => 'indra.yusuf@dseva.id'],
        ];

        foreach ($people as $i => $p) {
            if (Employee::where('email', $p['email'])->where('company_id', $company['id'])->exists()) {
                continue;
            }

            $userId = null;
            // ~70% have a User account; the rest are pending invitations
            if ($i % 10 < 7) {
                $u = User::firstOrCreate(
                    ['email' => $p['email']],
                    [
                        'name' => $p['name'],
                        'password' => Hash::make('password'),
                        'avatar' => '/dashboard/assets/images/user/avatar-' . (($i % 10) + 1) . '.jpg',
                        'email_verified_at' => now()->subDays(rand(2, 30)),
                        'company_id' => $company['id'],
                        'status' => User::STATUS_ACTIVE,
                    ]
                );
                if (!$u->hasRole(User::ROLE_USER_EMPLOYEE)) {
                    $u->assignRole(User::ROLE_USER_EMPLOYEE);
                }
                $userId = $u['id'];
            }

            $emp = new Employee;
            $emp['company_id'] = $company['id'];
            $emp['department_id'] = $departments[$i % $departments->count()]['id'];
            $emp['email'] = $p['email'];
            $emp['user_id'] = $userId;
            $emp->save();
        }
    }
}
