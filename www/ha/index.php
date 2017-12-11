<?php
include('./CalFileParser.php');
$cal = new CalFileParser();

function getShortName( $team ) {
    switch ( $team ):
        case 'AIK':
            return 'AIK';
        case 'Almtuna':
        case 'Almtuna IS':
            return 'AIS';
        case 'Björklöven':
            return 'IFB';
        case 'Karlskoga':
        case 'BIK Karlskoga':
            return 'BIK';
        case 'Leksand':
        case 'Leksands IF':
            return 'LIF';
        case 'MODO':
        case 'MODO Hockey':
            return 'MODO';
        case 'IK Oskarshamn':
        case 'Oskarshamn':
            return 'IKO';
        case 'Pantern':
        case 'IK Pantern':
            return 'PAN';
        case 'Södertälje':
        case 'Södertälje SK':
            return 'SSK';
        case 'Timrå':
        case 'Timrå IK':
            return 'TIK';
        case 'Tingsryd':
        case 'Tingsryds AIF':
            return 'TAIF';
        case 'Troja/Ljungby':
        case 'Troja-Ljungby':
        case 'IF Troja-Ljungby':
            return 'TRO';
        case 'HC Vita Hästen':
        case 'Vita Hästen':
            return 'VIT';
        case 'Västerviks IK':
            return 'VIK';
        default:
            echo $team;
    endswitch;
}

$games = $cal->parse( 'https://hockey-mchockeyface.herokuapp.com/calendar?team=LIF' );

foreach( $games as $game ):
    // $currentTime = new DateTime( $game[ 'DTSTART' ]->format( 'Y-m-d H:i' ) );
    // $diff = $currentTime->diff( $game[ 'DTSTART' ] );
    //
    // $game[ 'DTSTART' ]->sub( $diff );
    $game[ 'DTSTART' ]->add( new DateInterval( 'PT2H' ) );
    $matchDate = $game[ 'DTSTART' ]->format( 'Y-m-d' );

    if( !isset( $items[ $matchDate ] ) ):
        $items[ $matchDate ] = array();
    endif;

    $teams = explode( ' - ', $game[ 'SUMMARY' ] );

    if ( getShortName( $teams[ 0 ] ) == 'LIF' ) :
        $title = getShortName( $teams[ 1 ] );
    else :
        $title = getShortName( $teams[ 0 ] );
    endif;

    $items[ $matchDate ][] = array(
        'image' => 'shl/lif.png',
        'time' => $game[ 'DTSTART' ]->format( 'H:i' ),
        'title' => $title
    );
endforeach;

header( 'Access-Control-Allow-Origin: *' );
header( 'Content-Type: application/json' );
echo json_encode( $items );
