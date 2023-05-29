<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.1',
    title: 'API for BnB service'
)]
#[OA\Server(
    url: '/api',
    description: 'Main endpoint'
)]
// Reusable parameters
#[OA\PathParameter(
    name: 'bookable',
    required: true,
    schema: new OA\Schema(title: 'Bookable uuid', type: 'string', format: 'uuid'),
)]
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
