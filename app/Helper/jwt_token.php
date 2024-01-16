<?php

namespace App\Helper;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;

class jwt_token
{

   

    public static function create($userEmail): string
    {
        $key=env("JWT_KEY");
        $payload = [
            "iss" => 'laravel-token',
            'iat' => time(),
            'exp' => time() + 60 * 60,
            'userEmail' => $userEmail,
        ];

       return JWT::encode($payload, $key, 'HS256');

    }
    public static function createTokenForSet($userEmail): string
    {
        $key=env("JWT_KEY");
        $payload = [
            "iss" => 'laravel-token',
            'iat' => time(),
            'exp' => time() + 60 * 10,
            'userEmail' => $userEmail,
        ];

       return JWT::encode($payload, $key, 'HS256');

    }

    public static function verify($token)
    {
        try {
            $key=env('JWT_KEY');
            $decoded = JWT::decode($token, new Key($key ,'HS256'));

           $decoded->userEmail;

            // Perform any additional actions with the decoded data

        } catch (\Exception $e) {
           
                return'unauthorized';
         
        }
    }
}
