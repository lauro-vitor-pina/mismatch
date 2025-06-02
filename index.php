<?php
require_once(__DIR__ . '/src/appvars.php');
require_once(__DIR__ . '/src/services/connection_service.php');
require_once(__DIR__ . '/src/services/mismatch_user_service.php');
require_once(__DIR__ . '/src/services/login_service.php');

$dbc = connection_service_get_dbc();

$get_all_result = mismatch_user_service_get_all($dbc, 5, null, 'join_date', 'DESC');

connection_service_close($dbc);

?>


<?php

$page_title = 'Where opposites attract!';

require_once(__DIR__ . '/src/templates/header.php');

require_once(__DIR__ . '/src/templates/navmenu.php');

?>

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

<?php require_once(__DIR__ . '/src/templates/footer.php') ?>