<?php

declare(strict_types=1);

namespace App\Virtual\Response;

use App\Virtual\ValidationError;
use OpenApi\Attributes as OA;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class HttpValidationErrorResponse extends OA\Response
{
    public function __construct(string $description = 'Validation error')
    {
        parent::__construct(
            response: 422,
            description: $description,
            headers: [new HeaderSetCookieToken],
            content: new OA\JsonContent(ref: ValidationError::class),
        );
    }
}
