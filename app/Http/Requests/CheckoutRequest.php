<?php

namespace App\Http\Requests;

use App\Models\Bookable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
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
            'bookings' => 'required|array|min:1',
            'bookings.*.start' => 'required|date_format:Y-m-d|after_or_equal:today',
            'bookings.*.end' => 'required|date_format:Y-m-d|after:bookings.*.start',
            'person.first_name' => 'required|min:2',
            'person.last_name' => 'required|min:2',
            'person.address' => 'required|min:10',
            'person.email' => 'required|email',
            'person.phone' => 'nullable|regex:/^([0-9]{6,11})$/',
            'bookings.*' => [
                'required',
                function ($attribute, $value, $fail) {
                    $bookableId = $value['bookable_id'];

                    $bookable = Bookable::findOr($bookableId, static function () use ($bookableId) {
                        $message = sprintf('Bookable with id "%s" not found', $bookableId);

                        throw new ModelNotFoundException($message);
                    });

                    if ($bookable->availableForDate($value['start'], $value['end'])->count()) {
                        $fail('Object is not available for given dates');
                    }
                }
            ],
        ];

    }
}
