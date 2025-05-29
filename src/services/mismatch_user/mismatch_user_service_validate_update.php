<?php

function mismatch_user_service_validate_update($user_id, $first_name, $last_name, $gender, $birthdate, $city, $state)
{
    $results = [];

    if (!is_numeric($user_id)) {
        $results[] = '<p class="error">Invalid user id</p>';
    }

    if (empty($first_name)) {
        $results[] = '<p class="error">* First name is mandatory field</p>';
    }

    if (empty($last_name)) {
        $results[] = '<p class="error" >* Last name is mandatory field!</p>';
    }

    if (empty($gender)) {
        $results[] = '<p class="error">* Gender is mandatory field!</p>';
    }

    if (empty($birthdate)) {
        $results[] = '<p class="error">* Birth date is mandatory field!</p>';
    }

    if (empty($city)) {
        $results[] = '<p class="error">* City is mandatory field!</p>';
    }

    if (empty($state)) {
        $results[] = '<p class="error">* State is mandatory field!</p>';
    }

    return sizeof($results) == 0 ? null : $results;
}
