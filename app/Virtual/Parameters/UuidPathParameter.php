<?php

declare(strict_types=1);

namespace App\Virtual\Parameters;

use Attribute;
use OpenApi\Attributes as OA;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class UuidPathParameter extends OA\PathParameter
{
    public function __construct(string $name, string $description = 'Uuid')
    {
        parent::__construct(
            name: $name,
            description: $description,
            required: true,
            schema: new OA\Schema(type: 'string', format: 'uuid'),
        );
    }
}
