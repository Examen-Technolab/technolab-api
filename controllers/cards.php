<?php
function getCards($connect)
{
    $cards = getElements($connect, 'sql/cards.sql');

    echo ($cards);
}

?>