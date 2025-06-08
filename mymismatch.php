<?php
require_once(__DIR__ . '/src/templates/startsession.php');
require_once(__DIR__ . '/src/services/login_service.php');
require_once(__DIR__ . '/src/services/connection_service.php');
require_once(__DIR__ . '/src/services/mismatch_response_service.php');

login_service_authenticate();

$user_logged   = login_service_get_user_logged();

$dbc = connection_service_get_dbc();

$result = mismatch_response_service_get_my_mismatch($dbc, $user_logged['id']);

connection_service_close($dbc);


?>

<?php

$page_title = 'My mismatch';

require_once(__DIR__ . '/src/templates/header.php');

require_once(__DIR__ . '/src/templates/navmenu.php');

if ($result == null) {
    echo '<h3>You are not mismatched with anyone :(</h3>';
} else {
    $mismatch_user = $result['mismatch_user'];
?>

    <table>
        <tr>
            <td>
                <b> <?php echo $mismatch_user['first_name'] . ' ' . $mismatch_user['last_name']; ?> </b>
                <br>
                <b> <?php echo $mismatch_user['city'] . ', ' . $mismatch_user['state']; ?> </b>
            </td>
            <td>
                <?php echo '<img width="80" height="80" src="' . MM_UPLOADPATH .  $mismatch_user['picture'] . '" '; ?>
            </td>
        </tr>
    </table>

    <h3>You are mismatched on the following <?php echo $result['mismatch_topics_count'] ?> topics</h3>

<?php

    foreach ($result['mismatch_topics_rows'] as $topic_row) {
        echo $topic_row . ' <br> ';
    }

    echo '<br>';

    echo 'View ';

    echo '<a href="viewprofile.php?user_id=' . $mismatch_user['user_id'] . '">' . $mismatch_user['first_name'] . '\'s profile' . '</a>';
}

?>

<?php require_once(__DIR__ . '/src/templates/footer.php'); ?>