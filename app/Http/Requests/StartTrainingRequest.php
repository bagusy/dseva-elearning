<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StartTrainingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $companyId = $this->user()['company_id'];
        return [
            'department_id' => ['required', 'array'],
            'department_id.*' => [
                'required',
                Rule::exists('departments', 'id')->where(fn ($q) => $q->where('company_id', $companyId)),
            ],
            'subject' => ['required', 'string'],
            'message' => ['required', 'string'],
            'day_completion' => ['required', 'integer', 'min:1'],
            'start_date' => ['required', 'date', 'after:' . now()->subDay()->format('Y-m-d')],
        ];
    }

    protected function prepareForValidation()
    {
        $currentUser = $this->user()->load('company.departments');
        if ($this['target'] === 'all') {
            $this['department_id'] = $currentUser->company->departments()->pluck('id')->toArray();
        }

        if ($this['day_completion'] === 'custom') {
            $this['day_completion'] = $this['day_completion_value'] ?? 0;
        }
    }

    protected function passedValidation()
    {
        unset($this['target']);
        unset($this['day_completion_value']);
    }
}
