<?php

if(!authenticated())
{
    header('location: /login');
    exit();
}

view("index.phtml", [
    'pageTitle' => 'Dashboard',
    'heading' => 'Home',
]);
