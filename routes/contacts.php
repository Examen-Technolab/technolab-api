<?php

require './controllers/contacts.php';

function contactsRouter($connect, $method, $params)
{

    switch ($method) {
        case 'GET':
            getContacts($connect);
            break;
        case 'POST':
            setContacts($connect);
            break;
        // case 'DELETE':
        //     $id = $params[1];
        //     mysqli_query($connect, "DELETE FROM `downloads` WHERE `downloads`.`id` = '$id'");

        //     http_response_code(200);

        //      echo print_r('sucsess');
        //     break;
    }

}

?>