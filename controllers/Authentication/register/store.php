<?php

use models\Core\Validator;
require_once(base_path("models/DataSets/UsersDataSet.php"));
$usersDataSet = new UsersDataSet();

if($_POST['submit'] == "first")
{
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $accountType = $_POST['accountType'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if(!Validator::string($firstName, 2, 75) && !Validator::string($lastName, 2, 75))
    {
        $errors['InvalidName'] = "First and Last names must be between 2 and 75 characters maximum!";
    }

    if(!Validator::email($email))
    {
        $errors['InvalidEmail'] = "You have provided an invalid email";
    }

    if(!Validator::string($password, 2, 75))
    {
        $errors['InvalidPassword'] = "Password must be between 2 and 75 characters maximum!";
    }

    if($password !== $confirmPassword )
    {
        $errors['PasswordMatch'] = "Passwords do not match!";
    }

    if($accountType == "student")
    {
        header('Location: /register?step=2');
        exit();
    }

    header('Location: /register?step=3');
}









