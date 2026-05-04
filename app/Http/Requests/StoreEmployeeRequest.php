<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('manage employee');
    }

    public function rules()
    {
        $departmentId = $this->user()->company->departments()->pluck('id')->toArray();
        $canManageUser = $this->user()->can('manage user');
        $allowedRoles = [
            User::ROLE_SUBSCRIPTION_MANAGER,
            User::ROLE_COURSE_CREATOR,
            User::ROLE_CONTENT_CREATOR,
        ];
        return [
            'email' => ['required', 'email', 'unique:employees'],
            'department_id' => ['required', Rule::in($departmentId)],
            'with_user' => ['required', 'boolean'],
            'role' => $canManageUser
                ? ['nullable', Rule::in($allowedRoles)]
                : ['prohibited'],
            'name' => ['nullable', 'string'],
            'avatar' => ['required', 'string'],
        ];
    }

    protected function prepareForValidation()
    {
        $this['with_user'] = isset($this['with_user']);
        if ($this['with_user'] && $this['name'] === null) {
            throw ValidationException::withMessages([
                'name' => [
                    'name is required'
                ]
            ]);
        }
        if (!$this->user()->can('manage user')) {
            $this['role'] = null;
        }

        if ($this['role'] !== null && !$this['with_user']) {
            throw ValidationException::withMessages([
                'with_user' => [
                    'you must specify the user name'
                ]
            ]);
        }
    }
}
