<?php

require './functions/getElements.php';
require './functions/setElement.php';

require 'cards.php';
require 'downloads.php';
require 'contacts.php';
require 'partners.php';
require 'manuals.php';

function useRouter($connect, $params, $method)
{
    if ($method == 'GET') {
        $sql_file = 'sql/' + $params[0] + '.sql';
        getElements($connect, $sql_file);
    }

    // switch ($params[0]) {
    //     case 'cards':
    //         cardsRouter($connect, $method, $params);
    //         break;
    //     case 'downloads':
    //         downloadsRouter($connect, $method, $params);
    //         break;
    //     case 'contacts':
    //         contactsRouter($connect, $method, $params);
    //         break;
    //     case 'partners':
    //         partnersRouter($connect, $method, $params);
    //         break;
    //     case 'manuals':
    //         manualsRouter($connect, $method, $params);
    //         break;
    //     // case 'events':
    //     //     eventsRouter($connect, $method, $params);
    //     //     break;

    // }
}

?>