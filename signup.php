<?php

require_once(__DIR__ . '/src/services/connection_service.php');
require_once(__DIR__ . '/src/services/mismatch_user/mismatch_user_service_signup.php');

$username = '';
$password1 = '';
$password2 = '';
$signup_result = null;

if (isset($_POST['submit'])) {

    $dbc = connection_service_get_dbc();

    $username = mysqli_real_escape_string($dbc, trim($_POST['username']));

    $password1 = mysqli_real_escape_string($dbc, trim($_POST['password1']));

    $password2 = mysqli_real_escape_string($dbc, trim($_POST['password2']));

    $signup_result = mismatch_user_service_signup($dbc, $username, $password1, $password2);

    connection_service_close($dbc);
}


?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mismatch - Sign Up</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <fieldset>
            <legend>Registration Info</legend>
            <br>

            <div>
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" value="<?php echo $username; ?>">
                <br> <br>
            </div>
            <div>
                <label for="password1">Password:</label>
                <input type="password" name="password1" id="password1" value="<?php echo $password1; ?>">
                <br> <br>
            </div>
            <div>
                <label for="password2">Password (retype):</label>
                <input type="password" name="password2" id="password2" value="<?php echo $password2; ?>">
                <br> <br>
            </div>
        </fieldset>
        <br>
        <input type="submit" name="submit" value="Sign Up">
    </form>

    <?php
    if ($signup_result != null) {
        echo $signup_result['message'];
    }
    ?>
</body>

</html>