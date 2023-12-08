<?php

declare(strict_types=1);

namespace App\Virtual;

use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(
            property: 'links',
            properties: [
                new OA\Property(property: 'first', type: 'string', format: 'url', example: 'http://localhost/api/bookables?page=1'),
                new OA\Property(property: 'last', type: 'string', format: 'url', example: 'http://localhost/api/bookables?page=9'),
                new OA\Property(property: 'next', type: 'string', format: 'url', example: 'http://localhost/api/bookables?page=2', nullable: true),
                new OA\Property(property: 'prev', type: 'string', format: 'url', example: null, nullable: true),
            ],
            type: 'object',
        ),
    ]
)]
class PaginateShort
{
}
