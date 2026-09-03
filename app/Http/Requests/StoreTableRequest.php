<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTableRequest extends FormRequest
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
            'table_number' => 'required|string|unique:tables,table_number,id|regex:/^[a-zA-Z0-9\-]+$/',
            'type' => 'required|in:private,public',
            'min_capacity' => 'required|integer|min:1',
            'max_capacity' => 'required|integer|gte:min_capacity',
            'location' => 'nullable|string|max:100',
            'status' => 'required|in:available,occupied,reserved,maintenance',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
