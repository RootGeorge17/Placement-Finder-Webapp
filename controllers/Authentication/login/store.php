<?php
use models\Core\Validator;
require_once(base_path("models/DataSets/UsersDataSet.php"));
$usersDataSet = new UsersDataSet();

$email = $_POST['email'];
$password = $_POST['password'];
$errors = [];

if(!empty($errors)) {
    return view('Authentication/login.phtml', [
        'errors' => $errors
    ]);
}

$userMatch = $usersDataSet->credentialsMatch($email, $password);

if(!$userMatch) {
    $errors['NoAccount'] = "Sorry we didn't recognise those details.";
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





