<?php

if (!authenticated()) {
    header('Location: /login');
    exit();
}

view('UpdateProfileForms/updatepassword.phtml', [
    'pageTitle' => 'Update Password',
]);

