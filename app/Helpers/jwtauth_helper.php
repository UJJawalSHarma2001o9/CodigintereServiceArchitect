<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

if (!function_exists('generateJWT')) {
    function generateJWT($data)
    {
        $key = getenv('JWT_SECRET');
        $payload = [
            'iat' => time(),                // Issued at
            'exp' => time() + 3600,         // Expiry time (1 hour)
            'data' => $data                 // User data
        ];
        return JWT::encode($payload, $key, 'HS256');
    }
}

if (!function_exists('validateJWT')) {
    function validateJWT($token)
    {
        try {
            $key = getenv('JWT_SECRET');
            return JWT::decode($token, new Key($key, 'HS256'));
        } catch (Exception $e) {
            return false;
        }
    }
}
