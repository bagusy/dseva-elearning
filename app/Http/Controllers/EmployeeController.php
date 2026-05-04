<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Models\Employee;
use App\Models\User;
use App\Notifications\EmailInvitation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $currentUser = $request->user()->load(['company.employees.user.courseEnrollments']);
        $employees = $currentUser['company']->employees->where('user_id', '<>', $currentUser['id']);
        $departments = $currentUser['company']->departments;
        return view('employees.index', compact('employees', 'departments'));
    }

    public function store(StoreEmployeeRequest $request)
    {
        $currentUser = $request->user();
        $company = $currentUser->company;
        $userId = null;
        DB::transaction(function () use ($currentUser, $company, &$userId, $request) {
            $user = User::where('email', $request['email'])->first();
            if ($user !== null && $user['company_id'] !== null && $user['company_id'] !== $company['id']) {
                abort(403, 'This email belongs to a user in a different company');
            }
            if ($request['with_user']) {
                if ($user !== null) {
                    $user['name'] = $request['name'];
                    $user['status'] = User::STATUS_ACTIVE;
                    $user['avatar'] = $request['avatar'];
                    $user->save();

                    if ($request['role'] == null) {
                        $user->syncRoles(User::ROLE_USER_EMPLOYEE);
                    } else {
                        $user->syncRoles($request['role']);
                    }
                } else {
                    $user = User::create([
                        'name' => $request['name'],
                        'email' => $request['email'],
                        'password' => Hash::make(uniqid()),
                        'avatar' => $request['avatar'],
                    ]);
                    $user['company_id'] = $company['id'];
                    $user->save();

                    if ($request['role'] == null) {
                        $user->assignRole(User::ROLE_USER_EMPLOYEE);
                    } else {
                        $user->assignRole($request['role']);
                    }
                }
                // Notification reset password
                $token = Str::random(64);

                DB::table('password_resets')->where('email', '=', $request['email'])->delete();
                DB::table('password_resets')->insert([
                    'email' => $request['email'],
                    'token' => Hash::make($token),
                    'created_at' => Carbon::now()
                ]);

                $user->notify(new EmailInvitation($currentUser['name'], $token));
                $userId = $user['id'];
            }
            $employee = new Employee;
            $employee['email'] = $request['email'];
            $employee['department_id'] = $request['department_id'];
            $employee['user_id'] = $userId;

            $company->employees()->save($employee);
        });

        return redirect()->back()->withMessage('Employee added');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Employee $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        //
    }

    public function destroy(Request $request, Employee $employee)
    {
        $this->authorize('delete', $employee);
        $employee->load('user');
        if ($employee['user'] !== null) {
            $user = $employee->user;
            $user['status'] = User::STATUS_INACTIVE;
            $user->save();
        }
        $employee->delete();

        return redirect()->back()->withMessage('Employee deleted');
    }
}
