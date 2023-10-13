<?php
function getCards($connect)
{
    $cards = getElements($connect, 'cards');

    echo ($cards);
}

?>