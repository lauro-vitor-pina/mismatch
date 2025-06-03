<?php
require_once(__DIR__ . '/src/services/connection_service.php');
require_once(__DIR__ . '/src/services/login_service.php');
require_once(__DIR__ . '/src/services/mismatch_response_service.php');

login_service_authenticate();

$dbc = connection_service_get_dbc();

$user = login_service_get_user_logged();

$result = mismatch_response_service_handler_questionnaire($dbc, $user['id'], $_POST);

connection_service_close($dbc);

?>


<?php
$page_title =  'Questionnaire';
require_once(__DIR__ . '/src/templates/header.php');
require_once(__DIR__ . '/src/templates/navmenu.php');
?>


<p> How do you feel about each topic ? </p>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

    <?php

    $category = $result[0]['category'];

    echo '<fieldset> <legend>' . $category . '</legend>';

    foreach ($result as $response_item) {

        $response_id = $response_item['response_id'];
        $current_category = $response_item['category'];
        $topic_name = $response_item['name'];
        $response_value = $response_item['response'];

        if ($category != $current_category) {

            $category = $current_category;

            echo '</fieldset>';

            echo '<fieldset> <legend>' . $category . '</legend>';
        }

        echo "<label for='$response_id'> $topic_name </label>";

        $love_checked = ($response_value == 'Love' ? 'checked="checked"' : '');
        echo "<input type='radio' name='$response_id' value='Love' $love_checked> Love";

        $hate_checked = ($response_value == 'Hate' ? 'checked="checked"' : '');
        echo "<input type='radio' name='$response_id' value='Hate' $hate_checked> Hate";

        echo '<br />';
    }

    echo '</fieldset>';

    ?>

    <br />

    <input type="submit" value="Save Questionnaire" name="submit" />

</form>

<?php require_once(__DIR__ . '/src/templates/footer.php'); ?>