<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteDepartmentRequest;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $currentUser = $request->user();
        $currentUser->load('company.departments');

        $departments = $currentUser['company']['departments'];

        return view('department.index', compact('departments'));
    }

    public function store(StoreDepartmentRequest $request)
    {
        $currentUser = $request->user()->load('company');
        $company = $currentUser['company'];
        $newDepartment = new Department;
        $newDepartment['name'] = $request['name'];
        $newDepartment['report_email'] = $request['report_email'];

        $company->departments()->save($newDepartment);

        return redirect()->back()->withMessage('Department added');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Department  $Department
     * @return \Illuminate\Http\Response
     */
    public function show(Department $Department)
    {
        //
    }

    public function update(UpdateDepartmentRequest $request, Department $department)
    {
        $this->authorize('update', $department);
        $department['name'] = $request['name'];
        $department['report_email'] = $request['report_email'];
        $department->save();

        return redirect()->back()->withMessage('Department updated');
    }

    public function destroy(DeleteDepartmentRequest $request, Department $department)
    {
        $this->authorize('delete', $department);
        $department->delete();

        return redirect()->back()->withMessage('Department deleted');
    }
}
