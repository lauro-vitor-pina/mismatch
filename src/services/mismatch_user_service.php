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
                    `username`,
                    `first_name`, 
                    `last_name`,
                    `gender`, 
                     DATE_FORMAT(`birthdate`, '%d/%m/%Y') AS `birthdate`, 
                    `city`, 
                    `state`, 
                    `picture` 
              FROM `mismatch_user` 
              WHERE `user_id` = $user_id
              LIMIT 1";

    $query_result = mysqli_query($dbc, $query) or die('Error mismatch_user_service_get_by_id');

    $result = mysqli_fetch_array($query_result);

    return $result;
}

function mismatch_user_service_get_gender_description($gender)
{
    switch ($gender) {
        case 'M':
            return 'Male';
        case 'F':
            return 'Female';

        default:
            return null;
    }
}


function mismatch_user_service_signup($dbc, $username, $password1, $password2)
{
    $validate_result = mismatch_user_service_validate_signup($dbc, $username, $password1, $password2);

    if ($validate_result != null) {

        return '<p class="error">' . $validate_result . '</p>';
    }

    $query = "INSERT INTO mismatch_user (`username`, `password`, `join_date`) VALUES ('$username', SHA('$password1'), NOW())";

    mysqli_query($dbc, $query) or die('Error in mismatch_user_service_signup');

    $result =
        '<p>Your new account has been successfully created. You\'re now ready to log in and ' .
        '<a href="editprofile.php">edit your profile</a> </p>';

    return $result;
}


function mismatch_user_service_validate_signup($dbc, $username, $password1, $password2)
{
    if (empty($username)) {
        return 'username field is mandatory!';
    }

    if (empty($password1)) {
        return 'password field is  mandatory!';
    }

    if (empty($password2)) {
        return 'password (retype) field is mandatory!';
    }

    if ($password1 != $password2) {
        return 'Password must be equal!';
    }

    $query = "SELECT * FROM mismatch_user WHERE username = '$username'";

    $query_result = mysqli_query($dbc, $query);

    if (mysqli_num_rows($query_result) != 0) {
        return 'An account already exists for  this username. Please use a different';
    }

    return null;
}


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

function mismatch_user_service_upload_picture(
    $dbc,
    $user_id,
    $new_picture_name,
    $new_picture_type,
    $new_picture_size,
    $new_picture_tmp_name,
    &$old_picture_name
) {

    if (empty($new_picture_name)) {
        return null;
    }

    if (
        $new_picture_type != 'image/gif' &&
        $new_picture_type != 'image/jpeg' &&
        $new_picture_type != 'image/pjpeg' &&
        $new_picture_type != 'image/png'
    ) {
        @unlink($new_picture_tmp_name);
        return '<p class="error" >The new picture type must be a image.</p>';
    }

    if ($new_picture_size <= 0 || $new_picture_size > MM_MAXFILESIZE) {
        @unlink($new_picture_tmp_name);
        return '<p class="error"> The picture must be no  greater than ' .  (MM_MAXFILESIZE / 1024) . 'KB <p>';
    }

    list($new_picture_width, $new_picture_height) = getimagesize($new_picture_tmp_name);

    if ($new_picture_width > MM_MAXIMGWIDTH || $new_picture_height > MM_MAXIMGHEIGHT) {
        @unlink($new_picture_tmp_name);
        return '<p class="error"> The picture must be no greater than ' . (MM_MAXIMGWIDTH . 'x' . MM_MAXIMGHEIGHT) . ' pixels in size</p>';
    }

    $new_picture_name = uniqid() . '_' . $new_picture_name;

    $path  = MM_UPLOADPATH . basename($new_picture_name);

    $upload_result = move_uploaded_file($new_picture_tmp_name, $path);

    if ($upload_result == false) {
        @unlink($new_picture_tmp_name);
        return '<p class="error">Sorry, there was a problem uploading your picture</p>';
    }

    if ($old_picture_name != $new_picture_name) {
        @unlink(MM_UPLOADPATH . $old_picture_name);
    }

    $query = "UPDATE mismatch_user SET picture = '$new_picture_name' WHERE user_id = $user_id LIMIT 1";

    mysqli_query($dbc, $query) or die('error in mismatch_user_service_upload_picture');

    $old_picture_name = $new_picture_name;

    return '<p class="success">Your upload was sucessfuly</p>';
}

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
