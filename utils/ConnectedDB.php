<?php

class ConnectedDB
{
    private $connect;
    private $errHandler;

    public function __construct($config, $errHandler)
    {
        $this->errHandler = $errHandler;

        //подключаемся к БД при создании экземпляра
        try {
            $this->connect = new PDO($config['db_url'], $config['user'], $config['password']);
        } catch (PDOException $e) {
            $this->errHandler->setError(500, "Error!: " . $e->getMessage());
            exit;
        }
    }

    private function executeSQL($sql, $sql_params = array()) //выполняет запрос с параметрами
    {
        $stmt = $this->connect->prepare($sql);

        $stmt->execute($sql_params);

        return $stmt;
    }

    private function getDataSQL($sql, $sql_params = array()) // выполняет запрос с параметрами и возвращает результат
    {
        $stmt = $this->executeSQL($sql, $sql_params);

        $elementList = array();

        while ($element = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $elementList[] = $element;
        }

        return $elementList;
    }

    function runSQLFile($sql_name, $sql_params = array())
    //выполняет запрос из sql файла с параметрами и возвращает данные в json
    //если данных нет, то возвращает false
    {
        $sql_file = __DIR__ . "/../sql/$sql_name.sql";

        $query = file_get_contents($sql_file);

        $elementList = $this->getDataSQL($query, $sql_params);

        if (count($elementList)) {
            return json_encode($elementList);
        } else
            return false;
    }

    function findUser($login)
    //ищет пользователя по логину и возвращает его в в виде объекта
    {
        $users = $this->getDataSQL("SELECT * FROM users where login = :lgn;", array(':lgn' => $login));
        if (!count($users)) {
            $this->errHandler->setError(404, '', 'Пользователь не найден');
            die();
        } else
            return $users[0];
    }
}
?>