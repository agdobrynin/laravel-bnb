<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FetchUserResource;
use App\Virtual\Response\HeaderSetCookieToken;
use App\Virtual\Response\HttpErrorResponse;
use App\Virtual\Response\ValidationErrorResponse;
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
        headers: [new HeaderSetCookieToken],
        content: new OA\JsonContent(ref: FetchUserResource::class),
    )]
    #[HttpErrorResponse(code: 401, description: 'Unauthenticated')]
    public function __invoke(Request $request): FetchUserResource
    {
        return new FetchUserResource($request->user());
    }

    // Virtual methods for Laravel/Fortify
    #[OA\Put(
        path: '/user/profile-information',
        summary: 'Update user profile information',
        security: [['sanctum' => []]],
        tags: ['User'],
    )]
    #[OA\RequestBody(
        content: new OA\JsonContent(
            required: ['first_name', 'last_name', 'email'],
            properties: [
                new OA\Property(property: 'first_name', title: 'user name', type: 'string', minimum: 2),
                new OA\Property(property: 'last_name', title: 'user last name', type: 'string', minimum: 2),
                new OA\Property(property: 'email', type: 'string', format: 'email')
            ],
        )
    )]
    #[ValidationErrorResponse]
    #[HttpErrorResponse(code: 401, description: 'Unauthenticated')]
    #[OA\Response(
        response: 200,
        description: 'Success update',
        headers: [new HeaderSetCookieToken],
        content: new OA\JsonContent(),
    )]
    public function profileInformation(): void
    {
        throw new \RuntimeException('Not implemented here.');
    }
}
