<?php

// Include header layout file
require('./layouts/header.php');

if (isset($_GET["mobile_number"])) {
    $mobileNumber = $_GET["mobile_number"];
}

if (isset($_POST["submit"])) {
    $mobileNumber = $_POST["mobile_number"];
    $firstName = $_POST["first_name"];
    $middleName = $_POST["middle_name"];
    $lastName = $_POST["last_name"];

    $signUp = $usersFacade->signUp($firstName, $middleName, $lastName, $mobileNumber);
    if ($signUp) {
        header('Location: home.php?welcome');
    }
}

?>

<div class="container py-4">
    <div class="row">
        <div class="col-sm-3 col-md-12"></div>
        <div class="col-sm-6 col-md-12">
            <div style="height: calc(100vh - 150px)">
                <h4>What's your name?</h4>
                <p class="small">Your full name will help your rider identify you.</p>
                <form action="add-personal-details.php" class="pt-3 needs-validation" method="post" novalidate>
                    <div class="mb-2">
                        <input type="text" class="form-control py-3" id="firstName" placeholder="First Name" name="first_name" required>
                        <div class="invalid-feedback">Please enter your first name.</div>
                    </div>
                    <div class="mb-2">
                        <input type="text" class="form-control py-3" placeholder="Middle Name (Optional)" name="middle_name">
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control py-3" id="lastName" placeholder="Last Name" name="last_name" required>
                        <div class="invalid-feedback">Please enter your last name.</div>
                    </div>
            </div>
            <input type="hidden" name="mobile_number" value="<?= $mobileNumber ?>">
            <button type="submit" class="btn btn-success btn-lg w-100" name="submit">Submit</button>
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

                    // Custom validation for first name and last name
                    var firstNameInput = document.getElementById('firstName');
                    var lastNameInput = document.getElementById('lastName');
                    var firstName = firstNameInput.value.trim();
                    var lastName = lastNameInput.value.trim();
                    if (firstName.length === 0 && lastName.length === 0) {
                        firstNameInput.classList.add('is-invalid');
                        LastNameInput.classList.add('is-invalid');
                    } else {
                        firstNameInput.classList.remove('is-invalid');
                        lastNameInput.classList.remove('is-invalid');
                    }

                    form.classList.add('was-validated');
                }, false);
            });
    })();
</script>