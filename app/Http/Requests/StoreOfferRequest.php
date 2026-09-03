<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOfferRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) {
                    if ($this->discount_type === 'percentage') {
                        if ($value < 1 || $value > 100) {
                            $fail('Percentage discount must be between 1 and 100.');
                        }
                    } else if ($this->discount_type === 'fixed') {
                        if ($value < 0) {
                            $fail('Fixed discount amount must be a positive number.');
                        }
                    }
                },
            ],
            'start_date' => 'required|date|date_format:Y-m-d',
            'end_date' => 'required|date|date_format:Y-m-d|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'applicable_days' => 'nullable|array',
            'applicable_days.*' => 'integer|in:1,2,3,4,5,6,7',
            'display_on_menu' => 'nullable|in:0,1',
            'status' => 'required|in:active,inactive',
            'menu_items' => 'required|string|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'start_date.required' => 'The start date field is required.',
            'start_date.date' => 'The start date must be a valid date.',
            'end_date.required' => 'The end date field is required.',
            'end_date.date' => 'The end date must be a valid date.',
            'end_date.after_or_equal' => 'The end date must be greater than or equal to the start date.',
            'menu_items.required' => 'Please select at least one item for this offer.',
        ];
    }


}
