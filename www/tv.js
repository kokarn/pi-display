(function(){
    var printed = 0;
    function loadShows( callback ){
        var xhr = $.ajax({
            url: 'shows.php'
        });

        xhr.done( function( data ){
            callback( data );
        });
    }

    function zeroPad(number, length) {
        var str = String( number );

        while ( str.length < length ) {
            str = '0' + str;
        }

        return str;
    }

    function parseShowName( showName ){
        return String( showName ).trim().toLowerCase().replace( /\s+/g, '-' );
    }

    function getTodayDate(){
        var today = new Date();

        return today.getFullYear() + '-' + zeroPad( today.getMonth() + 1, 2 ) + '-' + zeroPad( today.getDate(), 2 );
    }

    function getDayName( date ){
        var dayDate = new Date( date );

        if( getTodayDate() == date ){
            return 'Today';
        }

        switch( dayDate.getDay() ){
            case 0:
                return 'Sunday';
            case 1:
                return 'Monday';
            case 2:
                return 'Tuesday';
            case 3:
                return 'Wednesday';
            case 4:
                return 'Thursday';
            case 5:
                return 'Friday';
            case 6:
                return 'Saturday';
            default:
                return '???';
        }
    }

    function printDay( day, shows ){
        var dayDate = new Date( day );
        var currentDate = new Date();
        var startOfTodayDate = new Date( currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate() );

        var $outerWrapper = $( '.js-tv' );
        var $titleWrapper = $( '<div class="show-day-title">' + getDayName( day ) + '</div>' )
        var $showElement;

        if( dayDate.getTime() < startOfTodayDate.getTime() ){
            return false;
        }

        if( dayDate.getTime() > startOfTodayDate.getTime() + 7 * 24 * 60 * 60 * 1000 ){
            return false;
        }

        for( var i = 0; i < shows.length && printed < 10; i = i + 1 ){
            $showElement = printShow( $outerWrapper, shows[ i ] );

            if( i === 0 ){
                $titleWrapper.css({
                    top: $showElement.position().top + 10,
                    left: $showElement.position().left
                });

                $outerWrapper.append( $titleWrapper );
            }
        }
    }

    function printShow( $wrapper, show ){
        var parsedShowName = parseShowName( show );
        var $element = $( '<div class="show-wrapper"><img src="images/?query=' + parsedShowName + '"></div>' );

        $wrapper.append( $element );

        printed = printed + 1;

        return $element;
    }

    function printAllShows( shows ){
        printed = 0;
        $( '.js-tv' ).empty();

        for( var day in shows ){
            if( shows.hasOwnProperty( day ) ){
                printDay( day, shows[ day ] );
            }
        }
    }

    $(function(){
        loadShows( printAllShows );

        setInterval( function(){
            loadShows( printAllShows );
        }, 60000 );
    });
})();
