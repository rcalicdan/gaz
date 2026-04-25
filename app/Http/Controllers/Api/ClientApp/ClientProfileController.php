<?php

namespace App\Http\Controllers\Api\ClientApp;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientApp\Profile\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Client App - Profile', description: 'Profile and company settings endpoints for the mobile application')]
class ClientProfileController extends Controller
{
    #[OA\Get(
        path: '/api/client-app/profile',
        summary: 'Get the current user and company profile',
        description: 'Returns the logged-in user\'s details along with the associated company (Client) information, including addresses and default waste type.',
        security: [['bearerAuth' => []]],
        tags: ['Client App - Profile'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Profile data retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            description: 'User object with nested client data',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'first_name', type: 'string', example: 'Jan'),
                                new OA\Property(property: 'last_name', type: 'string', example: 'Kowalski'),
                                new OA\Property(property: 'email', type: 'string', example: 'jan.kowalski@example.com'),
                                new OA\Property(
                                    property: 'client',
                                    type: 'object',
                                    properties: [
                                        new OA\Property(property: 'id', type: 'integer', example: 1),
                                        new OA\Property(property: 'company_name', type: 'string', example: 'EcoTech Sp. z o.o.'),
                                        new OA\Property(property: 'vat_id', type: 'string', example: '1234567890'),
                                        new OA\Property(property: 'registered_street_name', type: 'string', example: 'ul. Zielona 15'),
                                        new OA\Property(property: 'premises_street_name', type: 'string', example: 'ul. Magazynowa 10A', nullable: true),
                                        new OA\Property(property: 'contact_person', type: 'string', example: 'Jan Kowalski'),
                                    ]
                                )
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 403,
                description: 'Unauthorized - Not a client account',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthorized'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated')
        ]
    )]
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user->isClient() || !$user->client_id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $user->load('client.phoneNumbers', 'client.defaultWasteType')
        ]);
    }

    #[OA\Put(
        path: '/api/client-app/profile',
        summary: 'Update company profile and pickup location',
        description: 'Updates the contact person and the physical premises (pickup) address. NOTE: Updating the address automatically triggers background geocoding to update GPS coordinates for route optimization.',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'contact_person', type: 'string', example: 'Anna Nowak'),
                    new OA\Property(property: 'premises_street_name', type: 'string', example: 'ul. Magazynowa'),
                    new OA\Property(property: 'premises_street_number', type: 'string', example: '10A'),
                    new OA\Property(property: 'premises_city', type: 'string', example: 'Poznań'),
                    new OA\Property(property: 'premises_zip_code', type: 'string', example: '60-001'),
                    new OA\Property(property: 'premises_province', type: 'string', example: 'Wielkopolskie'),
                ]
            )
        ),
        tags: ['Client App - Profile'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Profile updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Profile updated successfully'),
                        new OA\Property(property: 'data', type: 'object', description: 'Updated User object with nested client data')
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error (e.g., missing required fields)'
            ),
            new OA\Response(response: 401, description: 'Unauthenticated')
        ]
    )]
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->user();
        $client = $user->client;

        $client->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $user->fresh('client')
        ]);
    }
}
