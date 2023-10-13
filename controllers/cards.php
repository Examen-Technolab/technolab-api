<?php
function getCards($connect)
{
    $cards = getElements($connect, 'cards');
}

function getCardInfo($connect, $sql_name, $sql_params)
{
    $query = file_get_contents("sql/$sql_name.sql");

    $stmt = $connect->prepare($query);


    $stmt->execute($sql_params);

    $elementList = array();


    while ($element = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $elementList[] = $element;
    }

    echo json_encode($elementList);
}

?>