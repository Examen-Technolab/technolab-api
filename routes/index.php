<?php
//роутер 
require_once __DIR__ . '/../utils/Router.php';

// контроллеры
require_once __DIR__ . '/../controllers/Users.php';
require_once __DIR__ . '/../controllers/Cards.php';
require_once __DIR__ . '/../controllers/Info.php';

// авторизация и аутентификация
require_once __DIR__ . '/../middlewares/auth.php';

require 'cards.php';

function useRouter($db, $jwt, $errHandler) //запускает соответствующие запросу роуты
{
    $sections = array('about', 'contacts', 'descriptions', 'downloads', 'education', 'manuals', 'partners', 'cardTypes'); //вынести в константы

    $router = new Router(); //объект-роут для запуска контрроллеров

    $usersController = new Users($db, $errHandler); //контроллер для работы с пользователями
    $cardsController = new Cards($db, $errHandler); //контроллер для работы с карточками
    $infoController = new Info($db, $errHandler); //контроллер для работы с карточками

    //для post запроса по урлу sign-in производим вход пользователя
    $router->start('POST', 'sign-in', function () use ($usersController, $jwt) {
        $usersController->login($jwt);
    });

    //для post запроса по урлу sign-out производим выход пользователя
    $router->start('POST', 'sign-out', function () use ($usersController) {
        $usersController->logout();
    });

    //проверка куков
    $router->start('GET', 'check', function () use ($usersController, $jwt) {
        $usersController->check($jwt);
    });

    //для get запроса по урлу cards возвращаем все карточки
    $router->start('GET', 'cards', function () use ($cardsController) {
        $cardsController->getCards();
    });

    //для get запроса по урлу card возвращаем карточку с соответствующим id
    $router->start('GET', 'card', function () use ($cardsController) {
        $cardsController->getCard();
    });

    //для get запроса по урлу info возвращаем описание карточки с соответствующим id
    $router->start('GET', 'info', function () use ($infoController) {
        $infoController->getInfo();
    });

    //для get запроса по урлу секции возвращаем информацию по секции
    foreach ($sections as $section) {
        $router->start('GET', $section, function () use ($db, $section) {
            echo $db->runSQLFile($section);
        });
    }

    auth($jwt); //защита роутов 

    //доступные только админам роуты

    $router->start('POST', 'info', function () use ($infoController) {
        $infoController->postInfo();
    });

    $router->start('PATCH', 'info', function () use ($infoController) {
        $infoController->patchInfo();
    });

    $router->start('DELETE', 'info', function () use ($infoController) {
        $infoController->deleteInfo();
    });

    //для post запроса по урлу cards добавляем карточку
    $router->start('POST', 'cards', function () use ($cardsController) {
        $cardsController->postCard();
    });

    //для DELETE запроса по урлу card удаляем карточку
    $router->start('DELETE', 'card', function () use ($cardsController) {
        $cardsController->deleteCard();
    });

    //для PATCH запроса по урлу card изменяем карточку
    $router->start('PATCH', 'card', function () use ($cardsController) {
        $cardsController->patchCard();
    });

    //для post запроса по урлу image загружаем картинку в соответствующую папку на сервере
    $router->start('POST', 'image', function () use ($errHandler) {

        //вынести в контроллер
        $file = file_get_contents('php://input');
        if (isset($_GET['name'], $_GET['dir'], $_GET['mainDir'])) {
            $name = $_GET['name'];
            $dir = $_GET['dir'];
            $mainDir = $_GET['mainDir'];
            $fileDir = __DIR__ . "/../../examen-technolab.ru/images/$mainDir/$dir";
            $filename = "$name.png";
            if (!file_exists($fileDir)) {
                mkdir($fileDir, 0777, true);
            }
            file_put_contents("$fileDir/$filename", $file);
            echo json_encode(array('message' => 'ok'));
        } else
            $errHandler->setError(400, '', 'Переданы некорректные параметры name, dir или mainDir');
    });

    //$router->use('cards', fn() => cardsRouter());

    $errHandler->setError(404, '', 'Роут не найден');
}

?>