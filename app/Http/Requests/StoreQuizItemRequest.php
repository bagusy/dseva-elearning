<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreQuizItemRequest extends FormRequest
{

    public function authorize()
    {
        return $this->user()->can('create quiz');
    }

    public function rules()
    {
        return [
            'question' => ['required', 'string'],
            'answer' => ['required', 'array'],
            'answer.*' => ['nullable', 'string'],
            'status' => ['required', 'array'],
            'status.*' => ['required', 'boolean'],
        ];
    }

    protected function passedValidation()
    {
        $answers = [];
        foreach ($this['answer'] as $i => $answer) {
            if ($answer !== null) {
                $answers[] = [
                    'status' => $this['status'][$i],
                    'answer' => $answer
                ];
            }
        }
        $this['answers'] = json_encode($answers);
    }
}
