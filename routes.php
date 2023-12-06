<?php

$router->get('/', 'controllers/index.php');

// Authentication
$router->get('/login', 'controllers/Authentication/login.php');
$router->post('/login', 'controllers/Authentication/store.php');
