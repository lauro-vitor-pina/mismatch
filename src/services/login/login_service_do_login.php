<?php 

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

    $expire = time() + (60 * 60 * 8);

    setcookie(KEY_LOGIN_USER_ID, $user['user_id'], $expire);

    setcookie(KEY_LOGIN_USER_NAME, $user['username'], $expire);

    header('Location: index.php');

    return null;
}