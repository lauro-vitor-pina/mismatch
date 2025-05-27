<?php

function mismatch_user_service_signup($dbc, $username, $password1, $password2)
{
    $signup_result = [
        'message' => ''
    ];

    $validate_result = mismatch_user_service_validate_signup($dbc, $username, $password1, $password2);

    if ($validate_result != null) {

        $signup_result['message'] = '<p class="error">' . $validate_result . '</p>';

        return $signup_result;
    }

    $query = "INSERT INTO mismatch_user (`username`, `password`, `join_date`) VALUES ('$username', SHA('$password1'), NOW())";

    mysqli_query($dbc, $query) or die('Error in mismatch_user_service_signup');

    $signup_result['message'] =
        '<p>Your new account has been successfully created. You\'re now ready to log in and ' .
        '<a href="editprofile.php">edit your profile</a> </p>';

    return $signup_result;
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
