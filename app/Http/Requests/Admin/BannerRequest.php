<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $desktop = $this->route('banner') ? 'nullable' : 'required';

        return [
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'desktop_image' => $desktop.'|image|mimes:jpg,jpeg,png,webp|max:4096',
            'mobile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'button_text' => 'nullable|string|max:80',
            'button_url' => ['nullable', 'string', 'max:255', function (string $attribute, mixed $value, \Closure $fail) {
                if ($value === null || $value === '') {
                    return;
                }
                $internal = str_starts_with($value, '/') && ! str_starts_with($value, '//');
                $scheme = parse_url($value, PHP_URL_SCHEME);
                $external = filter_var($value, FILTER_VALIDATE_URL) && in_array($scheme, ['http', 'https'], true);
                if (! $internal && ! $external) {
                    $fail('Enter a site path starting with / or an http(s) URL.');
                }
            }],
            'sort_order' => 'required|integer|min:0|max:9999',
            'status' => 'required|in:0,1',
        ];
    }
}
