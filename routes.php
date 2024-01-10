<?php

$router->get('/', 'controllers/homepage.php');
$router->get('/dashboard', 'controllers/dashboard.php');
$router->post('/dashboard', 'controllers/DeletePlacement/deleteplacement.php');

$router->get('/team', 'controllers/team.php');

// Authentication
$router->get('/login', 'controllers/Authentication/login/login.php');
$router->post('/login', 'controllers/Authentication/login/store.php');
$router->get('/signout', 'controllers/Authentication/login/signout.php');

$router->get('/register', 'controllers/Authentication/register/register.php');
$router->post('/register', 'controllers/Authentication/register/store.php');

// Edit Profile
$router->get('/editprofile', 'controllers/Profile/EditProfile/editprofile_get.php');
$router->post('/editprofile', 'controllers/Profile/EditProfile/editprofile_post.php');
$router->update('/editprofile', 'controllers/Profile/Update/updateprofile.php'); // used for updating profile
$router->get('/deleteprofile', 'controllers/Profile/DeleteProfile/deleteprofile.php'); // used for deleting profile

$router->get('/changepassword', 'controllers/Profile/Update/updatepassword.php'); // used for updating password
$router->post('/changepassword', 'controllers/Profile/Update/updatepassword_post.php'); // used for updating password

$router->get('/addplacement', 'controllers/AddPlacement/addplacement.php');
$router->post('/addplacement', 'controllers/AddPlacement/addplacement_post.php');

$router->get('/placements', 'controllers/ViewAllPlacements/viewallplacements.php');

$router->get('/students', 'controllers/ViewAllStudents/viewallstudents.php');

$router->get('/success', 'controllers/Success/success.php');

