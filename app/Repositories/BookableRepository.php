<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Bookable;

class BookableRepository implements Contracts\BookableRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function findWithCategory(string $uuid): Bookable
    {
        return Bookable::with('bookableCategory')->find($uuid);
    }
}
