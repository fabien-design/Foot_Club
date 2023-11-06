<?php

require '../config/parameters.php';
require '../vendor/autoload.php';
use App\Router\Router;
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// echo $url;
?>

<ul>
    <li><a href="<?= Router::use("allPlayers") ?>">Liste des joueurs</a></li>
    <li><a href="<?= Router::use("allTeams") ?>">Liste des equipes</a></li>
    <!-- <li><a href="<?= Router::use("allOppsClub") ?>">Liste des adversaires</a></li> -->
    <li><a href="<?= Router::use("allMatches") ?>">Liste des match</a></li>
    <li><a href="<?= Router::use("allStaffs") ?>">Liste des membres du staff</a></li>
</ul>