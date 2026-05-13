<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|unique:menu_items|string|max:200',
            'menu_section_id' => 'required|exists:menu_sections,id',
            'menu_category_id' => 'required|exists:menu_categories,id',
            'menu_subcategory_id' => 'required|exists:menu_subcategories,id',
            'description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'preparation_time' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The item name is required.',
            'name.max' => 'The item name must not exceed 200 characters.',
            'menu_section_id.required' => 'Please select a section.',
            'menu_category_id.required' => 'Please select a category.',
            'menu_subcategory_id.required' => 'Please select a subcategory.',
            'price.required' => 'The price is required.',
            'price.numeric' => 'The price must be a valid number.',
            'price.min' => 'The price must be at least 0.',
            'image.required' => 'Please upload an image.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a JPEG, PNG, JPG, or GIF file.',
            'image.max' => 'The image must not exceed 2MB.',
            'preparation_time.integer' => 'The preparation time must be a whole number.',
            'preparation_time.min' => 'The preparation time must be at least 0 minutes.',
            'status.required' => 'Please select a status.',
            'status.in' => 'The status must be either active or inactive.',
        ];
    }
}
