<?php

require_once(__DIR__ . '/login_service_start_session.php');
require_once(__DIR__ . '../../../appvars.php');


function login_service_do_login($dbc, $username, $password)
{
    if (empty($username) || empty($password)) {
        return '<p class="error">The user name and password are mandatory field.</p>';
    }

    $query = "SELECT `user_id`, `username`, `password`
              FROM mismatch_user
              WHERE `username` = '$username' AND `password` = SHA('$password')
              LIMIT 1";

    $query_result = mysqli_query($dbc, $query);

    $user = mysqli_fetch_array($query_result);

    if ($user == null) {
        return '<p class="error"> Sorry, you must enter a valid username and password to log in.</p>';
    }

    login_service_start_session();
    $_SESSION[KEY_LOGIN_USER_ID] = $user['user_id'];
    $_SESSION[KEY_LOGIN_USER_NAME] = $user['username'];

    $expires = time() + (60 * 60 * 24 * 30);
    setcookie(KEY_LOGIN_USER_ID, $user['user_id'], $expires);
    setcookie(KEY_LOGIN_USER_NAME, $user['username'], $expires);

    header('Location: index.php');

    return null;
}
