<?php

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
