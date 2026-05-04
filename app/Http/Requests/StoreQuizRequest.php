<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreQuizRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('create quiz');
    }

    public function rules()
    {
        return [
            'title' => ['required', 'string'],
            'min_score' => ['required', 'integer', 'min:0', 'max:100'],
            'show_question' => ['required', 'integer', 'min:1', 'max:20'],
        ];
    }

    protected function passedValidation()
    {
        $this['is_custom'] = $this->user()->hasRole([User::ROLE_USER_ADMIN]);
    }
}
