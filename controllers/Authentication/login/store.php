<?php
require_once(base_path("models/DataSets/UsersDataSet.php"));
$usersDataSet = new UsersDataSet();

$email = $_POST['email'];
$password = $_POST['password'];
$errors = [];

$userMatch = $usersDataSet->credentialsMatch($email, $password);

if(!$userMatch) {
    $errors['NoAccount'] = "Sorry we didn't recognise those details.";
    return view('Authentication/login.phtml', [
        'errors' => $errors,
        'pageTitle' => 'Login'
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





