<?php
//проверяем домен и разрешаем запрос
$http_origin = $_SERVER['HTTP_ORIGIN'];

//if ($http_origin == "http://localhost:3000") {
if ($http_origin == "https://test.examen-technolab.ru" || $http_origin == "https://examen-technolab.ru") {
    header("Access-Control-Allow-Origin: $http_origin");
    header("Access-Control-Allow-Credentials: true");

    // Поддерживаемые методы HTTP
    header('Access-Control-Allow-Methods: GET, POST, DELETE, PATCH');

    // Время, в течение которого веб-браузер может кэшировать ответ (в секундах)
    header('Access-Control-Max-Age: 86400');

    // Список языков, которые веб-браузер может использовать для запросов (разрешаются кросс-доменные запросы)
    header('Access-Control-Allow-Headers: Content-Type');
}

header('Content-Type: application/json; charset=utf-8');

//в файле конфиги БД и секретный ключ
require_once 'config/config.php';

//класс ошибок и подключенной БД
require_once 'utils/ErrorHandler.php'; //нужно сделать
require_once 'utils/ConnectedDB.php';

// класс для работы с токенами
require_once 'utils/JWTToken.php';

//содержит useRouter для обработки роутов
require_once 'routes/index.php';

// константы
$ERROR_MESSAGE400 = 'Некорректный запрос.';
$ERROR_MESSAGE401 = 'Ошибка авторизации.';
$ERROR_MESSAGE404 = 'Не найдено.';
$ERROR_MESSAGE409 = 'Конфликт.';
$ERROR_MESSAGE = 'Ошибка.';

$jwt = new JWTToken($JWT_SECRET); //объект для работы с jwt
$errHandler = new ErrorHandler(); //обработчик ошибок

//при создании экземпляра подключаемся к БД
$db = new ConnectedDB($db_config, $errHandler); //объект для работы с подключенной БД

useRouter($db, $jwt, $errHandler); //обработка роутов

?>