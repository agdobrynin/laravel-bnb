<?php

namespace App\Http\Controllers\Api;

use App\Dto\CheckoutBookingDto;
use App\Dto\CheckoutRequestDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Http\Resources\CheckoutSuccessResource;
use App\Models\Bookable;
use App\Models\Booking;
use App\Models\PersonAddress;
use App\ValueObject\PriceBreakdownVO;
use App\Virtual\Response\HeaderSetCookieToken;
use App\Virtual\Response\HttpNotFoundResponse;
use App\Virtual\Response\ValidationErrorResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OA;

class CheckoutController extends Controller
{
    #[OA\Post(
        path: '/api/checkout',
        summary: 'Checkout bookings by user.',
        security: [['sanctum' => []]],
        tags: ['Booking'],
    )]
    #[OA\RequestBody(
        content: new OA\JsonContent(ref: CheckoutRequestDto::class),
    )]
    #[OA\Response(
        response: 200,
        description: 'Success booking',
        headers: [new HeaderSetCookieToken],
        content: new OA\JsonContent(
            ref: CheckoutSuccessResource::class,
        ),

    )]
    #[HttpNotFoundResponse]
    #[ValidationErrorResponse]
    public function __invoke(CheckoutRequest $request): AnonymousResourceCollection
    {
        $dto = CheckoutRequestDto::fromRequest($request);
        $personAddress = PersonAddress::create((array)$dto->person);

        $bookings = collect($dto->bookings)->map(static function (CheckoutBookingDto $checkoutBooking) use ($personAddress, $request) {

            $bookable = Bookable::findOr($checkoutBooking->bookable_id, static function () use ($checkoutBooking) {
                $message = sprintf('Bookable with id "%s" not found', $checkoutBooking->bookable_id);

                throw new ModelNotFoundException($message);
            })->load('bookableCategory');


            /** @var Booking $booking */
            $booking = Booking::make((array)$checkoutBooking);
            $priceBreakdown = new PriceBreakdownVO($bookable, $booking->start, $booking->end);
            $booking->price = $priceBreakdown->totalPrice;
            $booking->bookable()->associate($bookable);
            $booking->personAddress()->associate($personAddress);

            if ($user = $request->user()) {
                $booking->user()->associate($user);
            }

            $booking->save();

            return $booking;
        });

        // send emails for review feedback... maybe with models event?

        return CheckoutSuccessResource::collection($bookings);
    }
}
