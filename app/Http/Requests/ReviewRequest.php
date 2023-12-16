<?php

namespace App\Http\Requests;

use App\Models\Booking;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ReviewRequest extends FormRequest
{
    public ?Booking $booking = null;

    public function authorize(): Response
    {
        $uuid = $this->input('id');

        if ($uuid && $booking = Booking::findByReviewKey($uuid)) {
            $this->booking = $booking;

            if ($this->booking->user_id !== $this->user()?->id) {
                return Response::deny('Forbidden. Your are not owner this booking');
            }
        }

        return Response::allow();
    }

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
