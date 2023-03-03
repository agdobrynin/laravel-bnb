<?php

namespace App\Http\Controllers\Api;

use App\Dto\PriceBreakdownDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Http\Resources\CheckoutSuccessResource;
use App\Models\Bookable;
use App\Models\Booking;
use App\Models\PersonAddress;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CheckoutController extends Controller
{
    public function __invoke(CheckoutRequest $request)
    {
        $data = $request->validated();
        $personAddress = PersonAddress::create($data['person']);

        $bookings = collect($data['bookings'])->map(static function (array $bookingData) use ($personAddress) {
            $bookableId = $bookingData['bookable_id'];

            $bookable = Bookable::findOr($bookableId, static function () use ($bookableId) {
                $message = sprintf('Bookable with id "%s" not found', $bookableId);

                throw new ModelNotFoundException($message);
            });


            /** @var Booking $booking */
            $booking = Booking::make($bookingData);
            $priceBreakdownDto = new PriceBreakdownDto($bookable, $booking->start, $booking->end);
            $booking->price = $priceBreakdownDto->totalPrice;
            $booking->bookable()->associate($bookable);
            $booking->personAddress()->associate($personAddress);
            $booking->save();

            return $booking;
        });

        return CheckoutSuccessResource::collection($bookings);
    }
}
