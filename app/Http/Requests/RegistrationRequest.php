<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
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
            'event_id'      => ['required', 'exists:events,id'],
            'role_in_event' => ['required', 'string', 'max:20'],
            'status'        => ['required', 'string', 'max:20'],
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
            'user_id.required'       => 'The user ID is required.',
            'user_id.exists'         => 'The selected user does not exist.',
            'event_id.required'      => 'The event ID is required.',
            'event_id.exists'        => 'The selected event does not exist.',
            'role_in_event.required' => 'The role in the event is required.',
            'role_in_event.string'   => 'The role must be a string.',
            'role_in_event.max'      => 'The role may not be greater than 20 characters.',
            'status.required'        => 'The status is required.',
            'status.string'          => 'The status must be a string.',
            'status.max'             => 'The status may not be greater than 20 characters.',
        ];
    }
}
