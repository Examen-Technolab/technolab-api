<?php
function setElement($connect, $handler)
{
    $JSONdata = file_get_contents('php://input');
    $data = json_decode($JSONdata, true);

    $handler($connect, $data);

    http_response_code(201);

    echo print_r($JSONdata);
}

?>