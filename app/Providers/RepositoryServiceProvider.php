<?php

namespace App\Providers;

use App\Repositories\BookableRepository;
use App\Repositories\BookingRepository;
use App\Repositories\Contracts\BookableRepositoryInterface;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Repositories\Contracts\PersonAddressRepositoryInterface;
use App\Repositories\PersonAddressRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            PersonAddressRepositoryInterface::class,
            PersonAddressRepository::class
        );
        $this->app->bind(
            BookableRepositoryInterface::class,
            BookableRepository::class
        );
        $this->app->bind(
            BookingRepositoryInterface::class,
            BookingRepository::class,
        );
    }
}
