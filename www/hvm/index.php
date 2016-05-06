<?php
$page = 'http://d.widgets.iihf.hockey/HYDRA/2016-WM/widget_en_2016_wm_tournament.js';

$data = file_get_contents( $page );
preg_match( '#\[(.+?)\]#mis', $data, $matches );

$matches = str_replace( ',}', '}', $matches[ 0 ] );
$matches = trim( substr( $matches, 0, strlen( $matches ) - 1 ) );
$matches = substr( $matches, 0, strlen( $matches ) - 1 ) . ']';

$matchesArray = json_decode( $matches );

$swedishGames = array();

foreach( $matchesArray as $match ):
    if( $match->h == 'SWE' || $match->g == 'SWE' ):
        $swedishGames[] = $match;
    endif;
endforeach;

$items = array();

foreach( $swedishGames as $swedishGame ):
    $items[ $swedishGame->d ] = array();

    $items[ $swedishGame->d ][] = array(
        'image' => 'hvm/sweden.jpg',
        'time' => date( 'H:i', strtotime( $swedishGame->d . ' ' . $swedishGame->t ) )
    );
endforeach;

header( 'Access-Control-Allow-Origin: *' );
header( 'Content-Type: application/json' );
echo json_encode( $items );
