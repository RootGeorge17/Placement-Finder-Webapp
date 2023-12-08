<?php

if(authenticated())
{
    header('location: /dashboard');
    exit();
}

view("Authentication/login.phtml", [
    'pageTitle' => 'Login',
]);


