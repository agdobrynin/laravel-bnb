<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BookableCategory extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name'];

    public function bookables(): HasMany
    {
        return $this->hasMany(Bookable::class);
    }
}
