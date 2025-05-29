<?php

require_once(__DIR__ . '/login_service_start_session.php');
require_once(__DIR__ . '/login_service_user_is_logged.php');
require_once(__DIR__ . '../../../appvars.php');


function login_service_logout()
{
    if (login_service_user_is_logged()) {

        login_service_start_session();

        $_SESSION = array();

        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600);
        }

        session_destroy();
    }

    header('Location: index.php');
}
