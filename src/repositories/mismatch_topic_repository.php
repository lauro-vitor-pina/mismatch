<?php


function mismatch_topic_repository_get_all_topic_ids($dbc)
{
    $query = "SELECT topic_id
              FROM mismatch_topic  t
              INNER JOIN mismatch_category c on t.category_id = c.category_id
              ORDER BY c.name ASC, t.name ASC;";


    $query_result = mysqli_query($dbc, $query) or die('Error in mismatch_response_service_get_all_topics_id');

    $topic_ids = array();

    while ($row = mysqli_fetch_array($query_result)) {
        array_push($topic_ids, $row['topic_id']);
    }

    return $topic_ids;
}
