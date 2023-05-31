<?php
declare(strict_types=1);

namespace App\Virtual\Response;

use App\Virtual\Message;
use OpenApi\Attributes as OA;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class HttpNotFoundResponse extends OA\Response
{
    public function __construct(string $description = 'Not found')
    {
        parent::__construct(
            response: 404,
            description: $description,
            headers: [new HeaderSetCookieToken],
            content: new OA\JsonContent(ref: Message::class),
        );
    }
}
