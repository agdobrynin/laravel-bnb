<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Models\Booking;
use App\Models\PersonAddress;

class CheckoutController extends Controller
{
    public function __invoke(CheckoutRequest $request)
    {
        $data = $request->validated();
        $personAddress = PersonAddress::create($data['person']);

        return collect($data['bookings'])->map(static function (array $bookingData) use ($personAddress) {
            /** @var Booking $booking */
            $booking = Booking::make($bookingData);
            $booking->price = 200;
            $booking->bookable_id = $bookingData['bookable_id'];
            $booking->personAddress()->associate($personAddress);
            $booking->save();

            return $booking;
        });
    }
}
