<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UpdateVideoRequest extends FormRequest
{
    public function authorize()
    {
        return ($this->user()->can('create video') && $this->video['user_id'] === $this->user()['id'] ) || $this->user()->hasRole(User::ROLE_ADMIN);
    }

    public function rules()
    {
        return [
            'title' => ['required', 'string'],
            'link' => ['required', 'string'],
            'source' => ['required', 'string', Rule::in([Video::SOURCE_WISTIA, Video::SOURCE_YOUTUBE])],
            'category' => ['required'],
            'tag' => ['nullable'],
        ];
    }

    protected function prepareForValidation()
    {
        $this['tag'] = !$this['tag'] ? [] : $this['tag'];
        $this['category'] = !$this['category'] ? [] : $this['category'];

        if (str_starts_with($this['link'], 'https://www.youtube.com/watch?v=')) {
            $link = explode('&', $this['link'])[0];
            $this['link'] = str_replace('https://www.youtube.com/watch?v=', 'https://www.youtube.com/embed/', $link);
            $this['source'] = Video::SOURCE_YOUTUBE;
        }

        if (str_starts_with($this['link'], 'https://youtu.be/')) {
            $this['link'] = str_replace('https://youtu.be/', 'https://www.youtube.com/embed/', $this['link']);
            $this['source'] = Video::SOURCE_YOUTUBE;
        }

        if (str_contains($this['link'], 'wistia.com/medias/')) {
            $wistiaId = explode('wistia.com/medias/',$this['link'])[1];
            $this['link'] = 'https://fast.wistia.net/embed/iframe/'.$wistiaId;
            $this['source'] = Video::SOURCE_WISTIA;
        }

        if (str_starts_with($this['link'], 'https://www.youtube.com/embed/')) {
            $this['source'] = Video::SOURCE_YOUTUBE;
        }

        if (str_starts_with($this['link'], 'https://fast.wistia.net/embed/iframe/')) {
            $this['source'] = Video::SOURCE_WISTIA;
        }

        if ($this['source'] == Video::SOURCE_YOUTUBE && !str_starts_with($this['link'], 'https://www.youtube.com/embed/')) {
            throw ValidationException::withMessages([
                'link' => ['This link is not youtube valid link',],
            ]);
        }

        if ($this['source'] == Video::SOURCE_WISTIA && !str_starts_with($this['link'], 'https://fast.wistia.net/embed/iframe/')) {
            throw ValidationException::withMessages([
                'link' => ['This link is not wistia valid link',],
            ]);
        }

        foreach ($this['category'] as $category) {
            if (!in_array($category, Video::CATEGORY_LIST)) {
                throw ValidationException::withMessages([
                    'category' => ['Invalid category',],
                ]);
            }
        }

        if ($this->user()->hasRole(User::ROLE_USER_ADMIN)){
            $this['category'] = [Video::CATEGORY_PRIVATE];
        }
    }
}
