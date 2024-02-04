<?php

// app/Helpers/info_helper.php
use CodeIgniter\CodeIgniter;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Returns CodeIgniter's version.
 */

function extract_payload($header)
{
    $token = extract_bearer_token($header);
    return decode_token($token);
}

function extract_bearer_token($header)
{
    $token = null;

    if (!empty($header) && preg_match('/Bearer\s(\S+)/', $header, $matches)) {
        $token = $matches[1];
    }
    return $token;
}

function decode_token($token)
{
    try {
        $key = getenv('JWT_SECRET');
        $alg = array("HS256");
        if (is_null($token) || empty($token)) throw new \Exception('Token não informado');
        return  JWT::decode($token, new Key($key, 'HS256'));
    } catch (\Exception $e) {
        return null;
    }
}
