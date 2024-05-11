<?php

// Include header layout file
require('./layouts/header.php');

if (isset($_POST["next"])) {
    $mobileNumber = $_POST["mobile_number"];

    $verifyMobileNumber = $usersFacade->verifyMobileNumber($mobileNumber);
    if ($verifyMobileNumber <= 0) {
        header('Location: add-personal-details.php?mobile_number=' . $mobileNumber);
    } else {
        echo "<script>alert('Mobile Number has already been taken!')</script>";
    }
}

?>

<div class="container py-4">
    <div class="row">
        <div class="col-sm-3 col-md-12"></div>
        <div class="col-sm-6 col-md-12">
            <div style="height: calc(100vh - 150px)">
                <h4>What's your mobile number?</h4>
                <p class="small">We will use you mobile number to contact you about your booking.</p>
                <form action="add-mobile-number.php" class="pt-3 needs-validation" method="post" novalidate>
                    <div class="mb-3">
                        <input type="text" class="form-control py-3" id="mobileNumber" placeholder="Mobile Number" name="mobile_number" minlength="11" maxlength="11" required>
                        <div class="invalid-feedback">Please enter a valid 11-digit mobile number starting with '0'.</div>
                    </div>
            </div>
            <button type="submit" class="btn btn-success btn-lg w-100" name="next">Next <i class="bi bi-arrow-right"></i></button>
            </form>
        </div>
        <div class="col-sm-3 col-md-12"></div>
    </div>
</div>

<?php require('./layouts/footer.php') ?>

<script>
    // JavaScript for form validation
    (function() {
        'use strict';
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms)
            .forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }

                    // Custom validation for mobile number
                    var mobileNumberInput = document.getElementById('mobileNumber');
                    var mobileNumber = mobileNumberInput.value.trim();
                    if (mobileNumber.length === 0 || !mobileNumber.startsWith('0') || mobileNumber.length !== 11) {
                        mobileNumberInput.classList.add('is-invalid');
                    } else {
                        mobileNumberInput.classList.remove('is-invalid');
                    }

                    form.classList.add('was-validated');
                }, false);
            });
    })();
</script>