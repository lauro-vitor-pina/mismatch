<?php

require_once(__DIR__ . '../../appvars.php');

session_start();

if (!isset($_SESSION[KEY_LOGIN_USER_ID]) && isset($_COOKIE[KEY_LOGIN_USER_ID])) {
    $_SESSION[KEY_LOGIN_USER_ID] = $_COOKIE[KEY_LOGIN_USER_ID];
}

if (!isset($_SESSION[KEY_LOGIN_USER_NAME]) && isset($_COOKIE[KEY_LOGIN_USER_NAME])) {
    $_SESSION[KEY_LOGIN_USER_NAME] = $_COOKIE[KEY_LOGIN_USER_NAME];
}
