<?php

use App\Router\Router;
use App\Model\Team;
use App\Controller\TeamController;
use App\Model\OpposingClub;
use voku\helper\URLify;


if(isset($_POST['formEditTeam'])){
    
    $team = new Team();
    $team->setIdTeam($_POST['formEditTeam']['idTeam']);
    $team->setNameTeam($_POST['formEditTeam']['nameTeam']);
    
    $OppsInfos = new OpposingClub($team,$_POST['formEditTeam']['addressTeam'],$_POST['formEditTeam']['cityTeam']);
    $OppsInfos->setIdOpposingClub($_POST['formEditTeam']['idOppsClub']);
   
    $reponse = TeamController::update($OppsInfos);
    if($reponse === true){
        Router::redirect(Router::use("allTeams"),2);
    }

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
    <a href="../<?= Router::use("allTeams") ?>">Retour</a>
    <h1>Modification d'une équipe</h1>

    <form action="<?= $id ?>" name="formEditTeam" method="POST" enctype="multipart/form-data" style="display:flex;flex-direction:column;gap: 40px;">
        <input type="hidden" name="formEditTeam[idTeam]" value="<?= $id ?>">
        <input type="hidden" name="formEditTeam[idOppsClub]" value="<?= $oppsClubInfos->getIdOpposingClub() ?>">
        <div style="display:flex">
            <label for="nameTeam">Nom de l'équipe : </label><br />
            <input type="text" name="formEditTeam[nameTeam]" id="nameTeam" placeholder="Nom de l'équipe" value="<?= $team->getNameTeam() ?>">
        </div>
        <div style="display:flex">
            <label for="cityTeam">Ville de l'équipe</label>
            <input type="text" name="formEditTeam[cityTeam]" id="cityTeam" placeholder="Ville de l'équipe" value="<?= $oppsClubInfos->getCityOpposingClub() ?>">
        </div>
        <div style="display:flex">
            <label for="addressTeam">Adresse de l'équipe</label>
            <input type="text" name="formEditTeam[addressTeam]" id="addressTeam" placeholder="Adresse de l'équipe" value="<?= $oppsClubInfos->getAddressOpposingClub() ?>">
        </div>
        <button type="submit">Modifier</button>
    </form>
</body>
</html>