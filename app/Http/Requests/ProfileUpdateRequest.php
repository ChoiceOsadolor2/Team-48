<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Support\InputSanitizer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => InputSanitizer::singleLine($this->input('name')),
            'email' => InputSanitizer::email($this->input('email')),
            'phone' => InputSanitizer::nullableSingleLine($this->input('phone')),
            'address' => InputSanitizer::nullableSingleLine($this->input('address')),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['string', 'max:20'],
            'email' => ['email', 'max:50', Rule::unique(User::class)->ignore($this->user()->id)],
            'phone' => ['nullable', 'string', 'max:20', Rule::unique(User::class)->ignore($this->user()->id),],
            'address' => ['nullable', 'string', 'max:200'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
        ];
    }
}
