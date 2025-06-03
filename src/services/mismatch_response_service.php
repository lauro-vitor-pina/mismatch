<?php
require_once(__DIR__ . '/connection_service.php');
require_once(__DIR__ . '/login_service.php');
require_once(__DIR__ . '../../repositories/mismatch_response_repository.php');
require_once(__DIR__ . '../../repositories/mismatch_topic_repository.php');

function mismatch_response_service_handler_questionnaire()
{
    login_service_authenticate();

    $dbc = connection_service_get_dbc();

    $user_id = login_service_get_user_logged()['id'];

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {

        $user_has_response =  mismatch_response_repository_user_has_response($dbc, $user_id);

        if (!$user_has_response) {

            $topic_ids = mismatch_topic_repository_get_all_topic_ids($dbc);

            foreach ($topic_ids as $topic_id_item) {
                mismatch_response_repository_insert($dbc, $user_id, $topic_id_item);
            }
        }
    }

    if (isset($_POST['submit'])) {

        foreach ($_POST as $key => $value) {
            if (is_numeric($key)) {
                mismatch_response_repository_update($dbc, $user_id, $key, $value);
            }
        }
    }

    $result = mismatch_response_repository_get_all($dbc, $user_id);

    connection_service_close($dbc);

    return $result;
}
