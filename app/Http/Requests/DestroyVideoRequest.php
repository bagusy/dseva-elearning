<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class DestroyVideoRequest extends FormRequest
{

    public function authorize()
    {
        return ($this->user()->can('delete video') && $this->video['user_id'] === $this->user()['id'] ) || $this->user()->hasRole(User::ROLE_ADMIN);
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
