<?php

function manualsRouter($connect, $method, $params)
{

    switch ($method) {
        case 'GET':
            $manuals = getElements($connect, 'sql/manuals.sql');

            echo ($manuals);
            break;
        case 'POST':
            setElement($connect, function ($connect, $data) {
                $title = $data['title'];
                $subtitle = $data['subtitle'];
                $text = $data['text'];
                $link = $data['link'];
                $img = $data['img'];
                $type = $data['type'];
                mysqli_query($connect, "INSERT INTO `manuals` (`id`, `title`, `subtitle`,`text`, `link`, `img`, `type_id`) VALUES (NULL, '$title', '$subtitle', '$text', '$link', '$img', '$type');");
            });
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