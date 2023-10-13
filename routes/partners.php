<?php

require './controllers/partners.php';

function partnersRouter($connect, $method, $params)
{

    switch ($method) {
        case 'GET':
            getPartners($connect);
            break;
        case 'POST':
            setPartners($connect);
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