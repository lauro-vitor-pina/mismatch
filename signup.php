<?php

require_once(__DIR__ . '/src/services/connection_service.php');
require_once(__DIR__ . '/src/services/login_service.php');
require_once(__DIR__ . '/src/services/mismatch_user_service.php');


$user_logged = login_service_get_user_logged();

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

<?php
$page_title = 'Sign Up';
require_once(__DIR__ . '/src/templates/header.php');
require_once(__DIR__ . '/src/templates/navmenu.php');
?>

<?php if ($user_logged['is_logged'] == false) { ?>
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

    <?php if (!empty($signup_result)) echo $signup_result; ?>

<?php } else {
    echo '<p class="login"> You are logged in as ' . $user_logged['username'] . '. <p>';
} ?>


<?php require_once(__DIR__ . '/src/templates/footer.php'); ?>