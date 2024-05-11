<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Maps Autocomplete</title>
    <style>
        #map {
            height: 400px;
            width: 100%;
            margin-bottom: 20px;
        }
        #autocomplete {
            width: 300px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div id="map"></div>
    <input id="autocomplete" type="text" placeholder="Enter a location">
    
    <script>
        function initMap() {
            var map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: -33.8688, lng: 151.2195},
                zoom: 13
            });

            var input = document.getElementById('autocomplete');
            var autocomplete = new google.maps.places.Autocomplete(input);

            autocomplete.bindTo('bounds', map);

            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();
                if (!place.geometry) {
                    window.alert("No details available for input: '" + place.name + "'");
                    return;
                }

                // If the place has a geometry, then present it on a map.
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);
                }
            });
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCcX-e3_IzAQX0oFYEblWVOh6izY8m6rk4&libraries=places&callback=initMap" async defer></script>
</body>
</html>