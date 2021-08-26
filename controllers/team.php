<?php

namespace Controllers\Team;

use function Models\Team\save as saveTeam;

require('./models/team.php');


function strore(\PDO $pdo): void
{

    $name = $_POST['name'];
    $slug = $_POST['slug'];
//todo il manque de la validation
    saveTeam($pdo, compact('name', 'slug'));

    header('Location: index.php');
    exit();
}

