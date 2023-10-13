<?php
function getPartners($connect)
{
    $partners = getElements($connect, 'sql/partners.sql');

    echo ($partners);
}

function setPartners($connect)
{
    setElement($connect, function ($connect, $data) {
        $title = $data['title'];
        $text = $data['text'];
        $link = $data['link'];
        mysqli_query($connect, "INSERT INTO `partners` (`id`, `title`, `text`, `link`) VALUES (NULL, '$title', '$text', '$link');");
    });
}

?>