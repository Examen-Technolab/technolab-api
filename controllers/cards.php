<?php
class Cards
{
    private $db;
    private $errHandler;

    private $fields = array('product', 'preview', 'lastPreview', 'type', 'title', 'article', 'price');
    //вынести из класса

    public function __construct($db, $errHandler)
    {
        $this->errHandler = $errHandler;
        $this->db = $db;
    }

    private function fieldsToString()
    {
        $result = '';
        foreach ($this->fields as &$field) {
            $end = ($field === $this->fields[count($this->fields) - 1]) ? '.' : ', ';
            $result = $result . $field . $end;
        }
        return $result;
    }


    private function checkFields($data)
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
                ':product' => $data['product'],
                ':preview' => $data['preview'],
                ':lastPreview' => $data['lastPreview'],
                ':isHidden' => 0, //$data['isHidden'],
                ':productType' => $data['type'],
                ':title' => $data['title'],
                ':article' => $data['article'],
                ':price' => $data['price']
            );
        } else {

        }
        $this->errHandler->setError(400, '', 'Некорректное тело запроса. Отсутствует одно из полей: ' . $this->fieldsToString());
    }

    function getCards() // вернуть все карточки
    {
        echo $this->db->runSQLFile('cards');
    }

    function getCard($cardID = false) // вернуть карточку по id
    {
        //проверяем передан ли id 
        if ($cardID || isset($_GET['id'])) {

            $cardID = $cardID ? $cardID : $_GET['id'];

            $card = $this->db->runSQLFile('cardByID', array(':id' => $cardID));
            // если нет карточки с таким id, то вернется false

            if (!$card) {
                $this->errHandler->setError(404, '', 'Нет карточки с таким ID');
                die();
            }

            return $card;
        } else {
            $this->errHandler->setError(400, '', 'Некорректный ID');
            die();
        }
    }

    private function findCardByProduct($product = false)
    {
        // вернуть карточку по продукту, лучше по артикулу
        // если нет карточки с таким продуктом. то возвращает false
        if ($product || isset($_GET['product'])) {
            $product = $product ? $product : $_GET['product'];
            return $this->db->runSQLFile('cardByProduct', array(':product' => $product));
        } else {
            $this->errHandler->setError(400, '', 'Некорректный product');
            die();
        }
    }

    function postCard() // добавить карточку
    {
        //получаем данные из тела запроса
        $JSONdata = file_get_contents('php://input');
        $data = json_decode($JSONdata, true);

        //формируем массив параметров запроса и проверяем поля
        $params = $this->generateParams();

        //если нет такой карты, то добавляем, иначе возвращаем имеющуюся
        // проверяем по полю product - это не очень хорошо
        if ($this->findCardByProduct($data['product'])) {
            $this->errHandler->setError(409, '', 'Такой product уже есть');
        } else {
            $this->db->runSQLFile('addCard', $params);
        }
        echo ($this->findCardByProduct($data['product']));
    }

    function patchCard($cardID = false) // изменить карточку
    {
        //проверяем передан ли id 
        if ($cardID || isset($_GET['id'])) {

            $cardID = $cardID ? $cardID : $_GET['id'];

            //формируем массив параметров запроса
            $params = $this->generateParams();

            $oldCard = $this->getCard($cardID); //проверяем, есть ли карточка с таким id

            $this->db->runSQLFile('patchCard', array(':id' => $cardID, ...$params));

            echo $this->getCard($cardID);

        } else {
            $this->errHandler->setError(400, '', 'Некорректный ID');
            die();
        }
    }

    function deleteCard($cardID = false) // удалить карточку
    {
        //проверяем передан ли id 
        if ($cardID || isset($_GET['id'])) {

            $cardID = $cardID ? $cardID : $_GET['id'];

            $card = $this->getCard($cardID);

            $this->db->runSQLFile('deleteCard', array(':id' => $cardID));

            echo $card;

        } else {
            $this->errHandler->setError(400, '', 'Некорректный ID');
            die();
        }
    }
}
?>