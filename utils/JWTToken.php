<?php

require __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTToken
{
    private $key;
    private $alg = "HS256";

    public function __construct($key)
    {
        $this->key = $key;
    }

    public function generateToken($data)
    // возвращает сгенерированный токен для переданных данных
    {
        $payload = [
            'iss' => 'http://api.examen-technolab.ru',
            'sub' => "auth",
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24 * 7), // Токен действителен в течение 7 дней
            'data' => $data
        ];

        $jwt = JWT::encode($payload, $this->key, $this->alg);
        return $jwt;
    }

    public function validateToken($token)
    // проверяет переданный токен
    {
        try {
            JWT::decode($token, new Key($this->key, $this->alg));
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getDataFromToken($token)
    // возвращает данные из переданного токена
    {
        $decoded = JWT::decode($token, new Key($this->key, $this->alg));
        return $decoded->data;
    }
}

?>