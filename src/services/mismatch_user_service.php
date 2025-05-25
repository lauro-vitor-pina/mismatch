<?php

function mismatch_user_service_get_all($dbc, $limit, $offset, $sort_prop, $sort_dir)
{
    $query = "SELECT 
                    `user_id`, 
                    `join_date`, 
                    `first_name`, 
                    `last_name`,
                    `gender`, 
                    `birthdate`, 
                    `city`, 
                    `state`, 
                    `picture` 
              FROM `mismatch_user` 
              WHERE 1 = 1";

    if ($sort_prop != null && $sort_dir != null) {

        $query .= " ORDER BY `$sort_prop` $sort_dir ";
    }


    if ($limit != null) {

        $query .= " LIMIT $limit ";

        if ($offset != null) {
            $query .= " OFFSET $offset ";
        }
    }


    $query_result = mysqli_query($dbc, $query) or die('Error mismatch_user_service_get_all');

    $get_all_result = [];

    while ($row = mysqli_fetch_array($query_result)) {
        $get_all_result[] = $row;
    }

    return $get_all_result;
}

function mismatch_user_service_get_by_id($dbc, $user_id)
{
    $query = "SELECT 
                    `user_id`, 
                    `join_date`, 
                    `first_name`, 
                    `last_name`,
                    `gender`, 
                    `birthdate`, 
                    `city`, 
                    `state`, 
                    `picture` 
              FROM `mismatch_user` 
              WHERE `user_id` = $user_id
              LIMIT 1";

    $query_result = mysqli_query($dbc, $query) or die('Error mismatch_user_service_get_by_id');

    $mismatch_user = null;

    while ($row = mysqli_fetch_array($query_result)) {
        $mismatch_user = $row;
    }

    return $mismatch_user;
}

function mismatch_user_service_update($dbc, $user_id, $first_name, $last_name, $gender, $birthdate, $city, $state, $picture)
{
    $query = "UPDATE `mismatch_user` 
              SET
                `first_name`='$first_name',
                `last_name`='$last_name',
                `gender`='$gender',
                `birthdate`='$birthdate',
                `city`='$city',
                `state`='$state',
                `picture`='$picture' 
                WHERE  `user_id`= $user_id
                LIMIT 1";

    mysqli_query($dbc, $query) or die('Error mismatch_user_service_update');
}


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
