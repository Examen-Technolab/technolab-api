<?php

require './functions/getElements.php';
require './functions/setElement.php';

require 'cards.php';

function useRouter($connect, $params, $method)
{

    switch ($params[0]) {
        case 'cards':
            cardsRouter($connect, $method, $params);
            break;
        default:
            if ($method == 'GET') {
                getElements($connect, $params[0]);
            }
            break;
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