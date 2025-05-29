<?php 

require_once(__DIR__ . '/login_service_user_is_logged.php');
require_once(__DIR__ . '../../../appvars.php');


function login_service_logout()
{
    if (login_service_user_is_logged()) {

        $expire =  time() - 360;

        setcookie(KEY_LOGIN_USER_ID, '', $expire);
        setcookie(KEY_LOGIN_USER_NAME, '', $expire);
    }

    header('Location: index.php');
}
