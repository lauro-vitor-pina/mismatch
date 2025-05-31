<?php

require_once(__DIR__ . '/login_service_start_session.php');
require_once(__DIR__ . '/login_service_user_is_logged.php');
require_once(__DIR__ . '../../../appvars.php');


function login_service_logout()
{
    login_service_start_session();

    $_SESSION = array();
    
    $exipres = time() - 3600;

    setcookie(session_name(), '', $exipres);
    setcookie(KEY_LOGIN_USER_ID, '', $exipres);
    setcookie(KEY_LOGIN_USER_NAME, '', $exipres);

    session_destroy();

    header('Location: index.php');
}
