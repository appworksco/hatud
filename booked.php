<?php require('./layouts/header.php') ?>

<!-- Button trigger modal -->
<button id="autoOpenModalButton" style="display: none;" type="button" data-bs-toggle="modal" data-bs-target="#popupModal"></button>

<!-- Modal -->
<div class="modal fade" id="popupModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <h1>Awesome!</h1>Your booking is confirmed. You can view the status in the bookings tab.
            </div>
            <div class="modal-footer">
                <a href="location.php" class="btn btn-custom w-100 ms-1">Okay</a>
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