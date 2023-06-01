<?php
declare(strict_types=1);

namespace App\Virtual\Properties;

use OpenApi\Attributes as OA;

class PropertyRating extends OA\Property
{
    public function __construct()
    {
        parent::__construct(
            property: 'rating',
            description: 'Bookable rating',
            type: 'integer',
            default: 1,
            maximum: 5,
            minimum: 1,
            enum: [1, 2, 3, 4, 5]
        );
    }
}
