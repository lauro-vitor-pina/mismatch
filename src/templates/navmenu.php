<?php
require_once(__DIR__ . '../../services/login/login_service_get_user_logged.php');
$user_logged = login_service_get_user_logged();
?>

<hr>

<?php if ($user_logged['is_logged']) { ?>

    <a href="index.php">Home</a> &#10084;

    <a href="viewprofile.php?user_id=<?php echo $user_logged['id']; ?>">View Profile</a> &#10084;

    <a href="editprofile.php">Edit Profile</a> &#10084;

    <a href="logout.php">Log Out (<?php echo $user_logged['username']; ?>)</a>

<?php } else { ?>
    
    <a href="index.php">Home</a> &#10084;

    <a href="login.php">Log In</a> &#10084;

    <a href="signup.php">Sign Up</a>

<?php } ?>

<hr>