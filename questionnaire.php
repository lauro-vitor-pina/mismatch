<?php
require_once(__DIR__ . '/src/services/connection_service.php');
require_once(__DIR__ . '/src/services/mismatch_response_service.php');
require_once(__DIR__ . '/src/services/login_service.php');

$page_title =  'Questionnaire';
require_once(__DIR__ . '/src/templates/header.php');
require_once(__DIR__ . '/src/templates/navmenu.php');

login_service_authenticate();

$user_logged = login_service_get_user_logged();

$dbc = connection_service_get_dbc();


if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $exists_response = mismatch_response_service_exists_response_for_user($dbc, $user_logged['id']);

    if (!$exists_response) {

        $topic_ids = mismatch_response_service_get_all_topics_id($dbc);

        mismatch_response_service_insert_all_topics_in_response($dbc, $user_logged['id'], $topic_ids);
    }
}

if (isset($_POST['submit'])) {
    mismatch_response_service_update_questionnaire($dbc, $user_logged['id'], $_POST);
}

$responses = mismatch_response_service_get_all_response($dbc, $user_logged['id']);

connection_service_close($dbc);



echo '<p> How do you feel about each topic ? </p>';

echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';

$category = $responses[0]['category'];

echo '<fieldset> <legend>' . $category . '</legend>';

foreach ($responses as $response_item) {

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
    echo "<input type='radio' name='$response_id' value='Love' $love_checked > Love";

    $hate_checked = ($response_value == 'Hate' ? 'checked="checked"' : '');
    echo "<input type='radio' name='$response_id' value='Hate' $hate_checked > Hate";

    echo '<br/>';
}

echo '</fieldset>';

echo '<br/>';

echo '<input type="submit" value="Save Questionnaire" name="submit"/>';

echo '</form>';

require_once(__DIR__ . '/src/templates/footer.php');
