<?php
class Users
{
    private $db;
    private $errHandler;

    public function __construct($db, $errHandler)
    {
        $this->errHandler = $errHandler;
        $this->db = $db;
    }

    function checkCookie($jwt) //проверить куки на наличие jwt
    {
        if (isset($_COOKIE['JWT'])) {
            $token = $_COOKIE['JWT'];
            if ($jwt->validateToken($token)) {
                // Аутентификация прошла успешно 
                echo ($jwt->getDataFromToken($token));
                return true;
            } else {
                // Ошибка аутентификации - возвращаем ошибку
                $this->errHandler->setError(401, '', 'Отказано в доступе.');
            }
        }
        //  $this->errHandler->setError(400, '', 'Отсутствует токен');
        return false;
    }

    function login($jwt) // вход
    {
        //проверяем куки
        if ($this->checkCookie($jwt)) {
            echo json_encode(array('message' => "уже авторизован"));
            die();
        }

        //получаем логин и пароль из тела запроса
        $JSONdata = file_get_contents('php://input');
        $data = json_decode($JSONdata, true);

        //проверяем тело запроса
        if (isset($data['login'], $data['password'])) {
            $login = $data['login'];
            $password = $data['password'];

            //ищем пользователя в БД по логину и проверяем пароль
            $user = $this->db->findUser($login);
            $isLoggedIn = password_verify($password, $user['password']);

            // выдаем в куках токен на 7 дней, если пароль верен
            if ($isLoggedIn) {
                $token = $jwt->generateToken(json_encode(array('login' => $user['login'], 'isAdmin' => $user['isAdmin'])));
                setcookie('JWT', $token, strtotime('+7 days'));
                http_response_code(200);
                echo $jwt->getDataFromToken($token);
                die();
            } else {
                //неверный логин или пароль
                $this->errHandler->setError(401, '', 'Неверный логин или пароль');
                die();
            }
        } else
            $this->errHandler->setError(400, '', 'Некорректное тело запроса. Отсутствует поле login или password');

    }

    function logout() //выход
    {
        setcookie('JWT', '', time() - 3600);
        echo json_encode(array('message' => "Выход успешно завершен"));
        die();
    }
}
?>