<?php
include( '../config.php' );

$from = '9021014006480000';
$to = array(
    '9021014003395000',
    '9021014007320000'
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
    $url = 'https://api.vasttrafik.se/bin/rest.exe/v2/trip?originId=' . $from . '&needJourneyDetail=0&destId=' . $to . '&format=json';
    $return = array();

    $data = doRequest( $accessToken, $url );

    if( isset( $data[ 'TripList' ][ 'Trip' ] ) ):
        $return = $data[ 'TripList' ][ 'Trip' ];
    endif;

    return $return;
}

$accessToken = getAccessToken( $vasttrafikAuthToken );

$destinations = array();
for( $i = 0; $i < count( $to ); $i = $i + 1 ):
    $destinations[ $to[ $i ] ] = getTrips( $accessToken, $from, $to[ $i ] );
endfor;

$countdowns = array();
foreach( $destinations as $destination ):
    foreach( $destination as $trip ):
        if( isset( $trip[ 'Leg' ][ 'name' ] ) ):
            $trip[ 'Leg' ] = array(
                $trip[ 'Leg' ]
            );
        endif;

        $stops = array();

        foreach( $trip[ 'Leg' ] as $leg ):
            if( $leg[ 'type' ] === 'WALK' ):
                continue;
            endif;

            $stops[] = array(
                'name' => $leg[ 'sname' ],
                'destination' => str_replace( ', Göteborg', '', $leg[ 'Destination' ][ 'name' ] )
            );
        endforeach;


        $identifierParts = array();
        foreach( $stops as $stop ):
            $identifierParts[] = $stop[ 'name' ];
        endforeach;

        $identifier = implode( $identifierParts, '-' );

        if( !isset( $trip[ 'Leg' ][ 0 ][ 'Origin' ][ 'rtDate' ] ) ) :
            continue;
        endif;

        $ttl = ceil( ( strtotime( $trip[ 'Leg' ][ 0 ][ 'Origin' ][ 'rtDate' ] . ' ' . $trip[ 'Leg' ][ 0 ][ 'Origin' ][ 'rtTime' ] ) - time() ) / 60 );

        if( $ttl > 0 && $ttl < 60 ):
            if( !isset( $countdowns[ $identifier ] ) ):
                $lastLeg = end( $trip[ 'Leg' ] );
                $countdowns[ $identifier ] = array(
                    'ttl' => array(),
                    'destination' =>  str_replace( ', Göteborg', '', $lastLeg[ 'Destination' ][ 'name' ] ),
                    'route' => $stops
                );
            endif;

            $countdowns[ $identifier ][ 'ttl' ][] = $ttl;
        endif;
    endforeach;
endforeach;

ksort( $countdowns );

header( 'Access-Control-Allow-Origin: *' );
header( 'Content-Type: application/json' );
echo json_encode( $countdowns );
