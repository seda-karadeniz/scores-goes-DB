<?php
namespace Models\Team;

function all(\PDO $connection): array /*mettre lanti slash car recherche pdo dans lespace de nom or il doit le rechercher dans la racine*/
{
    $teamsRequest = 'SELECT * FROM teams order by name ';
    $pdoSt = $connection->query($teamsRequest);

    return $pdoSt->fetchAll();
}

function find(\PDO $connection, string $id): \stdClass
{
    $teamRequest = 'SELECT * FROM teams WHERE id = :id';
    $pdoSt = $connection->prepare($teamRequest);
    $pdoSt->execute([':id' => $id]);

    return $pdoSt->fetch();
}


function findByName(\PDO $connection, string $name): \stdClass
{
    $teamRequest = 'SELECT * FROM teams WHERE name = :name';
    $pdoSt = $connection->prepare($teamRequest);
    $pdoSt->execute([':name' => $name]);

    return $pdoSt->fetch();
}