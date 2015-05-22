var map;
function initialize() {
    var mapOptions = {
        zoom: 16,
        mapTypeId:google.maps.MapTypeId.ROADMAP
        //center: new google.maps.LatLng(-34.397, 150.644)
    };
    map = new google.maps.Map(document.getElementById('map'),
        mapOptions);

    var geoOptions = {
        timeout: 10 * 100
    }
    if(navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var pos = new google.maps.LatLng(position.coords.latitude,
                position.coords.longitude);

            var youMarker = new google.maps.Marker({
                position: pos,
                animation: google.maps.Animation.BOUNCE
            })

            var youInfowindow = new google.maps.InfoWindow({
                content: 'Ti si tu'
            });

            youMarker.setMap(map);
            youInfowindow.open(map, youMarker);









            map.setCenter(pos);
        }, function() {
            handleNoGeolocation(true);
        }, geoOptions);
    } else {
        // Browser doesn't support Geolocation
        handleNoGeolocation(false);
    }
}

function handleNoGeolocation(errorFlag) {
    if (errorFlag) {
        var content = 'Error: The Geolocation service failed.';
    } else {
        var content = 'Error: Your browser doesn\'t support geolocation.';
    }

    var options = {
        map: map,
        position: new google.maps.LatLng(60, 105),
        content: content
    };

    var infowindow = new google.maps.InfoWindow(options);
    map.setCenter(options.position);
}

google.maps.event.addDomListener(window, 'load', initialize);

/*
document.addEventListener("DOMContentLoaded", function(event) {

});
*/