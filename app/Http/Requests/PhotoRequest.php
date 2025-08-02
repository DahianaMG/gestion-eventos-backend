<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PhotoRequest extends FormRequest
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
            'event_id'    => ['required', 'integer', 'exists:events,id'],
            'photo'       => ['required', 'file', 'image', 'max:5120'], //max 5MB
            'description' => ['nullable', 'string'],
            'tags'        => ['nullable', 'array'],
            'tags.*'      => ['string', 'max:30'],
        ];
    }

    public function messages(): array
    {
        return [
            'event_id.required' => 'The event ID is required.',
            'event_id.integer'  => 'The event ID must be a valid number.',
            'event_id.exists'   => 'The selected event does not exist.',

            'photo.required' => 'A photo file is required.',
            'photo.file'     => 'The uploaded photo must be a valid file.',
            'photo.image'    => 'The uploaded file must be an image.',
            'photo.max'      => 'The image may not be greater than 5MB.',

            'description.string' => 'The description must be a valid text.',

            'tags.array'      => 'The tags must be an array.',
            'tags.*.string'   => 'Each tag must be a string.',
            'tags.*.max'      => 'Each tag may not be greater than 30 characters.',
        ];
    }
}
