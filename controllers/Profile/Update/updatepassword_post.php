<?php

require_once base_path("models/Core/Validator.php");

if (!authenticated()) {
    header('Location: /login');
    exit();
}

require_once base_path("models/DataSets/UsersDataSet.php");
$usersDataSet = new UsersDataSet();

$oldPassword = $_POST['currentPassword'];
$newPassword = $_POST['newPassword'];
$confirmPassword = $_POST['confirmPassword'];


if (!Validator::string($newPassword, 2, 75)) {
    $errors['InvalidPassword'] = "New Password must be between 2 and 75 characters maximum!";
}

if (!Validator::string($oldPassword, 2, 75)) {
    $errors['InvalidPassword'] = "Old Password must be between 2 and 75 characters maximum!";
}

if (empty($oldPassword)) {
    $errors['NoAccount'] = "Please enter your current password.";
}

if ($newPassword !== $confirmPassword) {
    $errors['NoAccount'] = "Passwords do not match.";
}

if (!empty($errors)) {
    view('UpdateProfileForms/updatepassword.phtml', [
        'pageTitle' => 'Update Password',
        'errors' => $errors,
    ]);
    exit();
}

if ($_SESSION['user']['usertype'] == 1) {
    // ------------------------- Student
    $usersDataSet->updateUserPassword($_SESSION['user']['id'], $oldPassword, $newPassword);

    // Redirect the user to the editprofile page
    header('location: /success');
    exit();

} elseif($_SESSION['user']['usertype'] == 2) {
    // ------------------------- Employer
    $usersDataSet->updateUserPassword($_SESSION['user']['id'], $oldPassword, $newPassword);

    // Redirect the user to the editprofile page
    header('location: /success');
    exit();

} elseif($_SESSION['user']['usertype'] == 3) {
    // ------------------------- Careers Staff
    $usersDataSet->updateUserPassword($_SESSION['user']['id'], $oldPassword, $newPassword);

    // Redirect the user to the editprofile page
    header('location: /success');
    exit();

} elseif ($_SESSION['user']['usertype'] == 4) {
    // ------------------------- Library User
    $usersDataSet->updateUserPassword($_SESSION['user']['id'], $oldPassword, $newPassword);

    // Redirect the user to the editprofile page
    header('location: /success');
    exit();
}
