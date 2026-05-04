<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDepartmentRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('manage department');
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string'],
            'report_email' => ['nullable', 'string'],
        ];
    }
}
