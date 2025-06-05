<?php


function mismatch_response_repository_get_all($dbc, $user_id)
{
    $query = "SELECT 
                r.response_id,
                r.topic_id,
                r.response,
                t.name AS topic,
                c.name AS category
              FROM `mismatch_response` r
              INNER JOIN `mismatch_topic` t ON t.topic_id = r.topic_id
              INNER JOIN `mismatch_category` c ON  c.category_id =  t.category_id
              WHERE r.user_id = $user_id
              ORDER BY c.name ASC, t.name ASC;";

    $query_result = mysqli_query($dbc, $query);

    $responses = array();

    while ($row = mysqli_fetch_array($query_result)) {
        array_push($responses, $row);
    }

    return $responses;
}


function mismatch_response_repository_insert($dbc, $user_id, $topic_id)
{
    $query = "INSERT INTO `mismatch_response` (`user_id`, `topic_id`,`response`) VALUES ($user_id, $topic_id, '');";

    mysqli_query($dbc, $query) or die('Error in  mismatch_response_repository_insert');

    if (mysqli_affected_rows($dbc) == 0) {
        throw  new Exception('Error in  mismatch_response_repository_insert');
    }
}


function mismatch_response_repository_update($dbc, $user_id, $response_id, $response_value)
{
    $query = "UPDATE `mismatch_response`
              SET `response` = '$response_value'
              WHERE `response_id` = $response_id
              AND `user_id` = $user_id;";

    mysqli_query($dbc, $query) or die('Error in mismatch_response_repository_update');
}

function mismatch_response_repository_user_has_response($dbc, $user_id)
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
