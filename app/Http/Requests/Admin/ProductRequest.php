<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('id');
        $slugRule = Rule::unique('product', 'slug');
        $redirectRule = Rule::unique('product_slug_redirects', 'slug');
        if ($productId) {
            $slugRule = $slugRule->ignore($productId);
            $redirectRule = Rule::unique('product_slug_redirects', 'slug')->where(fn ($query) => $query->where('product_id', '!=', $productId));
        }

        $rules = [
            'product_title' => 'required|string|max:255',
            'gst_rate' => 'nullable|numeric|min:0|max:100',
            'slug' => ['nullable', 'string', 'max:191', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slugRule, $redirectRule],
            'category_id' => 'required|integer|exists:category,id',
            'sub_category_id' => [
                'required',
                'integer',
                Rule::exists('sub_category', 'id')->where(fn ($query) => $query->where('category_id', $this->input('category_id'))),
            ],
            'ternary_category_id' => [
                'nullable',
                'integer',
                Rule::exists('ternary_category', 'ternary_id')->where(fn ($query) => $query->where('sub_category_id', $this->input('sub_category_id'))),
            ],
            'sku_id' => 'required|string|max:255',
            'actual_price' => 'required|numeric|min:0|max:999999.99',
            'discounted_price' => 'required|numeric|min:0|max:999999.99',
            'description' => 'required|string',
            'product_highlights' => 'nullable|string',
            'length' => 'nullable|string|max:16',
            'width' => 'nullable|string|max:16',
            'height' => 'nullable|string|max:16',
            'm_unit' => 'nullable|string|max:32',
            'featured_item' => 'required|in:0,1',
            'cod_available' => 'required|in:0,1',
            'status' => 'required|in:0,1',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'images' => 'nullable|array|max:6',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'integer',
            'quantities' => 'nullable|array',
            'quantities.*' => 'integer|min:0|max:100000',
        ];

        if ($productId) {
            $rules['color_id'] = 'nullable|integer|exists:colors,color_id';
            $rules['quantity'] = 'nullable|integer|min:0|max:100000|required_with:color_id';
        } else {
            $rules['color_id'] = 'required|integer|exists:colors,color_id';
            $rules['quantity'] = 'required|integer|min:0|max:100000';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'sub_category_id.exists' => 'Choose a subcategory that belongs to the selected category.',
            'ternary_category_id.exists' => 'Choose a ternary category that belongs to the selected subcategory.',
        ];
    }
}
