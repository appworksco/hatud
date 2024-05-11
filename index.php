<?php 

require('./layouts/header.php');

// Welcome message after creating an account
if (isset($_GET["welcome"])) {
    echo "<script>alert('You can now login to your account and start booking for a ride.')</script>";
}

?>

<!-- Button trigger modal -->
<button id="autoOpenModalButton" style="display: none;" type="button" data-bs-toggle="modal" data-bs-target="#popupModal"></button>

<!-- Modal -->
<div class="modal fade" id="popupModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                Don't have an account? If yes you'll be redirected to the sign-up page. Otherwise, if you're already a user you'll be redirected to the sign-in page.
            </div>
            <div class="modal-footer">
                <div class="d-flex w-100">
                    <a href="policy.php" class="btn btn-custom-outline w-100 me-1">Yes</a>
                    <a href="sign-in.php" class="btn btn-custom w-100 ms-1">No</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require('./layouts/footer.php') ?>

<!-- JavaScript to automatically open the modal -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var myModal = new bootstrap.Modal(document.getElementById('popupModal'));
        myModal.show();
    });
</script>