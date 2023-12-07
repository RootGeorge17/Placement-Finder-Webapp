<?php

$router->get('/', 'controllers/index.php');

$router->get('/dashboard', 'controllers/studentdashboard.php');

$router->get('/register', 'controllers/Authentication/register.php');

$router->post('/register', 'controllers/Authentication/register.php');

$router->get('/register', 'controllers/Authentication/register/register.php');
