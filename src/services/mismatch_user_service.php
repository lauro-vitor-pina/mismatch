<?php

require_once(__DIR__ . '../../repositories/mismatch_user_repository.php');

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

    mismatch_user_repository_insert($dbc, $username, $password1);
    
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

    mismatch_user_repository_update_picture($dbc, $user_id, $new_picture_name);

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
