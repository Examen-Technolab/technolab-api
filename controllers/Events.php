<?php
class Events
{
    private $db;
    private $errHandler;

    private $fields = array('code', 'logo', 'title', 'date', 'lastDay', 'about', 'btnText', 'btnLink', 'withPage', 'isLinkResult', 'links', 'description');

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
                ':code' => $data['code'],
                ':logo' => $data['logo'],
                ':title' => $data['title'],
                ':dateString' => $data['date'],
                ':lastDay' => $data['lastDay'],
                ':btnText' => $data['btnText'],
                ':btnLink' => $data['btnLink'],
                ':withPage' => $data['withPage'],
                ':isLinkResult' => $data['isLinkResult'] ? 1 : 0,
                ':about' => $data['about'],
                ':links' => $data['links'],
                ':description' => $data['description']
            );
        }
        $this->errHandler->setError(400, '', 'Некорректное тело запроса. Отсутствует одно из полей: ' . $this->fieldsToString());
    }

    private function findEventByID($eventID = false)
    // найти и вернуть карточку по id, если нет, то в ответе false
    {
        return $this->db->runSQLFile('eventByID', array(':eventId' => $eventID));
        // если нет карточки с таким id, то вернется false
    }

    private function findEventByCode($code)
    {
        // вернуть карточку по продукту, лучше по артикулу
        // если нет карточки с таким продуктом, то возвращает false

        return $this->db->runSQLFile('eventByCode', array(':code' => $code));

    }

    function getCards() // вернуть все карточки
    {
        echo $this->db->runSQLFile('cards');
    }

    function postEvent() // добавить карточку
    {
        //получаем данные из тела запроса
        $JSONdata = file_get_contents('php://input');
        $data = json_decode($JSONdata, true);

        //формируем массив параметров запроса и проверяем поля
        $params = $this->generateParams();

        if ($data['code']) {
            $code = $data['code'];

            if ($this->findEventByCode($code)) {
                $this->errHandler->setError(409, '', 'Такая страница уже существует!');

            }
        }

        $this->db->runSQLFile('addEvent', $params);


        echo json_encode(['ok' => true]);
    }

    function patchEvent() // изменить карточку
    {
        //проверяем передан ли id 
        if (isset($_GET['id'])) {

            $eventID = $_GET['id'];

            //формируем массив параметров запроса
            $params = $this->generateParams();

            $oldEvent = $this->findEventByID($eventID); //проверяем, есть ли мероприятие с таким id

            if ($oldEvent) {

                $this->db->runSQLFile('patchEvent', array(':eventId' => $eventID, ...$params));

                echo json_encode(array('data' => $this->findEventByID($eventID)));
                die();
            } else {
                $this->errHandler->setError(404, '', 'Не найдена карточка с таким ID ' . $eventID);
                die();
            }
        } else {
            $this->errHandler->setError(400, '', 'Некорректный ID');
        }
    }

    function deleteEvent($eventID = false) // удалить карточку
    {
        //проверяем передан ли id 
        if ($eventID || isset($_GET['id'])) {

            $eventID = $eventID ? $eventID : $_GET['id'];

            $event = $this->findEventByID($eventID);

            $this->db->runSQLFile('deleteEvent', array(':eventId' => $eventID));

            echo $event;

        } else {
            $this->errHandler->setError(400, '', 'Некорректный ID');
            die();
        }
    }
}
?>