<?php

namespace App\Http\Requests;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCourseRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('create course');
    }

    public function rules()
    {
        return [
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'category' => ['required', 'string', Rule::in(Course::CATEGORY_LIST)],
            'level' => ['required', 'string', Rule::in(Course::LEVEL_LIST)],
            'price_in_usd' => ['required', 'integer', 'min:0'],
        ];
    }

    protected function prepareForValidation()
    {
        $this['category'] = isset($this['category']) ? $this['category'] : 'Custom Training';
        $this['level'] = isset($this['level']) ? $this['level'] : 'Beginner';
        $this['price_in_usd'] = isset($this['price_in_usd']) ? $this['price_in_usd'] : 0;
    }

    protected function passedValidation()
    {
        $this['is_custom'] = $this->user()->hasRole(User::ROLE_USER_ADMIN);
        $this['status'] = Course::STATUS_DRAFT;
    }
}
