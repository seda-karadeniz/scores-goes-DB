<?php

use function Match\allWithTeams as allMatchesWithTeams;
use function Match\allWithTeamsGrouped as allMatchesWithTeamsGrouped;
use function Team\all as allTeam;

require('./configs/config.php');
require('./utils/dbaccess.php');
require ('./utils/standings.php');
require('./models/team.php');
require('./models/match.php');

$pdo = getConnection();


if ($_SERVER['REQUEST_METHOD'] === 'POST'){


    if (isset($_POST['action']) && isset($_POST['resource'])) {
        if ($_POST['action'] === 'store' && $_POST['resource'] === 'match') {
            $matchDate = $_POST['match-date'];
            $homeTeam = $_POST['home-team-unlisted'] === '' ? $_POST['home-team'] : $_POST['home-team-unlisted'];
            $awayTeam = $_POST['away-team-unlisted'] === '' ? $_POST['away-team'] : $_POST['away-team-unlisted'];
            $homeTeamGoals = $_POST['home-team-goals'];
            $awayTeamGoals = $_POST['away-team-goals'];

            $match = [$matchDate, $homeTeam, $homeTeamGoals, $awayTeamGoals, $awayTeam];

            //ajouter dans la db
        }
    }

}

elseif ($_SERVER['REQUEST_METHOD']=== 'GET'){
   // traiter les truc en get
    if (!isset($_GET['action']) && !isset($_GET['resource'])){
//homepage
        $standings = [];

        $matches2 = allMatchesWithTeamsGrouped(allMatchesWithTeams($pdo));
        $teams = allTeam($pdo);


        /*
        $handle = fopen(FILE_PATH, 'r');
        $headers = fgetcsv($handle, 1000);*/
        foreach ($matches2 as $match){

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
            $standings[$homeTeam]['GA'] += $match->away_team_goals;
            $standings[$awayTeam]['GF'] += $match->away_team_goals;
            $standings[$awayTeam]['GA'] += $match->home_team_goals;
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
}
else{
    header('Location: index.php'); /*rediriger vers la page d'accueil*/
    exit();
}


require('vue.php');
