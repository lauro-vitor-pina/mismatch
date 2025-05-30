<?php

require_once(__DIR__ . '/login_service_start_session.php');
require_once(__DIR__ . '../../../appvars.php');

function login_service_user_is_logged()
{
    login_service_start_session();

    if (!isset($_SESSION[KEY_LOGIN_USER_ID]) && isset($_COOKIE[KEY_LOGIN_USER_ID])) {
        $_SESSION[KEY_LOGIN_USER_ID] = $_COOKIE[KEY_LOGIN_USER_ID];
    }

    if (!isset($_SESSION[KEY_LOGIN_USER_NAME]) && isset($_COOKIE[KEY_LOGIN_USER_NAME])) {
        $_SESSION[KEY_LOGIN_USER_NAME] = $_COOKIE[KEY_LOGIN_USER_NAME];
    }

    return  isset($_SESSION[KEY_LOGIN_USER_ID]) && isset($_SESSION[KEY_LOGIN_USER_NAME]);
}
