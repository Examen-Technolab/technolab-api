<?php
function getElements($connect, $sql_name)
{
    $sql_file = "sql/$sql_name.sql";

    $query = file_get_contents($sql_file);

    $elements = $connect->query($query);

    $elementList = array();


    while ($element = $elements->fetch(PDO::FETCH_ASSOC)) {
        $elementList[] = $element;
    }

    //return json_encode($elementList);
    echo json_encode($elementList);
}

?>