<?php

use App\Controller\PlayerController;
use App\Controller\TeamController;
use App\Model\Player;
use App\Model\Team;
use App\Model\PlayerHasTeam;
use App\Router\Router;
use voku\helper\URLify;

if(isset($_POST['formAddPlayer'])){
    $extension = pathinfo($_FILES['formAddPlayer']['name']['picture'], PATHINFO_EXTENSION);
    $picName = URLify::filter($_FILES['formAddPlayer']['name']['picture']) . "." . $extension;
    $player = new Player();
    $player->setName($_POST['formAddPlayer']['namePlayer']);
    $player->setLastname($_POST['formAddPlayer']['lastnamePlayer']);
    $player->setBirthdate($_POST['formAddPlayer']['birthdate']);
    $player->setPicture($picName);
    $team = new Team();
    $team->setIdTeam($_POST['formAddPlayer']['teamPlayer']);
    $role = $_POST['formAddPlayer']['rolePlayer'];
    $playerTeam = new PlayerHasTeam($player, $team, $role);
    $store = PlayerController::store($playerTeam);

    if($store === true){
        if(!empty($_FILES['formAddPlayer']['tmp_name']['picture'])){
            move_uploaded_file($_FILES['formAddPlayer']['tmp_name']['picture'], "images/". $player->getPicture());
        }
        Router::redirect(Router::use("allPlayers"), 1);
    }
}  

$allTeams = TeamController::getAll();
$allTeamsClass = [];
foreach ($allTeams as $Team) {
    $teamInst = new Team();
    $teamInst->setIdTeam($Team['idTeam']);
    $teamInst->setNameTeam($Team['nameTeam']);
    array_push($allTeamsClass, $teamInst);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un joueur</title>
</head>
<body>
    <?php
    
    echo "<a href='".Router::use("allPlayers")."'> <- Retour </a>";
    ?>
    <h1>Ajout d'un joueur</h1>
    
    <form action="" name="formAddPlayer" method="POST" enctype="multipart/form-data" style="display:flex; flex-direction: column; gap:40px">
        <div style="display:flex">
            <label for="namePlayer">Prénom :</label>
            <input type="text" name="formAddPlayer[namePlayer]" id="namePlayer" placeholder="Prénom du joueur" >
        </div>
        <div style="display:flex">
            <label for="lastnamePlayer">Nom :</label>
            <input type="text" name="formAddPlayer[lastnamePlayer]" id="lastnamePlayer" placeholder="Nom du joueur">
        </div>
        <div style="display:flex">
            <label for="birthdate">Date de naissance :</label>
            <input type="date" name="formAddPlayer[birthdate]" id="birthdate">
        </div>
        <div style="display:flex ; flex-direction:column">
            <label for="picture">Photo :</label>
            <input type="file" name="formAddPlayer[picture]" id="picture" placeholder="Photo du joueur">
        </div>
        <div style="display:flex">
            <label for="teamPlayer">Équipe du joueur :</label>
            <select name="formAddPlayer[teamPlayer]" id="teamPlayer">
                <?php 
                echo "<option value='aucun'>-- Choisissez une équipe --</option>";
                foreach($allTeamsClass as $TeamOption){
                    
                    echo "<option value='".$TeamOption->getIdTeam()."'>".$TeamOption->getNameTeam()."</option>";
                    
                }
                ?>
            </select>
        </div>
        <div style="display:flex">
            <label for="rolePlayer">Role du joueur :</label>
            <input type="text" name="formAddPlayer[rolePlayer]" id="rolePlayer" placeholder="Role du joueur">
        </div>
        <button type="submit">Ajouter</button>
    </form>
</body>
</html>