<?php

namespace App\Http\Requests;

use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;

class OnboardCompanyRequest extends FormRequest
{
    public function rules()
    {
        $companySize = collect(Company::COMPANY_SIZE_LIST)->keys();
        return [
            "name" => ["required", "string"],
            "size" => ["required", "in:" . implode(",", $companySize->toArray())],
            "industry" => ["required", "in:" . implode(",", Company::COMPANY_INDUSTRY_LIST)],
        ];
    }
}
