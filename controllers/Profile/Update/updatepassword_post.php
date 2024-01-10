<?php

if (!authenticated()) {
    header('Location: /login');
    exit();
}

require_once base_path("models/DataSets/UsersDataSet.php");
$usersDataSet = new UsersDataSet();

if ($_POST['newpassword'] != $_POST['confirmpassword']) {
    $errors['NoAccount'] = "Passwords do not match.";
    return view('UpdateProfileForms/updatepassword.phtml', [
        'errors' => $errors,
        'pageTitle' => 'Update Password'
    ]);
}

if ($_SESSION['user']['usertype'] == 1) {
    // ------------------------- Student
    $usersDataSet->updateUserPassword($_SESSION['user']['id'], $_POST['currentpassword'], $_POST['newpassword']);

    // Redirect the user to the editprofile page
    header('location: /success');
    exit();

} elseif($_SESSION['user']['usertype'] == 2) {
    // ------------------------- Employer
    $usersDataSet->updateUserPassword($_SESSION['user']['id'], $_POST['currentpassword'], $_POST['newpassword']);

    // Redirect the user to the editprofile page
    header('location: /success');
    exit();

} elseif($_SESSION['user']['usertype'] == 3) {
    // ------------------------- Careers Staff
    $usersDataSet->updateUserPassword($_SESSION['user']['id'], $_POST['currentpassword'], $_POST['newpassword']);

    // Redirect the user to the editprofile page
    header('location: /success');
    exit();

} elseif ($_SESSION['user']['usertype'] == 4) {
    // ------------------------- Library User
    $usersDataSet->updateUserPassword($_SESSION['user']['id'], $_POST['currentpassword'], $_POST['newpassword']);

    // Redirect the user to the editprofile page
    header('location: /success');
    exit();
}
