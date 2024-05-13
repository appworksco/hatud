<?php

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
    .map {
        width: 100%;
        height: calc(100vh - 176px);
        /* Adjust the height as needed */
    }
    .overlay {
        position: fixed;
        z-index: 1;
        top: 0;
        right: 0;
        margin-top: -20px;
    }
</style>

<?php include("layouts/sidebar.php") ?>
<div class="container py-4">
    <div class="row">
        <div class="col-sm-3 col-md-12"></div>
        <div class="col-sm-6 col-md-12">
            <img src="./assets/images/white-bg.jpg" class="overlay">
            <div class="pt-5" style="margin-top: -8px;">
                <?php
                $fetchRowBookingByUserId = $bookingFacade->fetchRowBookingByUserId($userId);
                if ($fetchRowBookingByUserId < 1) {
                ?>
                    <h4>No booking/s yet!</h4>
                <?php } else { ?>
                    <?php
                    $fetchRowBookingByUserId = $bookingFacade->fetchBookingByUserId($userId);
                    while ($row = $fetchRowBookingByUserId->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                        <div class="card mb-2">
                            <div class="card-body">
                                <p class="m-0">
                                    Booking ID: <?= $row["id"] ?> <br>
                                    Route: <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#mapModal<?= $row["id"] ?>">View Route</a> <br>
                                    Status: <?= $row["status"] ?> <br>
                                    Rider: <?php if ($row["rider_id"] == 0) { ?> No rider yet! <?php } else { ?> <a href="#" class="text-decoration-none">View Rider</a> <?php } ?>
                                </p>
                                <h4 class="my-3">Fare: <?= $row["fare"] ?></h4>

                                <!-- Route Modal -->
                                <div class="modal fade" id="mapModal<?= $row["id"] ?>" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <div class="map" id="map<?= $row["id"] ?>"></div>
                                                <input type="hidden" id="locationLatitude<?= $row["id"] ?>" value="<?= $row["location_latitude"] ?>">
                                                <input type="hidden" id="locationLongitude<?= $row["id"] ?>" value="<?= $row["location_longitude"] ?>">
                                                <input type="hidden" id="destinationLatitude<?= $row["id"] ?>" value="<?= $row["destination_latitude"] ?>">
                                                <input type="hidden" id="destinationLongitude<?= $row["id"] ?>" value="<?= $row["destination_longitude"] ?>">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-custom w-100" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($row["status"] != 'Pending') { ?>

                                <?php } else { ?>
                                    <a href="#" class="btn btn-custom btn-sm w-100 mt-3">Cancel</a>
                                <?php } ?>
                            </div>
                        </div>
                <?php }
                } ?>
            </div>
        </div>
        <div class="col-sm-3 col-md-12"></div>
    </div>
</div>

<?php require('./layouts/footer.php') ?>

<script>
    var maps = [];
    var directionsServices = [];
    var directionsRenderers = [];

    function initMap() {
        <?php
        $fetchRowBookingByUserId = $bookingFacade->fetchBookingByUserId($userId);
        while ($row = $fetchRowBookingByUserId->fetch(PDO::FETCH_ASSOC)) {
        ?>
            var mapId = <?= $row["id"] ?>;
            var locationLatitude = parseFloat(document.getElementById('locationLatitude<?= $row["id"] ?>').value);
            var locationLongitude = parseFloat(document.getElementById('locationLongitude<?= $row["id"] ?>').value);
            var destinationLatitude = parseFloat(document.getElementById('destinationLatitude<?= $row["id"] ?>').value);
            var destinationLongitude = parseFloat(document.getElementById('destinationLongitude<?= $row["id"] ?>').value);

            var mapElement = document.getElementById('map<?= $row["id"] ?>');
            var map = new google.maps.Map(mapElement, {
                center: {
                    lat: (locationLatitude + destinationLatitude) / 2,
                    lng: (locationLongitude + destinationLongitude) / 2
                },
                zoom: 10,
                disableDefaultUI: true,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });

            var directionsService = new google.maps.DirectionsService();
            var directionsRenderer = new google.maps.DirectionsRenderer();
            directionsRenderer.setMap(map);

            maps[mapId] = map;
            directionsServices[mapId] = directionsService;
            directionsRenderers[mapId] = directionsRenderer;

            calculateAndDisplayRoute(locationLatitude, locationLongitude, destinationLatitude, destinationLongitude, mapId);
        <?php } ?>
    }

    function calculateAndDisplayRoute(startLat, startLng, destLat, destLng, mapId) {
        var start = new google.maps.LatLng(startLat, startLng);
        var end = new google.maps.LatLng(destLat, destLng);
        var request = {
            origin: start,
            destination: end,
            travelMode: 'DRIVING'
        };
        directionsServices[mapId].route(request, function(result, status) {
            if (status == 'OK') {
                directionsRenderers[mapId].setDirections(result);
            }
        });
    }
</script>


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCcX-e3_IzAQX0oFYEblWVOh6izY8m6rk4&libraries=places&callback=initMap&loading=async" async defer></script>