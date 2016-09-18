<?php
$page = 'https://www.wch2016.com/sweden/scores-schedule/';

$data = file_get_contents( $page );
preg_match_all( '#<div class="section-subheader">(.+?)</table>#mis', $data, $matchDays );

$swedishGames = array();

foreach( $matchDays[ 1 ] as $matchDay ):
    $match = array();
    $date = substr( $matchDay, strpos( $matchDay, ',' ) + 1, strpos( $matchDay, '<' ) - strpos( $matchDay, ',' ) - 1 );
    preg_match( '#<div class="wide-time-result">(.+?)</div>#mis', $matchDay, $time );

    if( strpos( $time[ 1 ], 'SWE' ) !== false ):
        continue;
    endif;

    preg_match( '#<div class="wide-matchup">(.+?)</div>#mis', $matchDay, $teams );
    preg_match_all( '#<label>(.+?)</label>#mis', $teams[ 1 ], $teams );

    if( $teams[ 1 ][ 0 ] == 'Team Sweden' ):
        $match[ 'opponent' ] = $teams[ 1 ][ 1 ];
    else :
        $match[ 'opponent' ] = $teams[ 1 ][ 0 ];
    endif;

    preg_match( '#<span class="matchup-time-or-result">(.+?)</span>#mis', $time[ 1 ], $time );

    //2008-07-01T22:35:17.03+08:00
    $date = strtotime( trim( $date ) . ' 2016' );
    $timestamp = strtotime( trim( str_replace( ' ET', '', $time[ 1 ] ) ) );
    $fullTimestamp = date( 'o-m-d', $date ) . 'T' . date( 'H:i', $timestamp ) . ':00.00-04:00';
    $match[ 'date' ] = strtotime( $fullTimestamp );

    $swedishGames[] = $match;
endforeach;

$items = array();

foreach( $swedishGames as $swedishGame ):
    $date = date( 'Y-m-d', $swedishGame[ 'date' ] );
    $items[ $date ] = array();

    $items[ $date ][] = array(
        'title' => $swedishGame[ 'opponent' ],
        'image' => 'wch/sweden.jpg',
        'time' => date( 'H:i', $swedishGame[ 'date' ] )
    );
endforeach;

header( 'Access-Control-Allow-Origin: *' );
header( 'Content-Type: application/json' );
echo json_encode( $items );
