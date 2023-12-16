<?php

namespace App\Http\Requests;

use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Http\FormRequest;

class BookingByReviewRequest extends FormRequest
{
    public function authorize(): Response
    {
        return $this->user()?->id === $this->route('booking')?->user_id
            ? Response::allow()
            : Response::deny('Your are not owner this review');
    }

    public function rules(): array
    {
        return [
            //
        ];
    }
}
