<?php

use models\Core\Validator;

$email = $_POST['email'];
$password = $_POST['passowrd'];
$errors = [];

if($_POST[''])
if(!Validator::email($email)) {
    $errors['InvalidEmail'] = "You have provided an invalid email";
}

if(!Validator::string($password, 5, 45)) {
    $errors['InvalidPassword'] = "Password must be more than 5 characters and maximum 45 characters";
}
