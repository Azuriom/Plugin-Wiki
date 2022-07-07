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
        $page = $this->route('page');

        return [
            'title' => ['required', 'string', 'max:100'],
            'slug' => ['required', 'max:100', new Slug(), Rule::unique(Page::class)->ignore($page, 'slug')],
            'category_id' => ['required', 'exists:wiki_categories,id'],
            'content' => ['required', 'string'],
        ];
    }
}
