<?php

// Include header layout file
require('./layouts/header.php');

if (isset($_SESSION["user_id"])) {
    $userId = $_SESSION["user_id"];
}
if (isset($_SESSION["fullname"])) {
    $fullname = $_SESSION["fullname"];
}
if (isset($_SESSION["mobile_number"])) {
    $mobileNumber = $_SESSION["mobile_number"];
}

?>

<style>
    #map {
        width: 100%;
        height: calc(100vh - 200px);
    }
</style>

<?php include("layouts/sidebar.php") ?>
<div class="container">
    <div class="row">
        <div class="col-sm-3 col-md-12"></div>
        <div class="col-sm-6 col-md-12 p-0">
            <div style="height: calc(100vh - 200px); overflow: hidden">
                <form action="" method="post">
                    <div id="map"></div>
            </div>
            <!-- Hidden values -->
            <input type="hidden" name="user_id" value="<?= $userId ?>">
            <input type="hidden" name="fullname" value="<?= $fullname ?>">
            <input type="hidden" name="mobile_number" value="<?= $mobileNumber ?>">
            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">
            <div class="bg-light p-3">
                <div class="d-flex justify-content-between">
                    <p>Fare: <span id="fare">0.00</span></p>
                    <p>Arrival: <span id="arrival">0 Minute/s</span></p>
                </div>
                <input type="text" class="form-control my-1" id="autocomplete" placeholder="Where do you want to go?">
                <a href="add-mobile-number.php" class="btn btn-custom btn-lg w-100 mt-2">Book Now</a>
            </div>
            </form>
        </div>
        <div class="col-sm-3 col-md-12"></div>
    </div>
</div>


<?php require('./layouts/footer.php') ?>

<script>
    var marker; // Define marker variable in a wider scope

    function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
            center: {
                lat: 7.790099,
                lng: 124.972710
            },
            zoom: 16,
            disableDefaultUI: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP
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

            // Move the marker to the searched place
            marker.setPosition(place.geometry.location);

            // Update the latitude and longitude hidden inputs
            document.getElementById('latitude').value = place.geometry.location.lat();
            document.getElementById('longitude').value = place.geometry.location.lng();

            // Calculate fare and arrival time based on distance
            calculateFareAndArrivalTime(map.getCenter(), place.geometry.location);
        });

        // Get the current location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };

                // Center the map on the user's location
                map.setCenter(pos);

                // Add a marker at the user's location
                marker = new google.maps.Marker({
                    position: pos,
                    map: map,
                    draggable: true,
                    title: 'Your Location'
                });

                // Event listener for marker dragend
                marker.addListener('dragend', function() {
                    // Update the latitude and longitude hidden inputs
                    document.getElementById('latitude').value = marker.getPosition().lat();
                    document.getElementById('longitude').value = marker.getPosition().lng();

                    // Calculate fare and arrival time based on new marker position
                    calculateFareAndArrivalTime(marker.getPosition(), document.getElementById('autocomplete').value);
                });
            }, function() {
                // Handle geolocation errors
                handleLocationError(true, map.getCenter());
            });
        } else {
            // Browser doesn't support Geolocation
            handleLocationError(false, map.getCenter());
        }

    }

    function calculateFareAndArrivalTime(origin, destination) {
        var service = new google.maps.DistanceMatrixService();
        service.getDistanceMatrix({
                origins: [origin],
                destinations: [destination],
                travelMode: 'DRIVING'
            },
            function(response, status) {
                if (status == 'OK') {
                    var distance = response.rows[0].elements[0].distance.value; // Distance in meters
                    var duration = response.rows[0].elements[0].duration.value; // Duration in seconds

                    // Calculate fare
                    var fare = calculateFareFromDistance(distance);

                    // Convert duration to minutes and round up
                    var arrivalTimeInMinutes = Math.ceil(duration / 60);

                    // Format fare
                    var formattedFare = fare.toLocaleString('en-PH', {
                        style: 'currency',
                        currency: 'PHP'
                    });

                    // Update fare display
                    document.getElementById('fare').innerHTML = formattedFare;

                    // Update arrival time display
                    document.getElementById('arrival').innerHTML = arrivalTimeInMinutes + " Minute/s";
                } else {
                    console.log('Error:', status);
                }
            }
        );
    }

    function calculateFareFromDistance(distance) {
        // Example fare calculation based on distance
        // You can adjust the formula based on your fare calculation logic
        var baseFare = 35; // Base fare
        var farePerKm = 10; // Fare per kilometer
        var distanceInKm = distance / 1000; // Convert distance to kilometers
        var fare = baseFare + (farePerKm * distanceInKm);

        // If fare has a decimal part, round up to the nearest integer
        if (fare % 1 !== 0) {
            fare = Math.ceil(fare);
        }

        return fare;
    }

    function handleLocationError(browserHasGeolocation, pos) {
        var infoWindow = new google.maps.InfoWindow();

        infoWindow.setPosition(pos);
        infoWindow.setContent(browserHasGeolocation ?
            'Error: The Geolocation service failed.' :
            'Error: Your browser doesn\'t support geolocation.');
        infoWindow.open(map);
    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCcX-e3_IzAQX0oFYEblWVOh6izY8m6rk4&libraries=places&callback=initMap" async defer></script>