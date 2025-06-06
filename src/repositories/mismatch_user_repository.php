<?php

function mismatch_user_repository_get_all($dbc, $limit, $offset, $sort_prop, $sort_dir)
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


    $query_result = mysqli_query($dbc, $query) or die('Error mismatch_user_repository_get_all');

    $get_all_result = [];

    while ($row = mysqli_fetch_array($query_result)) {
        $get_all_result[] = $row;
    }

    return $get_all_result;
}


function mismatch_user_repository_get_by_id($dbc, $user_id)
{
    $query = "SELECT 
                    `user_id`, 
                    `join_date`, 
                    `username`,
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

    $query_result = mysqli_query($dbc, $query) or die('Error mismatch_user_repository_get_by_id');

    $result = mysqli_fetch_array($query_result);

    return $result;
}

function mismatch_user_repository_get_user_ids_with_response_except($dbc, $user_id)
{

    $query = "SELECT mu.user_id 
              FROM mismatch_user AS mu
              WHERE mu.user_id != $user_id
              AND EXISTS(
                  SELECT mr.response_id
                  FROM mismatch_response AS mr 
                  WHERE mr.user_id = mu.user_id
              )";

    $query_result = mysqli_query($dbc, $query) or die('Error mismatch_user_repository_get_user_ids_with_response_except');

    $result = array();

    while ($row = mysqli_fetch_array($query_result)) {
        array_push($result, $row['user_id']);
    }

    return $result;
}

function mismatch_user_repository_insert($dbc, $username, $password)
{

    $query = "INSERT INTO mismatch_user (`username`, `password`, `join_date`) VALUES ('$username', SHA('$password'), NOW())";

    mysqli_query($dbc, $query) or die('Error in mismatch_user_service_signup');
}

function mismatch_user_repository_update_picture($dbc, $user_id, $new_picture_name)
{
    $query = "UPDATE mismatch_user SET picture = '$new_picture_name' WHERE user_id = $user_id LIMIT 1";

    mysqli_query($dbc, $query) or die('error in mismatch_user_service_upload_picture');
}


function mismatch_user_repository_update($dbc, $user_id, $first_name, $last_name, $gender, $birthdate, $city, $state)
{
    $query = "UPDATE `mismatch_user` 
              SET
                `first_name`='$first_name',
                `last_name`='$last_name',
                `gender`='$gender',
                `birthdate`='$birthdate',
                `city`='$city',
                `state`='$state'
                WHERE  `user_id`= $user_id
                LIMIT 1";

    mysqli_query($dbc, $query) or die('Error mismatch_user_repository_update');

    return true;
}
