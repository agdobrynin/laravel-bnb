<?php

namespace Tests\Unit;

use App\Models\Bookable;
use App\ValueObject\PriceBreakdownVO;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

class PriceBreakdownTest extends TestCase
{
    public function testSuccess(): void
    {
        $bookable = Bookable::factory()->make(['id' => Str::uuid()->toString()]);

        $vo = new PriceBreakdownVO($bookable, '2023-06-05', '2023-06-11');

        $regularPriceTotal = $bookable->price * 5;
        $weekendPriceTotal = $bookable->price_weekend * 2;

        $this->assertEquals($regularPriceTotal + $weekendPriceTotal, $vo->totalPrice);

        $this->assertEquals($regularPriceTotal, $vo->breakdown['regular']->totalPrice);
        $this->assertEquals(5, $vo->breakdown['regular']->days);
        $this->assertEquals($bookable->price, $vo->breakdown['regular']->pricePerDay);
        $this->assertEquals($regularPriceTotal, $vo->breakdown['regular']->totalPrice);

        $this->assertEquals($weekendPriceTotal, $vo->breakdown['weekend']->totalPrice);
        $this->assertEquals(2, $vo->breakdown['weekend']->days);
        $this->assertEquals($bookable->price_weekend, $vo->breakdown['weekend']->pricePerDay);
        $this->assertEquals($weekendPriceTotal, $vo->breakdown['weekend']->totalPrice);

        $this->assertEquals($weekendPriceTotal, $vo->breakdown['weekend']->totalPrice);

        $this->assertEquals('2023-06-05', $vo->dateStart);
        $this->assertEquals('2023-06-11', $vo->dateEnd);
    }
}
