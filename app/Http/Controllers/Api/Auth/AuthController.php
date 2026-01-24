<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\UpdateUserRequest;
use App\Http\Requests\Auth\CheckEmailRequest;
use Illuminate\Http\JsonResponse;
use App\Exceptions\AccountDeactivatedException;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    #[OA\Post(
        path: '/api/auth/register',
        summary: 'Register a new user',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['first_name', 'last_name', 'email', 'password', 'role'],
                properties: [
                    new OA\Property(property: 'first_name', type: 'string', example: 'Kyle'),
                    new OA\Property(property: 'last_name', type: 'string', example: 'Lowry'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password123'),
                    new OA\Property(
                        property: 'role',
                        type: 'string',
                        enum: ['admin', 'driver', 'employee'],
                        example: 'admin'
                    ),
                ]
            )
        ),
        tags: ['Authentication'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'User registered successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'User registered successfully'),
                        new OA\Property(
                            property: 'data',
                            properties: [
                                new OA\Property(property: 'user', type: 'object'),
                                new OA\Property(property: 'token', type: 'string'),
                            ],
                            type: 'object'
                        ),
                    ]
                )
            ),
            new OA\Response(response: 500, description: 'Server error')
        ]
    )]
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->authService->registerUser([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = $this->authService->generateToken($user);

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ], 201);
    }

    #[OA\Post(
        path: '/api/auth/login',
        summary: 'Login user',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password123'),
                ]
            )
        ),
        tags: ['Authentication'],
        responses: [
            new OA\Response(response: 200, description: 'Login successful'),
            new OA\Response(response: 401, description: 'Invalid credentials'),
            new OA\Response(response: 403, description: 'Account deactivated')
        ]
    )]
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $user = $this->authService->authenticateUser([
                'email' => $request->email,
                'password' => $request->password,
            ]);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials'
                ], 401);
            }

            $token = $this->authService->generateToken($user);

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'user' => $user,
                    'token' => $token
                ]
            ], 200);
        } catch (AccountDeactivatedException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Your account has been deactivated'
            ], 403);
        }
    }

    #[OA\Get(
        path: '/api/auth/user',
        summary: 'Get authenticated user',
        security: [['bearerAuth' => []]],
        tags: ['Authentication'],
        responses: [
            new OA\Response(response: 200, description: 'User data retrieved successfully'),
            new OA\Response(response: 401, description: 'Unauthenticated')
        ]
    )]
    public function getUser(): JsonResponse
    {
        $user = $this->authService->getAuthUserInformation();

        return response()->json([
            'success' => true,
            'data' => $user
        ], 200);
    }

    #[OA\Put(
        path: '/api/auth/user',
        summary: 'Update authenticated user',
        security: [['bearerAuth' => []]],
        tags: ['Authentication'],
        responses: [
            new OA\Response(response: 200, description: 'User updated successfully'),
            new OA\Response(response: 401, description: 'Unauthenticated')
        ]
    )]
    public function updateUser(UpdateUserRequest $request): JsonResponse
    {
        $data = $request->only(['name', 'email']);

        if ($request->has('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user = $this->authService->updateAuthUserInformation($data);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => $user
        ], 200);
    }

    #[OA\Post(
        path: '/api/auth/logout',
        summary: 'Logout user',
        security: [['bearerAuth' => []]],
        tags: ['Authentication'],
        responses: [
            new OA\Response(response: 200, description: 'Logged out successfully'),
            new OA\Response(response: 500, description: 'Logout failed')
        ]
    )]
    public function logout(): JsonResponse
    {
        $success = $this->authService->logoutUser();

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Logout failed'
        ], 500);
    }

    #[OA\Post(
        path: '/api/auth/check-email',
        summary: 'Check if email exists',
        tags: ['Authentication'],
        responses: [
            new OA\Response(response: 200, description: 'Email check completed')
        ]
    )]
    public function checkEmail(CheckEmailRequest $request): JsonResponse
    {
        $exists = $this->authService->userExists($request->email);

        return response()->json([
            'success' => true,
            'exists' => $exists
        ], 200);
    }
}