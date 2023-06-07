<?php
declare(strict_types=1);

namespace App\Virtual\Response;

use App\Dto\MessageResponseDto;
use OpenApi\Attributes as OA;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class HttpErrorResponse extends OA\Response
{
    public function __construct(int $code, string $description = 'Http exception')
    {
        parent::__construct(
            response: $code,
            description: $description,
            headers: [new HeaderSetCookieToken],
            content: new OA\JsonContent(ref: MessageResponseDto::class),
        );
    }
}
