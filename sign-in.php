<?php

// Include header layout file
require('./layouts/header.php');

if (isset($_POST["sign_in"])) {
    $mobileNumber = $_POST["mobile_number"] ?? '';
    $mobileNumberAsString = (string)$mobileNumber;
    if (!empty($mobileNumberAsString) && $mobileNumberAsString[0] !== '0') {
        $errors['mobile_number'] = "Mobile Number should start with 0.";
    } else {
        if (strlen($mobileNumberAsString) != 11) {
            $errors['mobile_number'] = "Mobile Number should be 11 digits.";
        } else {
            $verifyMobileNumber = $usersFacade->verifyMobileNumber($mobileNumber);
            $signIn = $usersFacade->signIn($mobileNumber);
            if ($verifyMobileNumber > 0) {
                while ($row = $signIn->fetch(PDO::FETCH_ASSOC)) {
                    $_SESSION["user_id"] = $row["id"];
                    $_SESSION["fullname"] = $row["first_name"] . ' ' . $row["middle_name"] . ' ' . $row["last_name"];
                    $_SESSION["mobile_number"] = $row["mobile_number"];
                    header("Location: location.php");
                }
            } else {
                $errors['mobile_number'] = "It appears that no account has been registered with that mobile number yet.";
            }
        }
    }
}

?>

<div class="container py-4">
    <div class="row">
        <div class="col-sm-3 col-md-12"></div>
        <div class="col-sm-6 col-md-12">
            <div style="height: calc(100vh - 150px)">
                <h4>Hello there!</h4>
                <p class="small">Welcome back! Please sign in to access your account and explore our services.</p>
                <form action="sign-in.php" class="pt-3 needs-validation" method="post" novalidate>
                    <div class="mb-3">
                        <input type="text" class="form-control py-3 <?= isset($errors['mobile_number']) ? 'is-invalid' : '' ?>" id="mobileNumber" placeholder="Mobile Number" name="mobile_number" minlength="11" maxlength="11" required>
                        <?php if (isset($errors['mobile_number'])) : ?>
                            <div class="invalid-feedback">
                                <?= $errors['mobile_number'] ?>
                            </div>
                        <?php endif; ?>
                    </div>
            </div>
            <button type="submit" class="btn btn-custom btn-lg w-100" name="sign_in">Sign In</button>
            </form>
        </div>
        <div class="col-sm-3 col-md-12"></div>
    </div>
</div>

<?php require('./layouts/footer.php') ?>