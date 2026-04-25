<?php

namespace App\Http\Controllers\Api\ClientApp;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientApp\Support\StoreSupportMessageRequest;
use Illuminate\Http\JsonResponse;

class ClientSupportController extends Controller
{
    public function contactInfo(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'company_name' => config('company.name', 'Olejos'),
                'phone' => config('company.phone', '+48 000 000 000'),
                'email' => config('company.email', 'kontakt@olejos.pl'),
                'address' => config('company.address', 'ul. Przykładowa 1, 00-000 Miasto'),
                'working_hours' => 'Pon - Pt: 8:00 - 16:00'
            ]
        ]);
    }

    public function sendMessage(StoreSupportMessageRequest $request): JsonResponse
    {
        $user = $request->user();
        $client = $user->client;
        $data = $request->validated();

        \Illuminate\Support\Facades\Log::info('Support message received from client app', [
            'client_id' => $client->id,
            'client_name' => $client->company_name,
            'user_email' => $user->email,
            'subject' => $data['subject'],
            'message' => $data['message'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Your message has been sent to our support team.'
        ]);
    }
}