<?php

require_once(__DIR__ . '../../appvars.php');

function login_service_authenticate()
{
    if (login_service_user_is_logged()) {
        return;
    }

    header('Location: login.php');
}

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


    $_SESSION[KEY_LOGIN_USER_ID] = $user['user_id'];
    $_SESSION[KEY_LOGIN_USER_NAME] = $user['username'];

    $expires = time() + (60 * 60 * 24 * 30);
    setcookie(KEY_LOGIN_USER_ID, $user['user_id'], $expires);
    setcookie(KEY_LOGIN_USER_NAME, $user['username'], $expires);

    header('Location: index.php');

    return null;
}

function login_service_get_user_logged()
{
    $user_logged = [
        'id' => '',
        'username' => '',
        'is_logged' => false,
    ];

    if (login_service_user_is_logged()) {

        $user_logged['id'] = $_SESSION[KEY_LOGIN_USER_ID];
        $user_logged['username'] = $_SESSION[KEY_LOGIN_USER_NAME];
        $user_logged['is_logged'] = true;
    }

    return $user_logged;
}

function login_service_logout()
{

    $_SESSION = array();

    $exipres = time() - 3600;

    setcookie(session_name(), '', $exipres);
    setcookie(KEY_LOGIN_USER_ID, '', $exipres);
    setcookie(KEY_LOGIN_USER_NAME, '', $exipres);

    session_destroy();

    header('Location: index.php');
}

function login_service_user_is_logged()
{
    return  isset($_SESSION[KEY_LOGIN_USER_ID]) && isset($_SESSION[KEY_LOGIN_USER_NAME]);
}
