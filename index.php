<?php
require_once(__DIR__ . '/src/appvars.php');
require_once(__DIR__ . '/src/services/connection_service.php');
require_once(__DIR__ . '/src/services/mismatch_user/mismatch_user_sevice_get_all.php');
require_once(__DIR__ . '/src/services/login/login_service_get_user_logged.php');

$user_logged = login_service_get_user_logged();

$dbc = connection_service_get_dbc();

$limit = $user_logged['is_logged'] ? null : 5;

$get_all_result = mismatch_user_service_get_all($dbc, $limit, null, 'join_date', 'DESC');

connection_service_close($dbc);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Mismatch - Where opposites attract!</title>
    <link rel="stylesheet" type="text/css" href="css/style.css" />
</head>

<body>

    <h3>Mismatch - Where opposites attract! </h3>

    <?php if ($user_logged['is_logged']) { ?>

        &#10084; <a href="viewprofile.php?user_id=<?php echo $user_logged['id']; ?>">View Profile</a><br />

        &#10084; <a href="editprofile.php">Edit Profile</a><br />

        &#10084; <a href="logout.php">Log Out (<?php echo $user_logged['username']; ?>)</a>

    <?php } else { ?>

        &#10084; <a href="login.php">Log In</a><br />

        &#10084; <a href="signup.php">Sign Up</a><br />

    <?php } ?>

    <h4>Latest members:</h4>

    <table>

        <?php

        foreach ($get_all_result as $row) {

            $user_id = $row['user_id'];
            $first_name = $row['first_name'];
            $picture = MM_UPLOADPATH . $row['picture'];
            $exists_picture = is_file($picture) && filesize($picture) > 0;

            if (!$exists_picture) {
                $picture =  MM_UPLOADPATH . 'nopic.jpg';
            }

            echo '<tr>';

            echo "<td> <img src='$picture' alt='$first_name'  width='80' height='80'/> </td>";

            if ($user_logged['is_logged']) {
                echo "<td> <a href='viewprofile.php?user_id=$user_id'> $first_name </a> </td>";
            } else {
                echo "<td> $first_name </td>";
            }

            echo '</tr>';
        }

        ?>

    </table>

</body>

</html>