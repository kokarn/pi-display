<?php

$from = '9021014006480000';
$to = array(
    '9021014005355000',
    '9021014003395000'
);

function getAccessToken( $auth ){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.vasttrafik.se:443/token' );
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials" );
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/x-www-form-urlencoded',
        'Authorization: Basic ' . $auth
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $tmp = curl_exec($ch);
    $result = json_decode( $tmp, TRUE );
    curl_close($ch);
    return $result[ 'access_token' ];
}

function doRequest( $accessToken, $url ){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url );
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer ' . $accessToken
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $tmp = curl_exec($ch);
    $result = json_decode( $tmp, TRUE );
    curl_close($ch);

    return $result;
}

function getTrips( $accessToken, $from, $to ){
    $url = 'https://api.vasttrafik.se/bin/rest.exe/v2/departureBoard?id=' . $from . '&date=' . date( 'o-m-d' ) . '&time=' . date( 'H:i' ) . '&timeSpan=60&needJourneyDetail=0&direction=' . $to . '&format=json';
    $return = array();

    $data = doRequest( $accessToken, $url );

    if( $data[ 'DepartureBoard' ][ 'Departure' ] ):
        $return = $data[ 'DepartureBoard' ][ 'Departure' ];
    endif;

    return $return;
}

$accessToken = getAccessToken( $vasttrafikAuthToken );

$trips = array();
for( $i = 0; $i < count( $to ); $i = $i + 1 ):
    $trips = array_merge( $trips, getTrips( $accessToken, $from, $to[ $i ] ) );
endfor;

$countdowns = array();
foreach( $trips as $trip ):
    if( !isset( $countdowns[ $trip[ 'sname' ] ] ) ):
        $countdowns[ $trip[ 'sname' ] ] = array();
    endif;

    //2008-08-07 18:11:31
    $countdowns[ $trip[ 'sname' ] ][] = ceil( ( strtotime( $trip[ 'rtDate' ] . ' ' . $trip[ 'rtTime' ] ) - time() ) / 60 );
endforeach;

header( 'Access-Control-Allow-Origin: *' );
header( 'Content-Type: application/json' );
echo json_encode( $countdowns );
