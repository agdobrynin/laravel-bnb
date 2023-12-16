<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Bookable;
use Illuminate\Database\Eloquent\ModelNotFoundException;

interface BookableRepositoryInterface
{
    /**
     * @throws ModelNotFoundException
     */
    public function findWithCategory(string $uuid): Bookable;
}
