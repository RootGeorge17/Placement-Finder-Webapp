<?php

$router->get('/', 'controllers/homepage.php');
$router->get('/dashboard', 'controllers/dashboard.php');

$router->get('/team', 'controllers/team.php');

// Authentication
$router->get('/login', 'controllers/Authentication/login/login.php');
$router->post('/login', 'controllers/Authentication/login/store.php');
$router->get('/signout', 'controllers/Authentication/login/signout.php');

$router->get('/register', 'controllers/Authentication/register/register.php');
$router->post('/register', 'controllers/Authentication/register/store.php');

// Edit Profile
$router->get('/editprofile', 'controllers/EditProfile/editprofile_get.php');
$router->post('/editprofile', 'controllers/EditProfile/editprofile_post.php');
$router->delete('/editprofile', 'controllers/DeleteProfile/deleteprofile.php');
$router->post('/editprofile/changepassword', 'controllers/EditProfile/editprofile_post.php');

$router->get('/addplacement', 'controllers/AddPlacement/addplacement.php');
$router->post('/addplacement', 'controllers/AddPlacement/addplacement_post.php');
$router->delete('/addplacement', 'controllers/DeletePlacement/deleteplacement.php');

$router->get('/placements', 'controllers/ViewAllPlacements/viewallplacements.php');

$router->get('/students', 'controllers/ViewAllStudents/viewallstudents.php');

