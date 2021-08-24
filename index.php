<?php

use function Models\Match\allWithTeams as allMatchesWithTeams;
use function Models\Match\allWithTeamsGrouped as allMatchesWithTeamsGrouped;
use function Models\Match\save as saveMatch;
use function Models\Team\all as allTeams;
use function Controllers\Match\strore as stroreMatch;

require ('./vendor/autoload.php');

require('./configs/config.php');
require('./utils/dbaccess.php');
require ('./utils/standings.php');
require('./models/team.php');
require('./models/match.php');
require('./controllers/match.php');


$pdo = getConnection();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['resource'])) {
        if ($_POST['action'] === 'store' && $_POST['resource'] === 'match') {
            stroreMatch($pdo);
        }
    }
}

elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['action']) && !isset($_GET['resource'])) {
        // Home page
        $standings = [];
        $matches2 = allMatchesWithTeamsGrouped(allMatchesWithTeams($pdo));
        $teams = allTeams($pdo);


        foreach ($matches2 as $match) {
            $homeTeam = $match->home_team;
            $awayTeam = $match->away_team;
            if (!array_key_exists($homeTeam, $standings)) {
                $standings[$homeTeam] = getEmptyStatsArray();
            }
            if (!array_key_exists($awayTeam, $standings)) {
                $standings[$awayTeam] = getEmptyStatsArray();
            }
            $standings[$homeTeam]['games']++;
            $standings[$awayTeam]['games']++;

            if ($match->home_team_goals === $match->away_team_goals) {
                $standings[$homeTeam]['points']++;
                $standings[$awayTeam]['points']++;
                $standings[$homeTeam]['draws']++;
                $standings[$awayTeam]['draws']++;
            } elseif ($match->home_team_goals > $match->away_team_goals) {
                $standings[$homeTeam]['points'] += 3;
                $standings[$homeTeam]['wins']++;
                $standings[$awayTeam]['losses']++;
            } else {
                $standings[$awayTeam]['points'] += 3;
                $standings[$awayTeam]['wins']++;
                $standings[$homeTeam]['losses']++;
            }
            $standings[$homeTeam]['GF'] += $match->home_team_goals;
            $standings[$homeTeam]['GA'] += $match->home_team_goals;
            $standings[$awayTeam]['GA'] += $match->away_team_goals;
            $standings[$awayTeam]['GF'] += $match->away_team_goals;
            $standings[$homeTeam]['GD'] = $standings[$homeTeam]['GF'] - $standings[$homeTeam]['GA'];
            $standings[$awayTeam]['GD'] = $standings[$awayTeam]['GF'] - $standings[$awayTeam]['GA'];

        }

        uasort($standings, function ($a, $b) {
            if ($a['points'] === $b['points']) {
                return 0;
            }
            return $a['points'] > $b['points'] ? -1 : 1;
        });
    }
} else {
    header('Location: index.php');
    exit();
}


require('vue.php');
