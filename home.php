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

<?php include("layouts/sidebar.php") ?>
<div class="container py-4">
    <div class="row">
        <div class="col-sm-3 col-md-12"></div>
        <div class="col-sm-6 col-md-12 p-0">
            <div style="height: calc(100vh - 250px)">
                <form action="" method="post">

            </div>
            <div class="bg-light p-3">
                <input type="text" class="form-control my-1" placeholder="To Destination">
                <a href="add-mobile-number.php" class="btn btn-custom btn-lg w-100 mt-2">Book Now</a>
            </div>

                </form>
        </div>
        <div class="col-sm-3 col-md-12"></div>
    </div>
</div>


<?php require('./layouts/footer.php') ?>