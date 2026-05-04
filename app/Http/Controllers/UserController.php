<?php

namespace App\Http\Controllers;

use App\Http\Requests\OnboardCompanyRequest;
use App\Models\Company;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $currentUser = $request->user();
        $query = User::where('status', User::STATUS_ACTIVE)
            ->role([User::ROLE_USER_ADMIN])
            ->with('company.employees');

        if (!$currentUser->hasRole(User::ROLE_ADMIN)) {
            $query->where('company_id', $currentUser['company_id']);
        }

        $users = $query->paginate(10);

        return view('users.index', compact('users'));
    }

    public function updateRole(Request $request)
    {
        abort_unless($request->user()->hasRole(User::ROLE_ADMIN), 403);

        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'role' => ['required', 'string', 'exists:roles,name'],
        ]);

        $target = User::findOrFail($data['user_id']);
        $target->syncRoles([$data['role']]);

        return back()->with('success', 'Role updated.');
    }

    public function formOnboard()
    {
        return view('auth.on-boarding-profile');
    }

    public function onboard(Request $request)
    {
        $currentUser = $request->user();
        $request->validate([
            'name' => ['required', 'string'],
            'avatar' => ['required', 'string'],
        ]);

        $currentUser['name'] = $request['name'];
        $currentUser['avatar'] = $request['avatar'];
        $currentUser->save();

        return redirect('/home');
    }
}
