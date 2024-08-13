<?php
class Info
{
    private $db;
    private $errHandler;

    private $fields = array('card_id', 'tab', 'title', 'list', 'note');
    //вынести из класса

    public function __construct($db, $errHandler)
    {
        $this->errHandler = $errHandler;
        $this->db = $db;
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

    private function checkFields($data) //проверка, есть ли все поля в $data
    {
        $result = true;
        foreach ($this->fields as &$field) {
            $result = $result && isset($data[$field]);
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
                ':card_id' => $data['card_id'],
                ':tab' => $data['tab'],
                ':title' => $data['title'],
                ':list' => $data['list'],
                ':note' => $data['note'],
            );
        } else {

        }
        $this->errHandler->setError(400, '', 'Некорректное тело запроса. Отсутствует одно из полей: ' . $this->fieldsToString());
    }

    private function findInfoByID($infoID = false)
    // найти и вернуть карточку по id, если нет, то в ответе false
    {
        return $this->db->runSQLFile('infoByID', array(':id' => $infoID));
        // если нет карточки с таким id, то вернется false
    }

    function getInfo()
    {
        if (isset($_GET['tab'], $_GET['id'])) {
            $cardID = $_GET['id'];
            $tab = $_GET['tab'];
            if ($cardID && is_numeric($cardID)) {
                echo $this->db->runSQLFile('info', array(':card_id' => $cardID, ':tab' => $tab));
                die();
            }
        }
        $this->errHandler->setError(400, '', 'Переданы некорректные параметры tab или id');

        // если нет карточки с таким id, то вернется false
    }


    function postInfo() // добавить карточку
    {
        //формируем массив параметров запроса и проверяем поля
        $params = $this->generateParams();

        $this->db->runSQLFile('addInfo', $params);

        echo json_encode(array('ok' => true));
    }

    function patchInfo() // изменить карточку
    {
        //проверяем передан ли id 
        if (isset($_GET['id'])) {

            $infoID = $_GET['id'];


            //формируем массив параметров запроса
            $params = $this->generateParams();


            $oldInfo = $this->findInfoByID($infoID); //проверяем, есть ли карточка с таким id

            if (!$oldInfo) {
                $this->errHandler->setError(404, '', 'Не найдено описание с таким ID');
                die();
            }

            $this->db->runSQLFile('patchInfo', array(':id' => $infoID, ...$params));

            echo $this->findInfoByID($infoID);

        } else {
            $this->errHandler->setError(400, '', 'Некорректный ID');
            die();
        }
    }

    function deleteInfo() // удалить карточку
    {
        //проверяем передан ли id 
        if (isset($_GET['id'])) {

            $infoID = $_GET['id'];

            $card = $this->findInfoByID($infoID);

            $this->db->runSQLFile('deleteInfo', array(':id' => $infoID));

            echo json_encode(
                array(
                    'ok' => true,
                    'data' => $card
                )
            );

        } else {
            $this->errHandler->setError(400, '', 'Некорректный ID');
            die();
        }
    }
}
?>