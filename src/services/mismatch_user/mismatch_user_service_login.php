<?php 

function mismatch_user_service_login($dbc)
{
    $user_username = '';
    $user_password = '';

    if (isset($_SERVER['PHP_AUTH_USER'])) {
        $user_username = mysqli_real_escape_string($dbc, $_SERVER['PHP_AUTH_USER']);
    }

    if (isset($_SERVER['PHP_AUTH_PW'])) {
        $user_password = mysqli_real_escape_string($dbc, $_SERVER['PHP_AUTH_PW']);
    }


    $query = "SELECT `user_id`, `username`, `password`
              FROM mismatch_user
              WHERE `username` = '$user_username' AND `password` = SHA('$user_password')";

    $query_result = mysqli_query($dbc, $query);

    if (mysqli_num_rows($query_result) != 1) {
        header('HTTP/1.1 401 Unauthorized');
        header('WWW-Authenticate: Basic realm="Mismatch"');
        connection_service_close($dbc);
        exit('<h3>Mismatch</h3> Sorry, you must enter a valid username and password to log in and access this page.');
    }

    $row = mysqli_fetch_array($query_result);

    $login_result = [
        'user_id' => $row['user_id'],
        'username' => $row['username']
    ];

    echo '<p class="login"> You are logged as ' . $user_username . '</p>';

    return $login_result;
}