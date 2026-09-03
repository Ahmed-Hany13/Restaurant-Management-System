<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
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
        // dd(request()->all());
        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'phone' => ['required','numeric'],
            'guest_count' => ['required', 'integer', 'min:1'],
            'reservation_type' => ['required', 'in:now,scheduled'],
            'table_type' => ['nullable', 'in:private,public'],
            'table_id' => ['nullable', 'exists:tables,id'],
            'reservation_date' => ['nullable', 'required_if:reservation_type,scheduled', 'date', 'after_or_equal:today'],
            'reservation_time' => ['nullable', 'required_if:reservation_type,scheduled'],
            'duration_hours' => ['nullable', 'in:1,1.5,2'],
            'special_occasion' => ['nullable', 'in:Birthday,Anniversary,Business,Other,None'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
