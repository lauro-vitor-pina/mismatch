<?php

if (connection_service_is_local_enviroment()) {
    require_once(__DIR__ . '../../connectvars.dev.php');
} else {
    require_once(__DIR__ . '../../connectvars.prd.php');
}


function connection_service_get_dbc()
{
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, 3306) or die('Faield to connect Database');

    return $dbc;
}

function connection_service_close($dbc)
{
    if ($dbc != null) {
        mysqli_close($dbc);
    }
}

function connection_service_is_local_enviroment(): bool
{
    $host = $_SERVER['SERVER_NAME'] ?? '';

    return in_array($host, ['localhost', '127.0.0.1']);
}
