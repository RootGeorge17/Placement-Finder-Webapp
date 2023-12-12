<?php

if(!authenticated())
{
    header('location: /login');
    exit();
}

view("team.phtml", [
    'pageTitle' => 'Team',
]);