<?php

namespace App\Http\Controllers\Api;

use App\Dto\CheckoutRequestDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Http\Resources\CheckoutSuccessResource;
use App\Mail\BookingMade;
use App\Services\BookingByUserService;
use App\Virtual\Response\HeaderSetCookieToken;
use App\Virtual\Response\HttpNotFoundResponse;
use App\Virtual\Response\HttpValidationErrorResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Mail;
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
    #[HttpValidationErrorResponse(description: 'Input validation or Fail for availability booking dates')]
    public function __invoke(CheckoutRequest $request, BookingByUserService $bookingByUserService): AnonymousResourceCollection
    {
        $dto = CheckoutRequestDto::fromRequest($request);
        $bookings = $bookingByUserService->handle($dto, $request->user());

        foreach ($bookings as $booking) {
            $email = $request->user()?->email ?: $dto->person->email;
            Mail::to($email)->send(new BookingMade($booking));
        }

        return CheckoutSuccessResource::collection($bookings);
    }
}
