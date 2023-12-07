<?php

$router->get('/', 'controllers/index.php');

$router->get('/dashboard', 'controllers/studentdashboard.php');

// Authentication
$router->get('/login', 'controllers/Authentication/login.php');
$router->post('/login', 'controllers/Authentication/store.php');

