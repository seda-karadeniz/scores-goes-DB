<?php
namespace Match;

function all(\PDO $connection): array
{

    $matchesRequest = 'SELECT * FROM matches order by date ';
    $pdoSt = $connection->query($matchesRequest);

    return $pdoSt->fetchAll();
}

function find(\PDO $connection, string $id): \stdClass
{
    $matchRequest = 'SELECT * FROM matches WHERE id = :id';
    $pdoSt = $connection->prepare($matchRequest);
    $pdoSt->execute([':id' => $id]);

    return $pdoSt->fetch();
}

function allWithTeams(\PDO $connection): array{
    $matchesInfosRequest = 'select  * from matches
                            join participations p on matches.id = p.match_id
                            join teams t on p.team_id = t.id
                            order by matches.id, is_home;';
    $pdoSt = $connection->query($matchesInfosRequest);

    return $pdoSt->fetchAll();
}

function allWithTeamsGrouped(array $allWithTeams):array{
    $matchesWithTeams = [];
    $m = null;
    foreach ($allWithTeams as $match){
        if (!$match->is_home){
            $m= new \stdClass();
            $d= new \DateTime();
            $d->setTimestamp(((int) $match->date)/1000);
            /*/1000 car 3 chiffre en trop */
            $m->match_date = $d;
            $m->away_team = $match->name;
            $m->away_team_goals = $match->goals;

        }
        else{
            $m->home_team = $match->name;
            $m->home_team_goals = $match->goals;
            $matchesWithTeams[]= $m;
        }

    }

    return $matchesWithTeams;
}