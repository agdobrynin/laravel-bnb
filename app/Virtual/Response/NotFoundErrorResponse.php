<?php
declare(strict_types=1);

namespace App\Virtual\Response;

use App\Virtual\ErrorMessage;
use OpenApi\Attributes as OA;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class NotFoundErrorResponse extends OA\Response
{
    public function __construct(string $description = 'Not found')
    {
        parent::__construct(
            response: 404,
            description: $description,
            content: new OA\JsonContent(ref: ErrorMessage::class),
        );
    }
}
