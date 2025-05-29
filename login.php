<?php

require_once(__DIR__ . '/src/services/connection_service.php');
require_once(__DIR__ . '/src/services/login/login_service_get_user_logged.php');
require_once(__DIR__ . '/src/services/login/login_service_do_login.php');

$user_logged = login_service_get_user_logged();
$username = '';
$password =  '';
$message = '';


if (isset($_POST['submit'])) {

    $dbc = connection_service_get_dbc();

    $username = mysqli_real_escape_string($dbc, trim($_POST['username']));

    $password = mysqli_real_escape_string($dbc, trim($_POST['password']));

    $message = login_service_do_login($dbc, $username, $password);

    connection_service_close($dbc);
}

?>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Mismatch - Login</title>
    <link rel="stylesheet" type="text/css" href="css/style.css" />
</head>

<body>
    <h2>Log In</h2>

    <?php if ($user_logged['is_logged'] == false) { ?>

        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <fieldset>
                <legend>Log In</legend>
                <div>
                    <label for="username">Username:</label>
                    <input type="text" name="username" value="<?php echo $username; ?>">
                    <br><br>
                </div>
                <div>
                    <label for="password">Password:</label>
                    <input type="password" name="password" value="<?php echo $password; ?>">
                </div>
            </fieldset>

            <br>

            <input type="submit" name="submit" value="Login">
        </form>

    <?php } else {
        echo '<p class="login"> You are logged in as ' . $user_logged['username'] . '. <p>';
    } ?>


    <?php echo $message; ?>

</body>

</html>