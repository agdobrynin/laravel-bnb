<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FetchUserResource;
use App\Virtual\Response\HttpErrorResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
class UserController extends Controller
{
    #[OA\Get(
        path: '/api/user',
        summary: 'Get info about authenticated user',
        security: [['sanctum' => []]],
        tags: ['Auth', 'User'],
    )]
    #[OA\Response(
        response: 201,
        description: 'Success',
        content: new OA\JsonContent(ref: FetchUserResource::class),
    )]
    #[HttpErrorResponse(code: 401, description: 'Unauthenticated')]
    public function __invoke(Request $request): FetchUserResource
    {
        return new FetchUserResource($request->user());
    }
}
