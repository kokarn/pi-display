(function( $ ){
    var trips;
    var display = {
        from: {
            hour: 7,
            minute: 15
        },
        to: {
            hour: 8,
            minute: 30
        }
    };

    function shouldDisplay(){
        var now = new Date();
        var currentHour = now.getHours();
        var currentMinute = now.getMinutes();

        if( currentHour < display.from.hour ){
            return false;
        }

        if( currentHour > display.to.hour ){
            return false;
        }

        if( currentMinute < display.from.minute ){
            return false;
        }

        if( currentMinute > display.to.minute ){
            return false;
        }

        return true;
    }

    function printTrips( trips ){
        var $wrapper = $( '.js-commute' );
        var tripName;
        var i;
        var $route;

        $wrapper.empty();

        if( !shouldDisplay() ){
            return false;
        }

        for( var identifier in trips ){
            if( trips.hasOwnProperty( identifier ) ){
                tripName = [];
                for( i = 0; i < trips[ identifier ].route.length; i = i + 1 ){
                    tripName.push( trips[ identifier ].route[ i ].name + ' --> ' + trips[ identifier ].route[ i ].destination );
                }

                tripName = tripName.join( ' | ' );

                $route = $( '<div class="route-wrapper"><span class="trip-name">' + tripName + '</span></div>' );

                for( i = 0; i < trips[ identifier ][ 'ttl' ].length; i = i + 1 ){
                    $route.append( '<span class="trip-time">' + trips[ identifier ][ 'ttl' ][ i ] + '</span>' );
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
