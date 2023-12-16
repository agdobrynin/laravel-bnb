<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ReviewRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|size:36|unique:reviews',
            'description' => 'required|min:2',
            'rating' => 'required|in:1,2,3,4,5',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($this->user() && ! $this->user()->email_verified_at) {
                $validator->errors()->add(
                    'verify-email',
                    'Please verify email'
                );
            }
        });
    }
}
