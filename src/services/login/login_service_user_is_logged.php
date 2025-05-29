<?php

require_once(__DIR__ . '/login_service_start_session.php');
require_once(__DIR__ . '../../../appvars.php');

function login_service_user_is_logged()
{
    login_service_start_session();

    return isset($_SESSION[KEY_LOGIN_USER_ID]) && isset($_SESSION[KEY_LOGIN_USER_NAME]);
}
