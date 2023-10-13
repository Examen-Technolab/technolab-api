<?php
function getDownloads($connect)
{
    $downloads = getElements($connect, 'sql/downloads.sql');

    echo ($downloads);
}

function setDownload($connect)
{
    setElement($connect, function ($connect, $data) {
        $title = $data['title'];
        $text = $data['text'];
        $link = $data['link'];
        mysqli_query($connect, "INSERT INTO `downloads` (`id`, `title`, `text`, `link`) VALUES (NULL, '$title', '$text', '$link');");
    });
}

?>