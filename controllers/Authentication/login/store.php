<?php
use models\Core\Validator;
require_once(base_path("models/DataSets/UsersDataSet.php"));
$usersDataSet = new UsersDataSet();

$email = $_POST['email'];
$password = $_POST['password'];
$errors = [];

if(!Validator::email($email)) {
    $errors['InvalidEmail'] = "You have provided an invalid email";
}

if(!Validator::string($password, 5, 45)) {
    $errors['InvalidPassword'] = "Password must be more than 5 characters and maximum 45 characters";
}

if(!empty($errors)) {
    return view('Authentication/login.phtml', [
        'errors' => $errors
    ]);
}

$userMatch = $usersDataSet->credentialsMatch($email, $password);

if(!$userMatch) {
    $errors['NoAccount'] = "Account doesn't exist!";
    return view('Authentication/login.phtml', [
        'errors' => $errors
    ]);
}

$userDetails = $usersDataSet->getUserDetails($email);

login(
    $userDetails['id'],
    $userDetails['email'],
    $userDetails['userType']
);

header('Location: /dashboard');
exit();





