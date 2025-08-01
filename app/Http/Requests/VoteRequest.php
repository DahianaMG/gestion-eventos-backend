<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Event;
use App\Models\Vendor;
use App\Models\Schedule;
use App\Models\ActivityParticipant;

class VoteRequest extends FormRequest
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
    public function prepareForValidation(): void
    {
        $map = [
            'vendor'      => Vendor::class,
            'schedule'    => Schedule::class,
            'participant' => ActivityParticipant::class,
        ];

        if (isset($map[$this->target_type])) {
            $this->merge([
                'target_type' => $map[$this->target_type],
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'event_id'    => ['required', 'integer', 'exists:events,id'],
            'target_type' => ['required', 'string'],
            'target_id'   => ['required', 'integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'event_id.required'    => 'The event ID is required.',
            'event_id.integer'     => 'The event ID must be a number.',
            'event_id.exists'      => 'The event does not exist.',
            'target_type.required' => 'The target type is required.',
            'target_type.string'   => 'The target type must be a string.',
            'target_id.required'   => 'The target ID is required.',
            'target_id.integer'    => 'The target ID must be a number.',
        ];
    }
}
