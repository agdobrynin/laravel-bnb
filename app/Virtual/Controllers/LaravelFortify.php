<?php

declare(strict_types=1);

namespace App\Virtual\Controllers;

use App\Dto\MessageResponseDto;
use App\Virtual\Models\EmailPassword;
use App\Virtual\Response\HeaderSetCookieToken;
use App\Virtual\Response\HttpErrorResponse;
use App\Virtual\Response\HttpValidationErrorResponse;
use OpenApi\Attributes as OA;

// Virtual controller for Laravel Fortify package.

final class LaravelFortify
{
    #[OA\Post(
        path: '/login',
        summary: 'Auth user and generating cookie header.',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(ref: EmailPassword::class)
        ),
        tags: ['Auth'],
    )]
    #[HttpErrorResponse(code: 422, description: 'These credentials do not match our records')]
    #[OA\Response(
        response: 200,
        description: 'Success auth',
        headers: [new HeaderSetCookieToken],
        content: new OA\JsonContent(),
    )]
    public function login(): void
    {
    }

    #[OA\Post(
        path: '/logout',
        summary: 'Logoff user. Invalidate auth cookie.',
        security: [['sanctum' => []]],
        tags: ['Auth'],
    )]
    #[OA\Response(
        response: 204,
        description: 'Logout success',
        headers: [new HeaderSetCookieToken],
        content: new OA\JsonContent()
    )]
    public function logout(): void
    {
    }

    #[OA\Get(
        path: '/sanctum/csrf-cookie',
        summary: 'Refresh CSRF cookie (app protection app)',
        security: [['sanctum' => []]],
        tags: ['Auth'],
    )]
    #[OA\Response(
        response: 204,
        description: 'Get new cookie',
        headers: [new HeaderSetCookieToken],
        content: new OA\JsonContent(),
    )]
    public function csrfCookie(): void
    {
    }

    #[OA\Post(
        path: '/forgot-password',
        summary: 'Emailed link for reset password',
        security: [['sanctum' => []]],
        tags: ['Auth'],
    )]
    #[OA\RequestBody(
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'email', type: 'string', format: 'email'),
            ])
    )]
    #[HttpValidationErrorResponse]
    #[OA\Response(
        response: 200,
        description: 'Success',
        headers: [new HeaderSetCookieToken],
        content: new OA\JsonContent(ref: MessageResponseDto::class),
    )]
    public function forgotPassword(): void
    {
    }

    #[OA\Post(
        path: '/register',
        description: 'After register user application emailed verification link. Firstly account is unverified.',
        summary: 'Register new user',
        security: [['sanctum' => []]],
        tags: ['Auth'],
    )]
    #[OA\RequestBody(
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'first_name', title: 'user name', type: 'string', minimum: 2),
                new OA\Property(property: 'last_name', title: 'user last name', type: 'string', minimum: 2),
                new OA\Property(property: 'password_confirmation', type: 'string'),
            ],
            allOf: [
                new OA\Schema(ref: EmailPassword::class),
            ]
        )
    )]
    #[HttpValidationErrorResponse]
    #[OA\Response(
        response: 201,
        description: 'Success register',
        headers: [new HeaderSetCookieToken],
        content: new OA\JsonContent(),
    )]
    public function register(): void
    {
    }
}
