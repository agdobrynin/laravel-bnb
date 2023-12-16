<?php

declare(strict_types=1);

namespace App\Dto;

use App\Contracts\DtoFromRequest;
use App\Http\Requests\CheckoutRequest;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema]
readonly class CheckoutRequestDto implements DtoFromRequest
{
    public function __construct(
        #[OA\Property]
        public CheckoutPersonDto $person,
        /** @var CheckoutBookingDto[] */
        #[OA\Property(
            items: new OA\Items(ref: CheckoutBookingDto::class),
        )]
        public array $bookings,
    ) {
    }

    public static function fromRequest(CheckoutRequest|FormRequest $request): self
    {
        $bookings = array_map(static function (array $booking) {
            return new CheckoutBookingDto(...$booking);
        }, $request->validated('bookings'));

        $person = $request->validated('person');

        if ($user = $request->user()) {
            $person['email'] = $user->email;
        }

        return new self(
            new CheckoutPersonDto(...$person),
            $bookings,
        );
    }
}
