<?php
include( '../config.php' );
include( 'SHL.class.php' );

if( !isset( $shlClientId ) || !isset( $shlClientSecret ) ):
    exit;
endif;

$findTeams = array( 'LIF', 'KHK' );

$shl = new SHL( $shlClientId, $shlClientSecret );

$games = $shl->getGames( date( 'Y' ) );

$items = array();

foreach( $games as $game ):

    if( !in_array( $game[ 'away_team_code' ], $findTeams ) && !in_array( $game[ 'home_team_code' ], $findTeams ) ):
        continue;
    endif;

    foreach( $findTeams as $teamName ):
        if( $game[ 'home_team_code' ] === $teamName ):
            $opponent = $game[ 'away_team_code' ];
        elseif( $game[ 'away_team_code' ] === $teamName ) :
            $opponent = $game[ 'home_team_code' ];
        else:
            continue;
        endif;

        $matchDate = date( 'Y-m-d', strtotime( $game[ 'start_date_time' ] ) );
        if( !isset( $items[ $matchDate ] ) ):
            $items[ $matchDate ] = array();
        endif;

        $items[ $matchDate ][] = array(
            'image' => 'shl/' . strtolower( $teamName ) . '.png',
            'time' => date( 'H:i', strtotime( $game[ 'start_date_time' ] ) ),
            'title' => $opponent
        );

        break;
    endforeach;
endforeach;

header( 'Access-Control-Allow-Origin: *' );
header( 'Content-Type: application/json' );
echo json_encode( $items );
?>
