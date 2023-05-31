<?php
declare(strict_types=1);

namespace App\Virtual\Response;

use OpenApi\Attributes as OA;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class HeaderSetCookieToken extends OA\Header
{
    public function __construct()
    {
        parent::__construct(
            header: 'Set-Cookie',
            description: 'Set Sanctum session auth header',
            required: true,
            schema: new OA\Schema(type: 'string', example: 'XSRF-TOKEN=eyJpdiI6I; expires=Wed, 31 May 2023 10:31:16 GMT; Max-Age=7200; path=/; samesite=lax'),
        );
    }
}
