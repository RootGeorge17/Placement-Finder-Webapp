<?php

if (!authenticated()) {
    header('location: /login');
    exit();
}

if ($_POST['submit'] == 'changepassword') {

}