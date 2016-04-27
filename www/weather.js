(function(){
    function setWeather(){
        $.simpleWeather({
            location: 'Gothenburg, Sweden',
            woeid: '',
            unit: 'C',
            success: function(weather) {
                html =
                '<div class="current-weather-wrapper">' +
                    '<i class="icon-' + weather.code + '"></i> ' +
                    '<span>' + weather.temp + '&deg;' + weather.units.temp + '</span>' +
                '</div>';

                html = html + '<div class="foreacasts-wrapper">';
                for( var i = 1; i < 6; i = i + 1 ) {
                    html = html + '<div class="foreacast-wrapper">';
                    html = html + '<i class="icon-' + weather.forecast[i].code + '"></i>';
                    html = html + '<div class="forecast-weather-wrapper">' + weather.forecast[i].day + '<br>' + weather.forecast[i].high + '&deg;' + weather.units.temp + '</div>';
                    html = html + '</div>';
                }

                html = html + '</div>';

                $( '.js-weather' ).html(html);
            },
            error: function(error) {
                $( '.js-weather' ).html( '<p>' + error + '</p>' );
            }
        });
    }

    $(function() {
        setWeather();

        setInterval( setWeather, 60000 );
    });
})();
