<?php
include( '../config.php' );

$url = 'http://www.myepisodes.com/rss.php?feed=mylist&uid=mirrorer&pwdmd5=' . $myEpisodes;

$data = file_get_contents( $url );

$shows = new SimpleXMLElement( $data );

$items = array();

foreach( $shows->channel->item as $show ):
    preg_match( '#\[(.+?)\]\[(.+?)\]\[(.+?)\]\[(.+?)\]#mis', $show->title, $matches );
    $dateString = date( 'Y-m-d', strtotime( $matches[ 4 ] ) );

    if( !isset( $items[ $dateString ] ) ):
        $items[ $dateString ] = array();
    endif;

    $item = array();

    $item[ 'image' ] = '/images/?query=' . preg_replace( '#\s+#mis', '-', strtolower( trim( $matches[ 1 ] ) ) );

    $item[ 'name' ] = trim( $matches[ 1 ] );

    $items[ $dateString ][] = $item;
endforeach;

header( 'Access-Control-Allow-Origin: *' );
header( 'Content-Type: application/json' );
echo json_encode( $items );
