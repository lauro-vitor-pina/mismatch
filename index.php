<?php
require_once(__DIR__ . '/src/appvars.php');
require_once(__DIR__ . '/src/services/connection_service.php');
require_once(__DIR__ . '/src/services/mismatch_user/mismatch_user_sevice_get_all.php');

$dbc = connection_service_get_dbc();
$get_all_result =  mismatch_user_service_get_all($dbc, 5, null, 'join_date', 'DESC');
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

    &#10084; <a href="viewprofile.php">View Profile</a><br />

    &#10084; <a href="editprofile.php">Edit Profile</a><br />

    <h4>Latest members:</h4>


    <table>

        <?php

        foreach ($get_all_result as $row) {

            $picture = MM_UPLOADPATH . $row['picture'];

            $exists_picture = is_file($picture) && filesize($picture) > 0;

            if (!$exists_picture) {
                $picture =  MM_UPLOADPATH . 'nopic.jpg';
            }

            echo '<tr>';

            echo '<td> <img src="' . $picture . '" alt="' . $row['first_name'] . '" /></td>';

            echo '<td>' . $row['first_name'] . '</td>';

            echo '</tr>';
        }

        ?>

    </table>

</body>

</html>