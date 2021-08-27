<?php
namespace Controllers\Page;
require('./models/match.php');
require('./models/team.php');
require ('./utils/standings.php');

function dashboard(\PDO $pdo)
{
    $standings = [];
    $matches = \Models\Match\allWithTeamsGrouped(\Models\Match\allWithTeams($pdo));
    $teams = \Models\Team\all($pdo);
    $view = './views/view.php';


    foreach ($matches as $match) {
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

    uasort($standings, static function ($a, $b) {
        if ($a['points'] === $b['points']) {
            return 0;
        }
        return $a['points'] > $b['points'] ? -1 : 1;
    });
    return compact('standings', 'matches', 'teams', 'view');
}