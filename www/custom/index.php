<?php
$data = json_decode( @file_get_contents( 'index.json' ) );

if( !$data ){
    $data = array();
}

header( 'Access-Control-Allow-Origin: *' );
header( 'Content-Type: application/json' );
die( json_encode( $data ) );
