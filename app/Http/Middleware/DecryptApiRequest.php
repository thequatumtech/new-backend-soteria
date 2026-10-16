<?php

namespace App\Http\Middleware;

use App\Services\ApiEncryptionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class DecryptApiRequest
{


    public function handle(
        Request $request,
        Closure $next
    ): Response {
        if ($request->header('X-API-Encryption') !== '1') {
            return $next($request);
        }

        // GET/HEAD requests without a body can still receive
        // encrypted responses.
        if (
            in_array($request->method(), ['GET', 'HEAD'], true)
            && $request->getContent() === ''
        ) {
            return $next($request);
        }

        try {
            $contentType = strtolower(
                $request->header('Content-Type', '')
            );

            if ($request->isJson()) {
                // Encrypted JSON request.
                $payload = $request->json()->all();
            } elseif (
                str_contains($contentType, 'multipart/form-data')
            ) {
                // Multipart request: encrypted text fields are
                // sent inside a form-data field named "payload".
                $rawPayload = $request->input('payload');

                if (!is_string($rawPayload)) {
                    return response()->json([
                        'message' => 'Encrypted payload field is required.',
                    ], 400);
                }

                $payload = json_decode(
                    $rawPayload,
                    true,
                    512,
                    JSON_THROW_ON_ERROR
                );

                if (!is_array($payload)) {
                    return response()->json([
                        'message' => 'Invalid encrypted payload.',
                    ], 400);
                }
            } else {
                return response()->json([
                    'message' => 'JSON or multipart/form-data required.',
                ], 415);
            }

            if (($payload['encrypted'] ?? false) !== true) {
                return response()->json([
                    'message' => 'Invalid encrypted payload.',
                ], 400);
            }

            $data = app(ApiEncryptionService::class)
                ->decrypt($payload);

            // Global TrimStrings / ConvertEmptyStringsToNull ran before
            // decryption, so apply the same normalisation to decrypted data.
            $data = $this->normalizeInput($data);

            if ($request->isJson()) {
                $request->json()->replace($data);
            }

            // Update the standard input bag for controllers using $request->input().
            $request->request->replace($data);

        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'Unable to decrypt request.',
            ], 400);
        }

        return $next($request);
    }

    private function normalizeInput(array $data): array
    {
        $exceptTrim = ['current_password', 'password', 'password_confirmation'];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->normalizeInput($value);
            } elseif (is_string($value)) {
                if (!in_array($key, $exceptTrim, true)) {
                    $value = trim($value);
                }
                $data[$key] = $value === '' ? null : $value;
            }
        }

        return $data;
    }
}
