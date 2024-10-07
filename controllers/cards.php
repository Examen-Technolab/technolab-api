<?php
class Cards
{
    private $db;
    private $errHandler;

    private $fields = array('product', 'preview', 'lastPreview', 'type', 'title', 'article', 'price', 'isHidden', 'ordinal');
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
                ':product' => $data['product'],
                ':preview' => $data['preview'],
                ':lastPreview' => $data['lastPreview'],
                ':isHidden' => $data['isHidden'],
                ':ordinal' => $data['ordinal'],
                ':productType' => $data['type'],
                ':title' => $data['title'],
                ':article' => $data['article'],
                ':price' => $data['price']
            );
        }
        $this->errHandler->setError(400, '', 'Некорректное тело запроса. Отсутствует одно из полей: ' . $this->fieldsToString());
    }

    private function findCardByID($cardID = false)
    // найти и вернуть карточку по id, если нет, то в ответе false
    {
        return $this->db->runSQLFile('cardByID', array(':id' => $cardID));
        // если нет карточки с таким id, то вернется false
    }

    private function findCardByProduct($product)
    {
        // вернуть карточку по продукту, лучше по артикулу
        // если нет карточки с таким продуктом, то возвращает false

        return $this->db->runSQLFile('cardByProduct', array(':product' => $product));

    }

    function getCards() // вернуть все карточки
    {
        echo $this->db->runSQLFile('cards');
    }

    function getCard() // вернуть карточку по id
    {
        //проверяем передан ли id 
        if (isset($_GET['id'])) {

            $cardID = $_GET['id'];

            $card = $this->findCardByID($cardID);
            // если нет карточки с таким id, то вернется false

            if (!$card) {
                $this->errHandler->setError(404, '', 'Нет карточки с таким ID');
                die();
            }
            echo $card;
        } else {
            $this->errHandler->setError(400, '', 'Некорректный ID');
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
        $product = $data['product'];
        //если нет такой карты, то добавляем, иначе возвращаем имеющуюся
        // проверяем по полю product - это не очень хорошо

        if ($this->findCardByProduct($product)) {
            $this->errHandler->setError(409, '', 'Продукт с таким именем уже существует!');
        } else {
            $this->db->runSQLFile('addCard', $params);
        }

        echo $this->findCardByProduct($product);
    }

    function patchCard() // изменить карточку
    {
        //проверяем передан ли id 
        if (isset($_GET['id'])) {

            $cardID = $_GET['id'];

            //формируем массив параметров запроса
            $params = $this->generateParams();

            $oldCard = $this->findCardByID($cardID); //проверяем, есть ли карточка с таким id

            if ($oldCard) {

                $this->db->runSQLFile('patchCard', array(':cardId' => $cardID, ...$params));

                echo json_encode(array('data' => $this->findCardByID($cardID)));
                die();
            } else {
                $this->errHandler->setError(404, '', 'Не найдена карточка с таким ID ' . $cardID);
                die();
            }
        } else {
            echo json_encode(array('data' => '$this->findCardByID($cardID)'));
            // $this->errHandler->setError(400, '', 'Некорректный ID');
            // die();
        }
    }

    function deleteCard($cardID = false) // удалить карточку
    {
        //проверяем передан ли id 
        if ($cardID || isset($_GET['id'])) {

            $cardID = $cardID ? $cardID : $_GET['id'];

            $card = $this->findCardByID($cardID);

            $this->db->runSQLFile('deleteCard', array(':id' => $cardID));

            echo $card;

        } else {
            $this->errHandler->setError(400, '', 'Некорректный ID');
            die();
        }
    }

    function getProduct() // найти продукт
    {
        if (isset($_GET['product'])) {
            $products = $this->db->runSQLFile('product', array(':product' => $_GET['product']));
            echo json_encode(!!$products);
        }
    }
}
?>