<?php
include( '../config.php' );
$teamId = 422;
$data = file_get_contents( 'https://api.crowdscores.com/api/v1/matches?team_id='  . $teamId . '&api_key=' . $fastestScoresApi );

$realData = json_decode( $data );

$items = array();

foreach( $realData as $match ):
    if( $match->homeTeam->dbid === $teamId ):
        $opponent = $match->awayTeam->name;
    else :
        $opponent = $match->homeTeam->name;
    endif;

    $items[ date( 'Y-m-d', $match->start / 1000 ) ] = array(
        array(
            'image' => 'euro2016/sweden.png',
            'time' => date( 'H:i', $match->start / 1000 ),
            'title' => $opponent
        )
    );
endforeach;

header( 'Access-Control-Allow-Origin: *' );
header( 'Content-Type: application/json' );
echo json_encode( $items );
?>
