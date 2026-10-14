<?php

namespace App\Services;

use RuntimeException;

class ApiEncryptionService
{
    private function getKey(): string
    {
        $key = base64_decode(
            (string) config('api-encryption.key'),
            true
        );

        // AES-256-CBC requires a 32-byte encryption key.
        if ($key === false || strlen($key) !== 32) {
            throw new RuntimeException(
                'API encryption key must contain exactly 32 decoded bytes.'
            );
        }

        return $key;
    }

    private function getMacKey(): string
    {
        // Derive a separate key for HMAC authentication.
        return hash_hmac(
            'sha256',
            'api-encryption-cbc-mac',
            $this->getKey(),
            true
        );
    }

    public function encrypt(array $payload): array
    {
        $iv = random_bytes(16);

        $plaintext = json_encode(
            $payload,
            JSON_THROW_ON_ERROR
        );

        $ciphertext = openssl_encrypt(
            $plaintext,
            'aes-256-cbc',
            $this->getKey(),
            OPENSSL_RAW_DATA,
            $iv
        );

        if ($ciphertext === false) {
            throw new RuntimeException('Encryption failed.');
        }

        $ivEncoded = base64_encode($iv);
        $dataEncoded = base64_encode($ciphertext);

        // Authenticate the encoded IV and ciphertext.
        $mac = hash_hmac(
            'sha256',
            $ivEncoded . '.' . $dataEncoded,
            $this->getMacKey()
        );

        return [
            'encrypted' => true,
            'iv' => $ivEncoded,
            'data' => $dataEncoded,
            'mac' => $mac,
        ];
    }

    public function decrypt(array $payload): array
    {
        foreach (['iv', 'data', 'mac'] as $field) {
            if (
                !isset($payload[$field]) ||
                !is_string($payload[$field])
            ) {
                throw new RuntimeException(
                    'Invalid encrypted payload.'
                );
            }
        }

        if (($payload['encrypted'] ?? false) !== true) {
            throw new RuntimeException(
                'Invalid encrypted payload.'
            );
        }

        $iv = base64_decode($payload['iv'], true);
        $ciphertext = base64_decode($payload['data'], true);

        if (
            $iv === false ||
            strlen($iv) !== 16 ||
            $ciphertext === false ||
            $ciphertext === '' ||
            strlen($ciphertext) % 16 !== 0
        ) {
            throw new RuntimeException(
                'Invalid encrypted payload.'
            );
        }

        // Verify integrity before decrypting.
        $expectedMac = hash_hmac(
            'sha256',
            $payload['iv'] . '.' . $payload['data'],
            $this->getMacKey()
        );

        if (!hash_equals($expectedMac, $payload['mac'])) {
            throw new RuntimeException(
                'Payload authentication failed.'
            );
        }

        $plaintext = openssl_decrypt(
            $ciphertext,
            'aes-256-cbc',
            $this->getKey(),
            OPENSSL_RAW_DATA,
            $iv
        );

        if ($plaintext === false) {
            throw new RuntimeException(
                'Decryption failed.'
            );
        }

        $decoded = json_decode(
            $plaintext,
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        if (!is_array($decoded)) {
            throw new RuntimeException(
                'Invalid decrypted JSON payload.'
            );
        }

        return $decoded;
    }
}
