<?php

function mismatch_response_service_exists_response_for_user($dbc, $user_id)
{
    $query = "SELECT COUNT(*) AS `qtd_response`
              FROM `mismatch_response` 
              WHERE `user_id` = $user_id;";

    $query_result = mysqli_query($dbc, $query) or die('Error in mismatch_response_service_exists_response');

    $result  = mysqli_fetch_array($query_result);

    $qtd_response = $result['qtd_response'];

    if ($qtd_response == 0) {
        return false;
    }

    return true;
}

function mismatch_response_service_get_all_topics_id($dbc)
{
    $query = "SELECT `topic_id`
              FROM `mismatch_topic` 
              ORDER BY `category` ASC, `name` ASC;";


    $query_result = mysqli_query($dbc, $query) or die('Error in mismatch_response_service_get_all_topics_id');

    $topic_ids = array();

    while ($row = mysqli_fetch_array($query_result)) {
        array_push($topic_ids, $row['topic_id']);
    }

    return $topic_ids;
}

function mismatch_response_service_insert_all_topics_in_response($dbc, $user_id, $topic_ids)
{
    $query = "INSERT INTO `mismatch_response` (`user_id`, `topic_id`,`response`) VALUES ";

    $last_id =  end($topic_ids);

    foreach ($topic_ids as $topic_id_item) {

        $query_value = $topic_id_item == $last_id ? " ($user_id, $topic_id_item, '');" : " ($user_id, $topic_id_item, ''),";

        $query = $query . $query_value;
    }

    mysqli_query($dbc, $query) or die('Error in  mismatch_response_service_insert_all_topics');

    if (mysqli_affected_rows($dbc) == 0) {
        throw  new Exception('Error in  insert all topics in response');
    }
}

function mismatch_response_service_get_all_response($dbc, $user_id)
{
    $query = "SELECT 
                r.response_id,
                r.topic_id,
                r.response,
                t.name,
                t.category
              FROM `mismatch_response` r
              INNER JOIN `mismatch_topic` t ON t.topic_id = r.topic_id
              WHERE r.user_id = $user_id
              ORDER BY t.category ASC, t.name ASC;";

    $query_result = mysqli_query($dbc, $query);

    $responses = array();

    while ($row = mysqli_fetch_array($query_result)) {
        array_push($responses, $row);
    }

    return $responses;
}

function mismatch_response_service_update_questionnaire($dbc, $user_id, $responses)
{

    foreach ($responses as $key => $value) {

        if (!is_numeric($key)) {
            continue;
        }

        $query = "UPDATE `mismatch_response`
                  SET `response` = '$value'
                  WHERE `response_id` = $key
                  AND `user_id` = $user_id; ";

        mysqli_query($dbc, $query) or die('Error in mismatch_response_service_update_questionnaire');
    }
}
