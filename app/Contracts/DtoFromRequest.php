<?php

namespace App\Contracts;

use Illuminate\Foundation\Http\FormRequest;

interface DtoFromRequest
{
    public static function fromRequest(FormRequest $request): self;
}
