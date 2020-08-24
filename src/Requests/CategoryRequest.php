<?php

namespace Azuriom\Plugin\Wiki\Requests;

use Azuriom\Plugin\Wiki\Models\Category;
use Azuriom\Rules\Slug;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $slugRule = Rule::unique(Category::class)->ignore($this->category, 'slug');

        return [
            'icon' => ['nullable', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:50'],
            'slug' => ['required', 'string', 'max:100', new Slug(), $slugRule],
        ];
    }
}
