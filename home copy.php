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
                <input type="text" class="form-control my-1" id="autocomplete" placeholder="Where are you now?">
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

        // Add a draggable marker for Bukidnon
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
    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCcX-e3_IzAQX0oFYEblWVOh6izY8m6rk4&libraries=places&callback=initMap" async defer></script>