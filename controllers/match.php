<?php
namespace Controllers\Match;

use function Models\Match\save as saveMatch;

require('./models/match.php');


function strore(\PDO $pdo): void
{

    $matchDate = $_POST['match-date'];
    $homeTeam = $_POST['home-team'];
    $awayTeam = $_POST['away-team'];
    $homeTeamGoals = $_POST['home-team-goals'];
    $awayTeamGoals = $_POST['away-team-goals'];

    $match = [
        'date' => $matchDate,
        'home-team' => $homeTeam,
        'home-team-goals' => $homeTeamGoals,
        'away-team-goals' => $awayTeamGoals,
        'away-team' => $awayTeam
    ];

    saveMatch($pdo, $match);
    header('Location: index.php');
    exit();
}
