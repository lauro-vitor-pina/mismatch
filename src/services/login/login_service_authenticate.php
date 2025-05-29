<?php 

require_once(__DIR__ . '/login_service_user_is_logged.php');

function login_service_authenticate()
{
    if (login_service_user_is_logged()) {
        return;
    }

    header('Location: login.php');
}
