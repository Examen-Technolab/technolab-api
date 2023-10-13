<?php

require './controllers/cards.php';

function cardsRouter($connect, $method, $params)
{

    switch ($method) {
        case 'GET':
            if (count($params) > 1) {
                $id = $params[1];
                $params = array(':card_id' => $id, ':tab_id' => 1);
                $sql_file = "sql/card.sql";

                $query = file_get_contents($sql_file);

                $stmt = $connect->prepare($query);
                echo $stmt->execute($params);


            } else
                getCards($connect);
            break;
        // case 'POST':
        //     $JSONdata = file_get_contents('php://input');
        //     $data = json_decode($JSONdata, true);
        //     $title = $data['title'];
        //     $text = $data['text'];
        //     $link = $data['link'];
        //     mysqli_query($connect, "INSERT INTO `downloads` (`id`, `title`, `text`, `link`) VALUES (NULL, '$title', '$text', '$link');");

        //     http_response_code(201);

        //     echo print_r($JSONdata);
        //     break;
        // case 'DELETE':
        //     $id = $params[1];
        //     mysqli_query($connect, "DELETE FROM `downloads` WHERE `downloads`.`id` = '$id'");

        //     http_response_code(200);

        //      echo print_r('sucsess');
        //     break;
    }

}

?>