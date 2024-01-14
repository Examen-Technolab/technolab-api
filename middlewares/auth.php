<?php
function auth($jwt)
{
    if (isset($_COOKIE['JWT'])) {
        $token = $_COOKIE['JWT'];
        if ($jwt->validateToken($token)) {
            // Аутентификация прошла успешно 
            // echo "Данные пользователя: " . $jwt->getDataFromToken($token);
            return $jwt->getDataFromToken($token);
        } else {
            // Ошибка аутентификации - возвращаем ошибку
            header('HTTP/1.0 401 Unauthorized');
            echo "Ошибка аутентификации";
            die();
        }
    } else {
        echo "неавторизован";
        die();
    }
}

?>