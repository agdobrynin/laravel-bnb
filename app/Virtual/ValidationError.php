<?php

declare(strict_types=1);

namespace App\Virtual;

use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(property: 'message', description: 'Main validation error', type: 'string'),
        new OA\Property(
            property: 'errors',
            properties: [
                new OA\Property(
                    property: 'fieldName',
                    title: 'All error for field name (field key)',
                    type: 'array',
                    items: new OA\Items(type: 'string'),
                ),
            ],
            type: 'object',
        ),
    ]
)]
class ValidationError
{
}
