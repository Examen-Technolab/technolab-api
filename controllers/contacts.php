<?php
function getContacts($connect)
{
    $contacts = getElements($connect, 'sql/contacts.sql');

    echo ($contacts);
}

function setContacts($connect)
{
    setElement($connect, function ($connect, $data) {
        $type = $data['type'];
        $title = $data['title'];
        $link = $data['link'];
        $linkTitle = $data['linkTitle'];
        mysqli_query($connect, "INSERT INTO `contacts` (`id`, `type`,  `title`, `link`, `linkTitle`) VALUES (NULL, '$type', '$title', '$link', '$linkTitle');");
    });
}

?>