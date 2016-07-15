<?php
    $data = json_decode( file_get_contents( 'index.json' ) );

    header( 'Access-Control-Allow-Origin: *' );
    header( 'Content-Type: application/json' );
    die( json_encode( $data ) );
