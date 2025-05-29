<?php

function login_service_start_session()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}
