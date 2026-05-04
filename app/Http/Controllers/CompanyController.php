<?php

namespace App\Http\Controllers;

use App\Http\Requests\OnboardCompanyRequest;
use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Company $company)
    {
        //
    }

    public function edit(Company $company)
    {
        //
    }

    public function update(Request $request, Company $company)
    {
        //
    }

    public function destroy(Company $company)
    {
        //
    }

    public function formOnboard()
    {
        return view('auth.on-boarding-company');
    }

    public function onboard(OnboardCompanyRequest $request)
    {
        if (!is_null($request->user()['company_id'])) {
            return redirect('/home');
        }

        DB::transaction(function () use ($request) {
            $company = new Company;
            $company['name'] = $request['name'];
            $company['industry'] = $request['industry'];
            $company['size'] = $request['size'];
            $company['user_id'] = $request->user()['id'];
            $company->save();

            $this->addDepartment($company['id']);
            $noDepartment = $company->departments()->where('name', Department::NO_DEPARTMENT)->first();

            $user = $request->user();

            $this->addEmployee($company['id'], $noDepartment['id'], $user['email'], $user['id']);

            $user['company_id'] = $company['id'];
            $user->save();
        });

        return redirect('/home');
    }

    private function addDepartment($companyId)
    {
        $now = now();
        $data = array();
        foreach (Department::DEFAULT_LIST as $departmentName) {
            $data[] = [
                'id' => Str::orderedUuid()->toString(),
                'company_id' => $companyId,
                'name' => $departmentName,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        DB::table('departments')->insert($data);
    }

    private function addEmployee($companyId, $departmentId, $email, $userId)
    {
        $employee = new Employee;
        $employee['company_id'] = $companyId;
        $employee['department_id'] = $departmentId;
        $employee['email'] = $email;
        $employee['user_id'] = $userId;
        $employee->save();
    }
}
