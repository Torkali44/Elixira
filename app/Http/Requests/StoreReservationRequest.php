<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => ['required', 'regex:/^\+?[0-9\s\-\(\)]{10,22}$/'],
            'reservation_date' => 'required|date|after_or_equal:today',
            'reservation_time' => 'required',
            'guests' => 'required|integer|min:1|max:20',
            'notes' => 'nullable|string|max:1000'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'name.max' => 'Name may not be longer than 255 characters.',
            'phone.required' => 'Phone number is required.',
            'phone.regex' => 'Enter a valid phone number.',
            'reservation_date.required' => 'Reservation date is required.',
            'reservation_date.date' => 'Reservation date is not valid.',
            'reservation_date.after_or_equal' => 'Choose today or a future date.',
            'reservation_time.required' => 'Reservation time is required.',
            'guests.required' => 'Number of guests is required.',
            'guests.integer' => 'Guests must be a whole number.',
            'guests.min' => 'At least one guest is required.',
            'guests.max' => 'Guest count may not exceed 20.',
            'notes.max' => 'Notes may not exceed 1000 characters.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->reservation_date && $this->reservation_time) {
                $reservationDateTime = \Carbon\Carbon::parse($this->reservation_date . ' ' . $this->reservation_time);

                if ($reservationDateTime->isPast()) {
                    $validator->errors()->add('reservation_time', 'Choose a future time for this date.');
                }
            }
        });
    }
}

