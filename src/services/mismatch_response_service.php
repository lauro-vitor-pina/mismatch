<?php

require_once(__DIR__ . '../../repositories/mismatch_response_repository.php');
require_once(__DIR__ . '../../repositories/mismatch_topic_repository.php');

function mismatch_response_service_handler_questionnaire($dbc, $user_id, $response_data)
{
    if (!mismatch_response_repository_user_has_response($dbc, $user_id)) {

        $topic_ids = mismatch_topic_repository_get_all_topic_ids($dbc);

        foreach ($topic_ids as $topic_id_item) {
            mismatch_response_repository_insert($dbc, $user_id, $topic_id_item);
        }
    }

    if (isset($response_data)) {

        foreach ($response_data as $response_id => $response_value) {
            if (is_numeric($response_id)) {
                mismatch_response_repository_update($dbc, $user_id, $response_id, $response_value);
            }
        }
    }

    $result = mismatch_response_repository_get_all($dbc, $user_id);

    return $result;
}
