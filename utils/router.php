<?php
$routes = require('./configs/routes.php');

$method = $_SERVER['REQUEST_METHOD'];//GET POST
$methodName = '_'.$method;//_GET _POST
$action = $$methodName['action'] ?? '';
$resource = $$methodName['resource'] ?? '';

$route = array_filter($routes, static function ($r) use ($method, $action, $resource) {
    return $r['method'] === $method
        && $r['action'] === $action
        && $r['resource'] === $resource;
});

if (!$route) {
    header('Location: index.php');
    exit();
}

return reset($route);


/*$routes = require('./configs/routes.php');


$method = $_SERVER['REQUEST_METHOD']; // GET ou POST
$methodName = '_'.$method; //renvoi _GET OU _POST
$action = $$methodName['action'] ?? ''; // les deux $ premier -> pour dire que cest dans une variable et le deuxieme -< la variable elle mm, ce qui donne $_get ou $_post
$resource = $$methodName['resource'] ?? '';
//mettre une chaine vide cm alternative si il nya rien

$route = array_filter($routes, static function ($r) use ($method, $action, $resource ){ //use cest pour recuperer cest variable ci
    return $r['method']=== $method
        && $r['action']=== $action
        && $r['resource']=== $resource;
});

if (!$route){
    // si larray est vide donc si les valeur de au dessus ne renvoi pas tte true
    header('Location: index.php');
    exit();
}

return reset($route);
// parcour larray de routes prned une route à la fois transmettre a une focntion qui permet de fr des comparaison pour voir la quel correspond a dees critere decrit dans la fonction et si renvoi true garde lentré correspondante
*/
