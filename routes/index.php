<?php
//роутер 
require_once __DIR__ . '/../utils/Router.php';

// контроллеры
require_once __DIR__ . '/../controllers/Users.php';
require_once __DIR__ . '/../controllers/Cards.php';
require_once __DIR__ . '/../controllers/Info.php';
require_once __DIR__ . '/../controllers/Images.php';
require_once __DIR__ . '/../controllers/Events.php';
require_once __DIR__ . '/../controllers/Web.php';

// авторизация и аутентификация
require_once __DIR__ . '/../middlewares/auth.php';

require 'cards.php';

function useRouter($db, $jwt, $errHandler) //запускает соответствующие запросу роуты
{
    $sections = array('events', 'about', 'contacts', 'descriptions', 'downloads', 'education', 'manuals', 'partners', 'cardTypes'); //вынести в константы

    $router = new Router(); //объект-роут для запуска контрроллеров

    $usersController = new Users($db, $errHandler); //контроллер для работы с пользователями
    $cardsController = new Cards($db, $errHandler); //контроллер для работы с карточками
    $infoController = new Info($db, $errHandler); //контроллер для работы с карточками
    $imagesController = new Images($db, $errHandler); //контроллер для работы с картинками
    $eventsController = new Events($db, $errHandler); //контроллер для работы с мероприятиями
    $webController = new Web($db, $errHandler); //контроллер для работы с вебинарами

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


    //для get запроса по урлу card возвращаем скрытую карточку с соответствующим id
    $router->start('GET', 'hidden', function () use ($cardsController) {
        $cardsController->getCard(1);
    });

    //для get запроса по урлу info возвращаем описание карточки с соответствующим id
    $router->start('GET', 'info', function () use ($infoController) {
        $infoController->getInfo();
    });

    //для get запроса по урлу web возвращаем все карточки
    $router->start('GET', 'web', function () use ($webController) {
        $webController->getWebs();
    });

    //для get запроса по урлу секции возвращаем информацию по секции
    foreach ($sections as $section) {
        $router->start('GET', $section, function () use ($db, $section) {
            $result = $db->runSQLFile($section);
            echo $result ? $result : json_encode([]);
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

    //для post запроса по урлу events добавляем карточку
    $router->start('POST', 'events', function () use ($eventsController) {
        $eventsController->postEvent();
    });

    //для DELETE запроса по урлу event удаляем карточку
    $router->start('DELETE', 'event', function () use ($eventsController) {
        $eventsController->deleteEvent();
    });

    //для PATCH запроса по урлу event изменяем карточку
    $router->start('PATCH', 'event', function () use ($eventsController) {
        $eventsController->patchEvent();
    });

    //для get запроса по урлу product проверяем уникальность имени прождукта
    $router->start('GET', 'product', function () use ($cardsController) {
        $cardsController->getProduct();
    });

    //для post запроса по урлу image загружаем картинку в соответствующую папку на сервере
    $router->start('POST', 'image', function () use ($imagesController) {
        $imagesController->postImages();
    });

    //для post запроса по урлу image загружаем картинку в соответствующую папку на сервере
    $router->start('DELETE', 'image', function () use ($imagesController) {
        $imagesController->deleteImages();
    });

    //для post запроса по урлу web добавляем вебинар
    $router->start('POST', 'web', function () use ($webController) {
        $webController->postWeb();
    });

    //для DELETE запроса по урлу event удаляем карточку
    $router->start('DELETE', 'web', function () use ($webController) {
        $webController->deleteWeb();
    });

    //для PATCH запроса по урлу event изменяем карточку
    $router->start('PATCH', 'web', function () use ($webController) {
        $webController->patchWeb();
    });

    //$router->use('cards', fn() => cardsRouter());

    $errHandler->setError(404, '', 'Роут не найден');
}

?>