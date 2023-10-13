<?php
//проверяем домен и разрешаем запрос
// $http_origin = $_SERVER['HTTP_ORIGIN'];

// if ($http_origin == "https://test.examen-technolab.ru" || $http_origin == "https://examen-technolab.ru" || $http_origin == "http://localhost:3000") {
//     header("Access-Control-Allow-Origin: $http_origin");
//     header("Access-Control-Allow-Credentials: true");

//     // Поддерживаемые методы HTTP
//     header('Access-Control-Allow-Methods: GET, POST');

//     // Время, в течение которого веб-браузер может кэшировать ответ (в секундах)
//     header('Access-Control-Max-Age: 86400');

//     // Список языков, которые веб-браузер может использовать для запросов (разрешаются кросс-доменные запросы)
//     header('Access-Control-Allow-Headers: Content-Type');
// }


header('Content-Type: application/json; charset=utf-8');

require 'config/connect.php';
require 'routes/index.php';

$method = $_SERVER['REQUEST_METHOD'];

$url = $_GET['url'];
$params = explode('/', $url);

useRouter($connect, $params, $method);

?>