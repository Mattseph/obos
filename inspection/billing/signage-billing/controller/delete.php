<?php

include './../../../../config/constants.php';

if (filter_has_var(INPUT_POST, 'password')) {

    $password = md5($_POST['password']);

    $checkUser = "SELECT * FROM user_view WHERE password = :password";
    $checkStatement = $pdo->prepare($checkUser);
    $checkStatement->bindParam(':password', $password);
    $checkStatement->execute();

    if ($checkStatement->rowCount() === 0) {
        $_SESSION['invalid_password'] = "
        <div class='msgalert alert--danger' id='alert'>
            <div class='alert__message'>
                Incorrect Password, Please try again
            </div>
        </div>

        ";
        //Redirecting to the manage billing page.
        header('location:' . SITEURL . 'inspection/billing/signage-billing/');
        exit;
    }
}

//Get the id to be deleted
if (filter_has_var(INPUT_POST, 'signage_id')) {
    $clean_signage_id = filter_var($_POST['signage_id'], FILTER_SANITIZE_NUMBER_INT);
    $signage_id = filter_var($clean_signage_id, FILTER_VALIDATE_INT);

    $billingQuery = "DELETE FROM signage_billing WHERE signage_id = :signage_id";
    $billingStatement = $pdo->prepare($billingQuery);
    $billingStatement->bindParam(':signage_id', $signage_id);

    if ($billingStatement->execute()) {
        //Creating SESSION variable to display message.
        $_SESSION['delete'] = "
        <div class='msgalert alert--success' id='alert'>
            <div class='alert__message'>
                Signage Billing Record Deleted Successfully
            </div>
        </div>
        ";
        //Redirecting to the manage display page.
        header('location:' . SITEURL . 'inspection/billing/signage-billing/');
    } else {
        //Creating SESSION variable to display message.
        $_SESSION['delete'] = "
        <div class='msgalert alert--danger' id='alert'>
            <div class='alert__message'>
                Failed to Delete Signage Billing Record, Please try again
            </div>
        </div>

        ";
        //Redirecting to the manage billing page.
        header('location:' . SITEURL . 'inspection/billing/signage-billing/');
    }
} else {

    $_SESSION['id_not_found'] = "
        <div class='msgalert alert--danger' id='alert'>
            <div class='alert__message'>
                Billing ID Not Found
            </div>
        </div>

        ";
    //Redirecting to the manage billing page.
    header('location:' . SITEURL . 'inspection/billing/signage-billing/');
}
