<?php

namespace App\Http\Controllers;

use App\Models\CourseEnrollment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $currentUser = $request->user()->load('company.departments', 'company.users.courseEnrollments');
        $departments = $currentUser['company']['departments'];
        $users = $currentUser['company']['users'];
        foreach ($departments as $i => $department) {
            $total = $department->courseEnrollments()->count();
            $done = $department->courseEnrollments()->where('status', CourseEnrollment::STATUS_COMPLETED)->count();
            $onProgress = $department->courseEnrollments()->where('status', CourseEnrollment::STATUS_ON_PROGRESS)->count();
            $percent = $total === 0 ? 0 : number_format(($done / $total * 100), 2, '.', '');
            $departments[$i]['total'] = $total;
            $departments[$i]['percentage'] = $percent;
            $departments[$i]['on_progress'] = $onProgress;
        }
        $statisticUser = [
            'total' => 0,
            'complete' => 0,
            'on_progress' => 0,
            'not_registered' => 0,
            'failed' => 0,
        ];
        foreach ($users as $user) {
            foreach ($user['courseEnrollments'] as $courseEnrollment) {
                if ($courseEnrollment['status'] === CourseEnrollment::STATUS_COMPLETED) {
                    $statisticUser['complete'] += 1;
                } else if ($courseEnrollment['status'] === CourseEnrollment::STATUS_ON_PROGRESS) {
                    $statisticUser['on_progress'] += 1;
                } else if ($courseEnrollment['status'] === CourseEnrollment::STATUS_NEW) {
                    $statisticUser['not_registered'] += 1;
                } else {
                    $statisticUser['failed'] += 1;
                }
                $statisticUser['total'] += 1;
            }
        }
        return view('dashboard.index', compact('departments', 'currentUser', 'users', 'statisticUser'));
    }
}
