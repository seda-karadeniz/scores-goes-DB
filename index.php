<?php


use function Controllers\Match\strore as stroreMatch;
use function Controllers\Team\strore as stroreTeam;

require ('./vendor/autoload.php');

require('./configs/config.php');
require('./utils/dbaccess.php');
require ('./utils/standings.php');



$pdo = getConnection();
$route = require('./utils/router.php');

require('./controllers/'.$route['controller-file'].'.php');

$data = call_user_func($route['callback'], $pdo);

extract($data, EXTR_OVERWRITE);

require($view);




/*
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['resource'])) {
        if ($_POST['action'] === 'store' && $_POST['resource'] === 'match') {
            stroreMatch($pdo);
        }elseif($_POST['action'] === 'store' && $_POST['resource'] === 'team'){
            stroreTeam($pdo);
        }
    }
}

elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['action']) && !isset($_GET['resource'])) {
        // Home page
        $data = dashboard($pdo);

    }
} else {
    header('Location: index.php');
    exit();
}*/