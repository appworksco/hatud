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

if (isset($_POST["set_location"])) {
    $locationLatitude = $_POST["location_latitude"];
    $locationLongitude = $_POST["location_longitude"];

    header("Location: destination.php?location_latitude=" . $locationLatitude . "&" . "location_longitude=" . $locationLongitude);
}

?>

<style>
    #map {
        width: 100%;
        height: calc(100vh - 195px);
    }
</style>

<?php include("layouts/sidebar.php") ?>
<div class="container">
    <div class="row">
        <div class="col-sm-3 col-md-12"></div>
        <div class="col-sm-6 col-md-12 p-0">
            <div style="height: calc(100vh - 195px); overflow: hidden">
                <form action="location.php" method="post">
                    <div id="map"></div>
            </div>
            <!-- Hidden values -->
            <input type="hidden" id="latitude" name="location_latitude">
            <input type="hidden" id="longitude" name="location_longitude">
            <div class="bg-light p-3">
                <input type="text" class="form-control my-1" id="autocomplete" placeholder="Where are you now?">
                <button type="submit" class="btn btn-custom btn-lg w-100 mt-2" id="setLocation" name="set_location">Set Location</button>
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

        // Get the search input and the button
        var searchInput = document.getElementById('autocomplete');
        var searchButton = document.getElementById('setLocation');

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
                    document.getElementById('latitude').value = marker.getPosition().lat();
                    document.getElementById('longitude').value = marker.getPosition().lng();
                });
            } else {
                marker.setPosition(place.geometry.location);
            }

            // Update the latitude and longitude hidden inputs for the initial position
            document.getElementById('latitude').value = place.geometry.location.lat();
            document.getElementById('longitude').value = place.geometry.location.lng();
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
            document.getElementById('latitude').value = marker.getPosition().lat();
            document.getElementById('longitude').value = marker.getPosition().lng();
        });

        // Initially disable the button since the input is empty
        searchButton.disabled = true;
    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCcX-e3_IzAQX0oFYEblWVOh6izY8m6rk4&libraries=places&callback=initMap" async defer></script>