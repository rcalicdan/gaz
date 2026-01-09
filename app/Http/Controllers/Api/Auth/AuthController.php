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

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

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

    public function getUser(): JsonResponse
    {
        $user = $this->authService->getAuthUserInformation();

        return response()->json([
            'success' => true,
            'data' => $user
        ], 200);
    }

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

    public function checkEmail(CheckEmailRequest $request): JsonResponse
    {
        $exists = $this->authService->userExists($request->email);

        return response()->json([
            'success' => true,
            'exists' => $exists
        ], 200);
    }
}