<?php 

require_once(__DIR__ . '../../../appvars.php');

function login_service_user_is_logged()
{
    return isset($_COOKIE[KEY_LOGIN_USER_ID]) && isset($_COOKIE[KEY_LOGIN_USER_NAME]);
}