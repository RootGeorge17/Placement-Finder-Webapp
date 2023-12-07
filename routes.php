<?php

$router->get('/', 'controllers/index.php');
$router->get('/dashboard', 'controllers/studentdashboard.php');

// Authentication
$router->get('/login', 'controllers/Authentication/login/login.php');
$router->post('/login', 'controllers/Authentication/Login/store.php');

$router->get('/register', 'controllers/Authentication/register/register.php');
$router->post('/register', 'controllers/Authentication/register/register.php');

