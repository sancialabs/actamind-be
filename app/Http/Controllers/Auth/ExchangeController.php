<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExchangeController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string',
        ]);

        $token = cache()->pull("oauth_code:{$validated['code']}");

        if (! $token) {
            return response()->json([
                'message' => 'Invalid or expired code',
            ], 401);
        }

        return response()->json([
            'token' => $token,
        ]);
    }
}
