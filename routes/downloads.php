<?php

require './controllers/downloads.php';

function downloadsRouter($connect, $method, $params)
{

    switch ($method) {
        case 'GET':
            getDownloads($connect);
            break;
        case 'POST':
            setDownload($connect);
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