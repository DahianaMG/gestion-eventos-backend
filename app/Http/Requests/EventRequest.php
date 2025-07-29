<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
        $rules = [
            'title'       => ['required', 'string', 'max:150'],
            'description' => ['required', 'string'],
            'date_time'   => ['required', 'date', 'after:now'],
            'location'    => ['required', 'string', 'max:150'],
            'has_fair'    => ['required', 'boolean'],
            'capacity'    => ['required', 'integer', 'min:1'],
        ];

        // If the authenticated user is admin it requires user_id
        //if (Auth::check() && Auth::user()->role === 'admin') {
        //    $rules['user_id'] = ['required', 'exists:users,id'];
        //}

        return $rules;
    }

    public function messages(): array
    {
        return [
            'user_id.required'     => 'The user ID is required.',
            'user_id.exists'       => 'The selected user does not exist.',
            'title.required'       => 'The event title is required.',
            'title.max'            => 'The event title may not be greater than 150 characters.',
            'description.required' => 'The event description is required.',
            'date_time.required'   => 'The event date and time is required.',
            'date_time.date'       => 'The event date must be a valid date.',
            'date_time.after'      => 'The event date must be in the future.',
            'location.required'    => 'The event location is required.',
            'location.max'         => 'The location may not be greater than 150 characters.',
            'has_fair.required'    => 'The fair field is required.',
            'has_fair.boolean'     => 'The fair field must be true or false.',
            'capacity.required'    => 'The capacity is required.',
            'capacity.integer'     => 'The capacity must be a valid number.',
            'capacity.min'         => 'The capacity must be at least 1.',
        ];
    }
}
