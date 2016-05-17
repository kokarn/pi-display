<?php
    $data = array();
    
    header( 'Access-Control-Allow-Origin: *' );
    header( 'Content-Type: application/json' );
    die( json_encode( $data ) );
