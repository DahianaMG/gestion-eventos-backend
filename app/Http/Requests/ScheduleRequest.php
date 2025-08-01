<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleRequest extends FormRequest
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
            'event_id'             => ['required', 'integer', 'exists:events,id'],
            'activity_name'        => ['required', 'string', 'max:100'],
            'start_time'           => ['required', 'date_format:H:i'],
            'end_time'             => ['required', 'date_format:H:i', 'after:start_time'],
            'location_description' => ['required', 'string', 'max:150'],
        ];

        //For update requests, replace 'required' with 'sometimes' in the validation rules.
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
            'event_id.required'             => 'The event ID is required.',
            'event_id.integer'              => 'The event ID must be a valid number.',
            'event_id.exists'               => 'The selected event does not exist.',
            'activity_name.required'        => 'The activity name is required.',
            'activity_name.max'             => 'The activity name may not be greater than 100 characters.',
            'start_time.required'           => 'The start time is required.',
            'start_time.date_format'        => 'The start time must be in the format HH:MM.',
            'end_time.required'             => 'The end time is required.',
            'end_time.date_format'          => 'The end time must be in the format HH:MM.',
            'end_time.after'                => 'The end time must be after the start time.',
            'location_description.required' => 'The location description is required.',
            'location_description.max'      => 'The location description may not be greater than 150 characters.',
        ];
    }
}
