<?php
function auth($jwt)
{
    if (isset($_COOKIE['JWT'])) {
        $token = $_COOKIE['JWT'];
        if (!$jwt->validateToken($token)) {
            // Ошибка аутентификации - отправляем ошибку и завершаем выполнение
            header('HTTP/1.0 401 Unauthorized');
            echo json_encode(array('message' => "Ошибка аутентификации"));
            die();
        }
    } else {
        // Нет токена - завершаем выполнение
        echo json_encode(array('message' => "не авторизован"));
        die();
    }
}

?>