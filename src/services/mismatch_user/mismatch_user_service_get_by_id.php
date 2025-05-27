<?php

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

    $mismatch_user = mysqli_fetch_array($query_result);


    return $mismatch_user;
}
