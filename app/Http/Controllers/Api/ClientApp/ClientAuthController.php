<?php

namespace App\Http\Controllers\Api\ClientApp;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientApp\Auth\RegisterRequest;
use App\Http\Requests\ClientApp\Auth\LoginRequest;
use App\Services\ClientAppAuthService;
use App\Exceptions\AccountDeactivatedException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Client App - Authentication', description: 'Authentication endpoints for the mobile client application')]
class ClientAuthController extends Controller
{
    public function __construct(protected ClientAppAuthService $authService) {}

    #[OA\Post(
        path: '/api/client-app/auth/register',
        summary: 'Register a new client company and user account',
        description: 'Creates a new company (Client) and an associated user. The account will be set to inactive (pending administrator approval).',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: [
                    'first_name', 'last_name', 'email', 'password', 'password_confirmation', 
                    'company_name', 'vat_id', 'registered_street_name', 'registered_city', 
                    'registered_zip_code', 'phone_number'
                ],
                properties: [
                    new OA\Property(property: 'first_name', type: 'string', example: 'Jan'),
                    new OA\Property(property: 'last_name', type: 'string', example: 'Kowalski'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'jan.kowalski@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password123'),
                    new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', example: 'password123'),
                    new OA\Property(property: 'company_name', type: 'string', example: 'EcoTech Sp. z o.o.'),
                    new OA\Property(property: 'vat_id', type: 'string', description: 'Company NIP / VAT ID', example: '1234567890'),
                    new OA\Property(property: 'registered_street_name', type: 'string', example: 'ul. Zielona 15'),
                    new OA\Property(property: 'registered_city', type: 'string', example: 'Warszawa'),
                    new OA\Property(property: 'registered_zip_code', type: 'string', example: '00-123'),
                    new OA\Property(property: 'phone_number', type: 'string', example: '+48 500 600 700'),
                ]
            )
        ),
        tags: ['Client App - Authentication'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Registration successful. Pending admin approval.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Registration successful. Your account is pending administrator approval.'),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation Error (e.g., NIP already exists)',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'This company is already registered. Please log in, or contact support to request app access.'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'vat_id',
                                    type: 'array',
                                    items: new OA\Items(type: 'string', example: 'This company is already registered. Please log in, or contact support to request app access.')
                                )
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function register(RegisterRequest $request): JsonResponse
    {
        $this->authService->registerClient($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Registration successful. Your account is pending administrator approval.',
        ], 201);
    }

    #[OA\Post(
        path: '/api/client-app/auth/login',
        summary: 'Login client user',
        description: 'Authenticates a client and returns an access token. Fails if account is pending approval.',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'jan.kowalski@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password123'),
                ]
            )
        ),
        tags: ['Client App - Authentication'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Login successful',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'user', type: 'object'),
                                new OA\Property(property: 'token', type: 'string', example: 'eyJ0eXAiOiJKV1QiLCJhbGci...'),
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Invalid credentials',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Invalid credentials or not a client account.'),
                    ]
                )
            ),
            new OA\Response(
                response: 403,
                description: 'Account Pending Approval',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Your account is pending approval or has been deactivated.'),
                    ]
                )
            )
        ]
    )]
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $user = $this->authService->authenticateClient($request->validated());

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials or not a client account.'
                ], 401);
            }

            $token = $this->authService->generateToken($user);

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $user->load('client.primaryPhoneNumber'),
                    'token' => $token
                ]
            ]);
        } catch (AccountDeactivatedException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is pending approval or has been deactivated.'
            ], 403);
        }
    }

    #[OA\Post(
        path: '/api/client-app/auth/logout',
        summary: 'Logout client user',
        description: 'Revokes the current access token for the client.',
        security: [['bearerAuth' => []]],
        tags: ['Client App - Authentication'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successfully logged out',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Successfully logged out'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated')
        ]
    )]
    public function logout(Request $request): JsonResponse
    {
        $request->user()->token()->revoke();

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out'
        ]);
    }
}