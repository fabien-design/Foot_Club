<?php

use App\Router\Router;
use App\Model\Player;
use App\Model\Team;
use App\Controller\PlayerController;
use App\Controller\TeamController;
use App\Model\PlayerHasTeam;
use voku\helper\URLify;
if(isset($_SESSION['modif'])){
    var_dump($_SESSION['modif']);
    unset($_SESSION['modif']);
}

if(isset($_POST['formEditPlayer'])){
    $extension = pathinfo($_FILES['formEditPlayer']['name']['picture'], PATHINFO_EXTENSION);
    $picName = URLify::filter($_FILES['formEditPlayer']['name']['picture']) . "." . $extension;
    
    $player = new Player();
    $player->setId($_POST['formEditPlayer']['idPlayer']);
    $player->setName($_POST['formEditPlayer']['namePlayer']);
    $player->setLastname($_POST['formEditPlayer']['lastnamePlayer']);
    $player->setBirthdate($_POST['formEditPlayer']['birthdate']);
    $player->setPicture($picName);
    $team = new Team();
    $team->setIdTeam($_POST['formEditPlayer']['teamPlayer']);

    $role = $_POST['formEditPlayer']['rolePlayer'];
    $playerHasTeam = new PlayerHasTeam($player, $team, $role);

    $reponse = PlayerController::update($playerHasTeam);
    if($reponse === true){
        if(!empty($_FILES['formEditPlayer']['tmp_name']['picture'])){
            move_uploaded_file($_FILES['formEditPlayer']['tmp_name']['picture'], "images/". $player->getPicture());
        }
        Router::redirect(Router::use("allPlayers"),2);
    }else{
        Router::redirect(Router::use("editPlayer", $id),2);
    }
    var_dump($reponse);

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
    <title>Modification</title>
</head>
<body>
    <a href="../<?= Router::use("allPlayers") ?>">Retour</a>
    <h1>Modification du joueur</h1>

    <form action="<?= $id ?>" name="formEditPlayer" method="POST" enctype="multipart/form-data" style="display:flex; flex-direction: column; gap:40px">
        <input type="hidden" name="formEditPlayer[idPlayer]" value="<?= $id ?>">
        <div style="display:flex">
            <label for="namePlayer">Prénom :</label>
            <input type="text" name="formEditPlayer[namePlayer]" id="namePlayer" placeholder="Prénom du joueur" value="<?= $player->getName() ?>">
        </div>
        <div style="display:flex">
            <label for="lastnamePlayer">Nom :</label>
            <input type="text" name="formEditPlayer[lastnamePlayer]" id="lastnamePlayer" placeholder="Nom du joueur" value="<?= $player->getLastname() ?>">
        </div>
        <div style="display:flex">
            <label for="birthdate">Date de naissance :</label>
            <input type="date" name="formEditPlayer[birthdate]" id="birthdate" value="<?= $player->getBirthdate() ?>">
        </div>
        <div style="display:flex ; flex-direction:column">
            <label for="picture">Photo :</label>
            <div style="display:flex">
                <p>Photo actuelle : </p>
                <img src="<?= Router::assets("images", $player->getPicture()) ?>" alt="photo du joueur" style="width:50px; height:50px">
            </div>
            <input type="file" name="formEditPlayer[picture]" id="picture" placeholder="Photo du joueur">
        </div>
        <div style="display:flex">
            <label for="teamPlayer">Équipe du joueur :</label>
            <select name="formEditPlayer[teamPlayer]" id="teamPlayer">
                <?php 
                    foreach($allTeamsClass as $TeamOption){
                        var_dump($TeamOption->getIdTeam());
                        if ($TeamOption->getIdTeam() == $team->getIdTeam()){
                            echo "<option value='".$TeamOption->getIdTeam()."' selected>".$TeamOption->getNameTeam()."</option>";
                        }else{
                            echo "<option value='".$TeamOption->getIdTeam()."'>".$TeamOption->getNameTeam()."</option>";
                        }
                    }
                ?>
            </select>
        </div>
        <div style="display:flex">
            <label for="rolePlayer">Role du joueur :</label>
            <input type="text" name="formEditPlayer[rolePlayer]" id="rolePlayer" placeholder="Role du joueur" value="<?= $playerHasTeam->getRole() ?>">
        </div>
        <button type="submit">Enregistrer</button>
    </form>
</body>
</html>