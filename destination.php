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
if (isset($_GET["location_latitude"])) {
    $locationLatitude = $_GET["location_latitude"];
}
if (isset($_GET["location_longitude"])) {
    $locationLongitude = $_GET["location_longitude"];
}

?>

<style>
    #map {
        width: 100%;
        height: calc(100vh - 235px);
    }
</style>

<?php include("layouts/sidebar.php") ?>
<div class="container">
    <div class="row">
        <div class="col-sm-3 col-md-12"></div>
        <div class="col-sm-6 col-md-12 p-0">
            <div style="height: calc(100vh - 235px); overflow: hidden">
                <form action="" method="post">
                    <div id="map"></div>
            </div>
            <!-- Hidden values -->
            <input type="text" id="locationLatitude" name="location_latitude" value="<?= $locationLatitude ?>">
            <input type="text" id="locationLongitude" name="location_longitude" value="<?= $locationLongitude ?>">
            <input type="text" id="destinationLatitude" name="destination_latitude">
            <input type="text" id="destinationLongitude" name="destination_longitude">
            <div class="bg-light p-3">
                <div class="d-flex justify-content-between">
                    <p>Fare: <span id="fare">0.00</span></p>
                    <p>Arrival: <span id="arrival">0 Minute/s</span></p>
                </div>
                <input type="text" class="form-control my-1" id="autocomplete" placeholder="Where are you going?">
                <button type="submit" class="btn btn-custom btn-lg w-100 mt-2" id="bookNow">Book Now</button>
            </div>
            </form>
        </div>
        <div class="col-sm-3 col-md-12"></div>
    </div>
</div>

<?php require('./layouts/footer.php') ?>

<script>
    var marker; // Define marker variable in a wider scope
    var map;

    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: {
                lat: 7.8554,
                lng: 125.0565
            },
            zoom: 10,
            disableDefaultUI: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        // Initialize the search functionality
        var input = document.getElementById('autocomplete');
        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo('bounds', map);

        // Get the search input, button, and hidden input fields for latitude and longitude
        var searchInput = document.getElementById('autocomplete');
        var searchButton = document.getElementById('bookNow');
        var locationLatitudeInput = document.getElementById('locationLatitude');
        var locationLongitudeInput = document.getElementById('locationLongitude');
        var destinationLatitudeInput = document.getElementById('destinationLatitude');
        var destinationLongitudeInput = document.getElementById('destinationLongitude');

        // Event listener for when the search input changes
        searchInput.addEventListener('input', function() {
            // Disable the button if the input field is empty
            if (searchInput.value.trim() === '') {
                searchButton.disabled = true;
            } else {
                searchButton.disabled = false;
            }
        });

        // Event listener for when a place is selected
        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();
            if (!place.geometry) {
                window.alert("No details available for input: '" + place.name + "'");
                return;
            }

            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);
            }

            // Move the marker to the searched place
            if (!marker) {
                marker = new google.maps.Marker({
                    map: map,
                    draggable: true,
                    animation: google.maps.Animation.DROP,
                    position: place.geometry.location
                });

                // Event listener for marker dragend
                marker.addListener('dragend', function() {
                    // Update the latitude and longitude hidden inputs
                    destinationLatitudeInput.value = marker.getPosition().lat();
                    destinationLongitudeInput.value = marker.getPosition().lng();

                    // Calculate fare and estimated arrival time
                    calculateFareAndArrivalTime(locationLatitudeInput.value, locationLongitudeInput.value, destinationLatitudeInput.value, destinationLongitudeInput.value);
                });
            } else {
                marker.setPosition(place.geometry.location);
            }

            // Update the latitude and longitude hidden inputs for the initial position
            destinationLatitudeInput.value = marker.getPosition().lat();
            destinationLongitudeInput.value = marker.getPosition().lng();

            // Calculate fare and estimated arrival time
            calculateFareAndArrivalTime(locationLatitudeInput.value, locationLongitudeInput.value, destinationLatitudeInput.value, destinationLongitudeInput.value);
        });

        // Add a draggable marker for the initial position
        marker = new google.maps.Marker({
            map: map,
            draggable: true,
            animation: google.maps.Animation.DROP,
            position: map.getCenter()
        });

        // Event listener for marker dragend
        marker.addListener('dragend', function() {
            // Update the latitude and longitude hidden inputs
            destinationLatitudeInput.value = marker.getPosition().lat();
            destinationLongitudeInput.value = marker.getPosition().lng();

            // Calculate fare and estimated arrival time
            calculateFareAndArrivalTime(locationLatitudeInput.value, locationLongitudeInput.value, destinationLatitudeInput.value, destinationLongitudeInput.value);
        });

        // Initially disable the button since the input is empty
        searchButton.disabled = true;
    }

    function calculateFareAndArrivalTime(startLat, startLng, destLat, destLng) {
        // Parse the latitude and longitude values to ensure they are numbers
        startLat = parseFloat(startLat);
        startLng = parseFloat(startLng);
        destLat = parseFloat(destLat);
        destLng = parseFloat(destLng);

        var service = new google.maps.DistanceMatrixService();
        service.getDistanceMatrix({
            origins: [{
                lat: startLat,
                lng: startLng
            }],
            destinations: [{
                lat: destLat,
                lng: destLng
            }],
            travelMode: 'DRIVING'
        }, function(response, status) {
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
                document.getElementById('arrival').innerHTML = arrivalTimeInMinutes + " Minutes";
            } else {
                console.log('Error:', status);
            }
        });
    }


    function calculateFareFromDistance(distance) {
        // Define base fare and fare per kilometer
        var baseFare = 35; // Base fare in PHP for the first 35 kilometers
        var farePerKilometer = 10; // Fare per kilometer in PHP for distance exceeding 2 kilometers

        // Convert distance to kilometers
        var distanceInKilometers = distance / 1000;

        // Calculate fare
        var fare = baseFare;
        if (distanceInKilometers > 5) {
            // Calculate fare for distance exceeding 2 kilometers
            fare += (distanceInKilometers - 5) * farePerKilometer;
        }

        // If fare has a decimal part, round up to the nearest integer
        if (fare % 1 !== 0) {
            fare = Math.ceil(fare);
        }

        return fare;
    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCcX-e3_IzAQX0oFYEblWVOh6izY8m6rk4&libraries=places&callback=initMap" async defer></script>