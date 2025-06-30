<?php

require_once(__DIR__ . '../../repositories/mismatch_response_repository.php');
require_once(__DIR__ . '../../repositories/mismatch_topic_repository.php');
require_once(__DIR__ . '../../repositories/mismatch_user_repository.php');

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

        echo '<h4 class="success">Your questionnaire has been updated.</h4>';
    }

    $result = mismatch_response_repository_get_all_by_user_id($dbc, $user_id);

    return $result;
}

function mismatch_response_service_get_my_mismatch($dbc, $user_id)
{
    $responses_user = mismatch_response_repository_get_all_by_user_id($dbc, $user_id);

    if (count($responses_user) <= 0) {  //só é possível achar um par para o usuário que tiver respondido o questionário
        return null;
    }

    $others_users_ids = mismatch_user_repository_get_user_ids_with_response_except($dbc, $user_id);

    if (count($others_users_ids) <= 0) { // se nehuma pessoa respondeu o questionário não existe match
        return null;
    }


    $mismatch_score = 0; //armazena o placar de desencontrabilidade entre dois usuários - o maior placar será o melhor par imperfeito
    $mismatch_user_id = -1; //armazena o id do usuário que está sendo verificado como um pontencial par
    $mismatch_topics = null; //array que armazena os tópicos que tem respostas opostas entre dois usuários
    $mismatch_categories = array(); // array que armazena as categorias que foram opostas entre os dois usuários

    foreach ($others_users_ids as $other_user_id) {

        $responses_other_user = mismatch_response_repository_get_all_by_user_id($dbc, $other_user_id); //obtem as respostas de outro usuário

        $score = 0;
        $topics = array();
        $categories = array(); //array que recebe as categorias de respostas opostas entre dois usuários

        for ($i = 0; $i < sizeof($responses_other_user); $i++) {

            $response_user_item = $responses_user[$i]['response'];

            $response_other_user_item = $responses_other_user[$i]['response'];

            if (
                $response_user_item != $response_other_user_item &&
                !empty($response_user_item) &&
                !empty($response_other_user_item)
            ) {
                $score++;
                array_push($topics, $responses_user[$i]['topic']);
                array_push($categories, $responses_user[$i]['category']);
            }
        }

        if ($score > $mismatch_score) { //verifica se esta pessoa é melhor do que o melhor par até agora
            $mismatch_score = $score;
            $mismatch_user_id  = $other_user_id;
            $mismatch_topics =  $topics;
            $mismatch_categories =  $categories;
        }
    }

    if ($mismatch_user_id == -1) { // não encontrou o usuário de match
        return null;
    }

    $mismatch_user = mismatch_user_repository_get_by_id($dbc, $mismatch_user_id);

    $mismatch_category_totals = mismatch_response_service_obter_total_categorias($mismatch_categories);


    $result = [
        'mismatch_user' => $mismatch_user,
        'mismatch_topics_rows' => $mismatch_topics,
        'mismatch_topics_count' => count($mismatch_topics),
        'mismatch_category_totals' => $mismatch_category_totals
    ];

    return $result;
}

/*
 * Esta função obtem as categorias e a a sua quantidade de vezes que as repostas foram opostas entre dois usuários
 * @param array de categorias
 * @return um array bi dimensional de categoria e a sua respectiva quantidade
 */
function mismatch_response_service_obter_total_categorias($categorias)
{
    $categorias_total = array();

    foreach ($categorias as $categoria_item) {

        $indice = obter_indice_matriz($categorias_total, $categoria_item);

        if ($indice != null) {

            $i = $indice['i'];
            $j = $indice['j'] + 1; // adiciona mais um porque é onde esta a quantidade
            $categorias_total[$i][$j]++;

        } else {
            array_push($categorias_total, array($categoria_item, 1));
        }
    }

    return $categorias_total;
}

/*
* A função busca a posição i, j de um dado termo em uma matriz
* @params a matriz a ser pesquisada
* @params o termo a ser encontrado
* @returns um array associativo com a posição i j da matriz ou nulo caso o termo não for encontrado.
*/
function obter_indice_matriz($matriz, $termo)
{
    if ($matriz == null || sizeof($matriz) == 0) {
        return null;
    }

    for ($i = 0; $i < sizeof($matriz); $i++) {

        for ($j = 0; $j < sizeof($matriz[$i]); $j++) {

            if ($matriz[$i][$j] == $termo) {

                $result = [
                    'i' => $i,
                    'j' => $j
                ];

                return $result;
            }
        }
    }

    return null;
}
