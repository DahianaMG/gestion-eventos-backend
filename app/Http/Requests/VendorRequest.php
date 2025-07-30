<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorRequest extends FormRequest
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
            'stand_name'        => ['required', 'string', 'max:100'],
            'stand_description' => ['required', 'string'],
            'stand_location'    => ['required', 'string', 'max:100'],
        ];

        //If the authenticated user is admin it requires user_id
        if ($this->user()->role === 'admin') {
            $rules['user_id']  = ['required', 'integer', 'exists:users,id'];
            $rules['event_id'] = ['required', 'integer', 'exists:events,id'];
        }

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            foreach ($rules as $field => &$ruleSet) {
                array_unshift($ruleSet, 'sometimes');
                $ruleSet = array_filter($ruleSet, fn($rule) => $rule !== 'required');
            }
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'user_id.required'           => 'The user ID is required.',
            'user_id.integer'            => 'The user ID must be a valid number.',
            'event_id.required'          => 'The event ID is required.',
            'event_id.integer'           => 'The event ID must be a valid number.',
            'user_id.exists'             => 'The selected user does not exist.',
            'event_id.integer'           => 'The event ID must be a valid number.',
            'event_id.exists'            => 'The selected event does not exist.',
            'stand_name.required'        => 'The stand name is required.',
            'stand_name.max'             => 'The stand name may not be greater than 100 characters.',
            'stand_description.required' => 'The stand description is required.',
            'stand_location.required'    => 'The stand location is required.',
            'stand_location.max'         => 'The stand location may not be greater than 100 characters.',
        ];
    }
}
