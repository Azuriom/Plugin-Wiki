<?php

namespace Azuriom\Plugin\Wiki\Requests;

use Azuriom\Http\Requests\Traits\ConvertCheckbox;
use Azuriom\Plugin\Wiki\Models\Category;
use Azuriom\Rules\Slug;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    use ConvertCheckbox;

    /**
     * The attributes represented by checkboxes.
     *
     * @var array<int, string>
     */
    protected array $checkboxes = [
        'is_enabled',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $slugRule = Rule::unique(Category::class)->ignore($this->category, 'slug');

        return [
            'icon' => ['nullable', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:50'],
            'slug' => ['required', 'string', 'max:100', new Slug(), $slugRule],
            'parent_id' => ['nullable', 'exists:wiki_categories,id'],
            'roles' => ['sometimes', 'nullable', 'array'],
            'is_enabled' => ['filled', 'boolean'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->mergeCheckboxes();

        if (! $this->filled('is_private') || ! $this->has('roles')) {
            $this->merge(['roles' => null]);
        }
    }
}
