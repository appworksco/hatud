<?php

// Include header layout file
require('./layouts/header.php');

if (isset($_GET["welcome"])) {
    echo "<script>alert('You can now start booking for a ride.')</script>";
}

?>

<?php include("layouts/sidebar.php") ?>


<?php require('./layouts/footer.php') ?>