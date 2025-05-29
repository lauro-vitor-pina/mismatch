<?php
require_once(__DIR__ . '/login_service_start_session.php');
require_once(__DIR__ . '/login_service_user_is_logged.php');
require_once(__DIR__ . '../../../appvars.php');

function login_service_get_user_logged()
{
    $user_logged = [
        'id' => '',
        'username' => '',
        'is_logged' => false,
    ];

    if (login_service_user_is_logged()) {

        login_service_start_session();
        $user_logged['id'] = $_SESSION[KEY_LOGIN_USER_ID];
        $user_logged['username'] = $_SESSION[KEY_LOGIN_USER_NAME];
        $user_logged['is_logged'] = true;
    }

    return $user_logged;
}
