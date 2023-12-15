<?php

// Perform the logout process
logout();

// Redirect the user to the login page
header('location: /');
exit();
