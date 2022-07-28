<?php

namespace Azuriom\Plugin\Wiki\Requests;

use Azuriom\Plugin\Wiki\Models\Page;
use Azuriom\Rules\Slug;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $uniqueRule = Rule::unique(Page::class)
            ->where('category_id', $this->input('category_id'))
            ->ignore($this->route('page'), 'slug');

        return [
            'title' => ['required', 'string', 'max:100'],
            'slug' => ['required', 'max:100', new Slug(), $uniqueRule],
            'category_id' => ['required', 'exists:wiki_categories,id'],
            'content' => ['required', 'string'],
        ];
    }
}
