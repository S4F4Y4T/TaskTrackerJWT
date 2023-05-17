<?php
defined('BASEPATH') or exit('No direct script access allowed');

class JWT
{
    private $secret;

    public function __construct()
    {
        $this->secret = 'MGNmNWFjY2I0MzI1NjNiYjViZTA4YTg3YjZiOTg2YmYwZmZmZWQ0NTRkNzZmNzA4Y2FhODI0MWM3ZDdiOTNkMQ'; // Replace with your own secret key
    }

    public function encode($type = 'access_token', $payload, $exp = 3600)
    {
        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'
        ];

        $header['type'] = $type;

        $header = $this->base64url_encode(json_encode($header));
        $payload['exp'] = time() + $exp;
        $payload = $this->base64url_encode(json_encode($payload));

        $signature = hash_hmac('sha256', $header . '.' . $payload, $this->secret);
        $signature = $this->base64url_encode($signature);

        return $header . '.' . $payload . '.' . $signature;
    }

    public function decode($type = 'access_token', $token)
    {
        $tokenParts = explode('.', $token);
        $header = $this->base64url_decode($tokenParts[0]);
        $payload = $this->base64url_decode($tokenParts[1]);
        $signature = $tokenParts[2];

        $header = json_decode($header, true);
        $payload = json_decode($payload, true);

        if($header['type'] !== $type)
        {
            return false;
        }

        $expectedSignature = hash_hmac('sha256', $tokenParts[0] . '.' . $tokenParts[1], $this->secret);
        $expectedSignature = $this->base64url_encode($expectedSignature);

        if ($signature === $expectedSignature) {
            return $payload;
        }

        return false;
    }

    function base64url_encode($data)
    {
        $base64 = base64_encode($data);
        $base64url = strtr($base64, '+/', '-_');
        return rtrim($base64url, '=');
    }

    function base64url_decode($base64url)
    {
        $base64 = strtr($base64url, '-_', '+/');
        $paddedBase64 = $base64url . substr('==', (strlen($base64) % 4));
        return base64_decode($paddedBase64);
    }
}
