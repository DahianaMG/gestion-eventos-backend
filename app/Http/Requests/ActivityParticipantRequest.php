<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActivityParticipantRequest extends FormRequest
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
            'display_name' => ['required', 'string', 'max:100'],
        ];

        $user = $this->user();

        $participantId = $this->route('id'); //Get the participant ID from the route
        $participant = \App\Models\ActivityParticipant::find($participantId);
        $schedule = $participant->schedule;

        //determines whether the authenticated user is the event organizer
        $isOrganizer = $schedule->event->user_id === $user->id;

        //If the user is admin or the event organizer, allow editing user_id and schedule_id
        if ($this->user()->role === 'admin' || $isOrganizer) {
            $rules['user_id'] = ['required', 'integer', 'exists:users,id'];
            $rules['schedule_id'] = ['required', 'integer', 'exists:schedules,id'];
        }

        //For update requests, use 'sometimes' instead of 'required'
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
            'display_name.required' => 'The display name is required.',
            'display_name.string'   => 'The display name must be a string.',
            'display_name.max'      => 'The display name may not be greater than 100 characters.',

            'user_id.required'      => 'The user ID is required.',
            'user_id.integer'       => 'The user ID must be a valid number.',
            'user_id.exists'        => 'The selected user does not exist.',

            'schedule_id.required'  => 'The schedule ID is required.',
            'schedule_id.integer'   => 'The schedule ID must be a valid number.',
            'schedule_id.exists'    => 'The selected schedule does not exist.',
        ];
    }
}
