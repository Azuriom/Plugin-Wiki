<?php

namespace Azuriom\Plugin\Wiki\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => ['required', 'string', 'max:100'],
            'category_id' => ['required', 'exists:wiki_categories,id'],
            'content' => ['required', 'string'],
        ];
    }
}
