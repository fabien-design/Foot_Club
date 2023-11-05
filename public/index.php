<?php

require "../vendor/autoload.php";

use App\Router\Router;

// Instantiate the Router
$router = new Router($_GET['url']);

$router->get("/", "App\Controller\HomeController@index", "welcome");
$router->get("/allPlayers", "App\Controller\PlayerController@index", "allPlayers");
$router->get("/allTeams", "App\Controller\TeamController@index", "allTeams");
$router->get("/allOppsClub", "App\Controller\OpposingClubController@index", "allOppsClub");
$router->get("/allMatches", "App\Controller\MatchController@index", "allMatches");
$router->get("/allStaffs", "App\Controller\StaffController@index", "allStaffs");

$router->get("/addPlayers", "App\Controller\PlayerController@create", "addPlayers");
$router->post("/addPlayers", "App\Controller\PlayerController@create", "addPlayers");
$router->get("/editPlayer/{id}", "App\Controller\PlayerController@edit", "editPlayer"); // {id} est l'endroit ou va etre mis l'id 
$router->post("/editPlayer/{id}", "App\Controller\PlayerController@edit", "editPlayer"); 
$router->get("/deletePlayer/{id}", "App\Controller\PlayerController@delete", "deletePlayer");

$router->get("/addTeam", "App\Controller\TeamController@create", "addTeam");
$router->post("/addTeam", "App\Controller\TeamController@create", "addTeam");
$router->get("/editTeam/{id}", "App\Controller\TeamController@edit", "editTeam");
$router->post("/editTeam/{id}", "App\Controller\TeamController@edit", "editTeam"); 
$router->get("/deleteTeam/{id}", "App\Controller\TeamController@delete", "deleteTeam");

$router->get("/addMatch", "App\Controller\MatchController@create", "addMatch");
$router->post("/addMatch", "App\Controller\MatchController@create", "addMatch");
$router->get("/editMatch/{id}", "App\Controller\MatchController@edit", "editMatch");
$router->post("/editMatch/{id}", "App\Controller\MatchController@edit", "editMatch");
$router->get("/deleteMatch/{id}", "App\Controller\MatchController@delete", "deleteMatch");

$router->get("/addStaff", "App\Controller\StaffController@create", "addStaff");
$router->post("/addStaff", "App\Controller\StaffController@create", "addStaff");
$router->get("/editStaff/{id}", "App\Controller\StaffController@edit", "editStaff");
$router->post("/editStaff/{id}", "App\Controller\StaffController@edit", "editStaff");
$router->get("/deleteStaff/{id}", "App\Controller\StaffController@delete", "deleteStaff");

$router->run();