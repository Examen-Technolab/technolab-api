<?php
class Web
{
    private $db;
    private $errHandler;

    private $fields = array('date', 'title', 'subtitle', 'about');

    public function __construct($db, $errHandler)
    {
        $this->errHandler = $errHandler;
        $this->db = $db;
    }

    private function checkFields($data) //проверка, есть ли все поля в $data
    {
        $result = true;
        foreach ($this->fields as &$field) {
            $result = $result && isset($data[$field]);
        }
        return $result;
    }

    private function fieldsToString() //переводит поля из массива в строку
    {
        $result = '';
        foreach ($this->fields as &$field) {
            $end = ($field === $this->fields[count($this->fields) - 1]) ? '.' : ', ';
            $result = $result . $field . $end;
        }
        return $result;
    }

    private function generateParams() // формируем массив параметров из тела запроса
    {
        //получаем данные из тела запроса
        $JSONdata = file_get_contents('php://input');
        $data = json_decode($JSONdata, true);
        if ($this->checkFields($data)) {
            //формируем массив параметров запроса
            return array(
                ':dateData' => $data['date'],
                ':title' => $data['title'],
                ':subtitle' => $data['subtitle'],
                ':about' => $data['about'],
                ':link' => $data['link'] ?: '',
                ':video' => $data['video'] ?: ''
            );
        }
        $this->errHandler->setError(400, '', 'Некорректное тело запроса. Отсутствует одно из полей: ' . $this->fieldsToString());
    }

    private function findWebByID($webID = false)
    // найти и вернуть карточку по id, если нет, то в ответе false
    {
        return $this->db->runSQLFile('webByID', array(':webId' => $webID));
        // если нет карточки с таким id, то вернется false
    }


    function getWebs() // вернуть все карточки
    {
        echo $this->db->runSQLFile('web');
    }

    function postWeb() // добавить карточку
    {
        //формируем массив параметров запроса и проверяем поля
        $params = $this->generateParams();

        $this->db->runSQLFile('addWeb', $params);

        echo json_encode(['ok' => true]);
    }

    function patchWeb() // изменить карточку
    {
        //проверяем передан ли id 
        if (isset($_GET['id'])) {

            $webID = $_GET['id'];

            //формируем массив параметров запроса
            $params = $this->generateParams();

            $oldWeb = $this->findWebByID($webID); //проверяем, есть ли мероприятие с таким id

            if ($oldWeb) {

                $this->db->runSQLFile('patchWeb', array(':webId' => $webID, ...$params));

                echo json_encode(array('data' => $this->findWebByID($webID)));
                die();
            } else {
                $this->errHandler->setError(404, '', 'Не найдена карточка с таким ID ' . $webID);
                die();
            }
        } else {
            $this->errHandler->setError(400, '', 'Некорректный ID');
        }
    }

    function deleteWeb($webID = false) // удалить карточку
    {
        //проверяем передан ли id 
        if ($webID || isset($_GET['id'])) {

            $webID = $webID ? $webID : $_GET['id'];

            $web = $this->findWebByID($webID);

            $this->db->runSQLFile('deleteWeb', array(':webId' => $webID));

            echo $web;

        } else {
            $this->errHandler->setError(400, '', 'Некорректный ID');
            die();
        }
    }
}
?>