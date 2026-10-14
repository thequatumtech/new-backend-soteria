<?php

namespace App\Http\Middleware;

use App\Services\ApiEncryptionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class EncryptApiResponse
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $response = $next($request);

        if ($request->header('X-API-Encryption') !== '1') {
            return $response;
        }

        // Only encrypt JSON responses.
        if (
            !str_contains(
                strtolower($response->headers->get('Content-Type', '')),
                'application/json'
            )
        ) {
            return $response;
        }

        try {
            $content = $response->getContent();

            if (!is_string($content) || $content === '') {
                return $response;
            }

            $payload = json_decode($content, true);

            if (!is_array($payload)) {
                return $response;
            }

            // Prevent double encryption.
            if (($payload['encrypted'] ?? false) === true) {
                return $response;
            }

            $encrypted = app(ApiEncryptionService::class)->encrypt($payload);

            $response->setContent(
                json_encode($encrypted, JSON_THROW_ON_ERROR)
            );

            $response->headers->remove('Content-Length');
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'Unable to encrypt response.',
            ], 500);
        }
    }
}
