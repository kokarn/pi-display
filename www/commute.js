(function( $ ){
    var trips;

    function printTrips( trips ){
        var $wrapper = $( '.js-commute' );

        $wrapper.empty();

        for( var name in trips ){
            if( trips.hasOwnProperty( name ) ){
                var $route = $( '<div><span class="trip-name">' + name + '</span></div>' );

                for( var i = 0; i < trips[ name ].length; i = i + 1 ){
                    $route.append( '<span class="trip-time">' + trips[ name ][ i ] + '</span>' );
                }

                $wrapper.append( $route );
            }
        }
    }

    function getTrips(){
        var xhr = $.ajax({
            url: 'commute/'
        });

        xhr.done( function( response ){
            printTrips( response );
        });
    }

    $(function(){
        getTrips();

        setInterval( getTrips, 60000 );
    });
})( $ );
