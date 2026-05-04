<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteDepartmentRequest extends FormRequest
{
    public function authorize()
    {
        return $this->department->employees()->count() === 0;
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
