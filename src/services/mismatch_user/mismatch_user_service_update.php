<?php


function mismatch_user_service_update($dbc, $user_id, $first_name, $last_name, $gender, $birthdate, $city, $state)
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

    mysqli_query($dbc, $query) or die('Error mismatch_user_service_update');

    return '<p class="success">The user has been updated with successfuly</p>';
}


