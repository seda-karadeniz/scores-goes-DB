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
                            order by matches.id;';
    $pdoSt = $connection->query($matchesInfosRequest);

    return $pdoSt->fetchAll();
}