<?php

use App\Router\Router;
use App\Model\Team;
use App\Model\FootMatch;
use App\Model\OpposingClub;
use App\Controller\MatchController;
use App\Controller\TeamController;
use voku\helper\URLify;


if(isset($_POST['formEditMatch'])){
    
    $idMatch = $_POST['formEditMatch']['idMatch'];

    $team = new Team();
    $team->setIdTeam($_POST['formEditMatch']['teamMatch']);

    $teamOpp = new Team();
    $teamOpp->setIdTeam($_POST['formEditMatch']['opponentMatch']);

    $dateMatch = $_POST['formEditMatch']['dateMatch'];
    $city = $_POST['formEditMatch']['city'];
    $scoreTeam = $_POST['formEditMatch']['teamScore'];
    $scoreTeamOpp = $_POST['formEditMatch']['opponentScore'];
   
    $reponse = MatchController::update($idMatch, $dateMatch, $team, $teamOpp,$scoreTeam,$scoreTeamOpp,$city);
    if($reponse === true){
        
        Router::redirect("allMatches",2);
    }

}

$team = new Team();
$team->setIdTeam($infosMatch['teamId']);
$team->setNameTeam($infosMatch['nameTeam']);

$opponentInfos = MatchController::getOpponentInfos($infosMatch['idOpponent']);
$opponentTeam = new Team();
$opponentTeam->setIdTeam($opponentInfos['idTeam']);
$opponentTeam->setNameTeam($opponentInfos['nameTeam']);

$opponent = new OpposingClub($opponentTeam, $opponentInfos['addressOpponent'], $opponentInfos['cityOpponent']);
$opponent->setIdOpposingClub($opponentInfos['idOpponent']);

$dateMatch = new DateTime($infosMatch['dateMatch']);

$matchInst = new FootMatch($dateMatch,$infosMatch['city'], $team, $opponent);
$matchInst->setId($infosMatch['idMatch']);
$matchInst->setTeamScore($infosMatch['teamScore']);
$matchInst->setOpponentScore($infosMatch['opponentScore']);
$formattedDate = $matchInst->getDate()->format('Y-m-d');


$allTeams = TeamController::getAll();
$allTeamsClass = [];
foreach ($allTeams as $Team) {
    $teamInst = new Team();
    $teamInst->setIdTeam($Team['idTeam']);
    $teamInst->setNameTeam($Team['nameTeam']);
    array_push($allTeamsClass, $teamInst);
}

$allOppsClub = MatchController::getAllOpponents($team->getIdTeam());
$allOppsClubClass = [];
foreach ($allOppsClub as $Opps) {
    $teamInst = new Team();
    $teamInst->setIdTeam($Opps['idTeam']);
    $teamInst->setNameTeam($Opps['nameTeam']);
    $oppsInst = new OpposingClub($teamInst, $Opps['addressOpponent'], $Opps['cityOpponent']);
    $oppsInst->setIdOpposingClub($Opps['idOpponent']);
    array_push($allOppsClubClass, $oppsInst);
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
    <a href="../<?= Router::use("allMatches") ?>">Retour</a>
    <h1>Modification d'un match</h1>

    <form action="<?= $id ?>" name="formEditMatch" method="POST" enctype="multipart/form-data" style="display:flex; flex-direction: column; gap:40px">
        <input type="hidden" name="formEditMatch[idMatch]" value="<?= $id ?>">
        <div style="display:flex">
            <label for="dateMatch">Date du match : </label>
            <input type="date" name="formEditMatch[dateMatch]" id="dateMatch"  value="<?= $formattedDate ?>">
        </div>
        <div style="display:flex">
            <label for="city">Ville du match : </label>
            <input type="text" name="formEditMatch[city]" id="city"  value="<?= $matchInst->getCity() ?>">
        </div>
        <div style="display:flex">
            <label for="teamMatch">Équipe 1 : </label>
            <select name="formEditMatch[teamMatch]" id="teamMatch">
                <?php 
                echo "<option value='aucun'>-- Choisissez une équipe --</option>";
                foreach($allTeamsClass as $TeamOption){
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
            <label for="teamScore">Score équipe 1</label>
            <input type="number" name="formEditMatch[teamScore]" id="teamScore" value="<?= $matchInst->getTeamScore() ?>">
        </div>
        <div style="display:flex">
            <label for="opponentMatch">Équipe adverse : </label>
            <select name="formEditMatch[opponentMatch]" id="opponentMatch">
            <?php 
                echo "<option value='aucun'>-- Choisissez une équipe --</option>";
                foreach($allOppsClubClass as $oppsOption){
                    if ($oppsOption->getIdOpposingClub() == $opponent->getIdOpposingClub()){
                        echo "<option value='".$oppsOption->getIdOpposingClub()."' selected>".$oppsOption->getOpposingClub()->getNameTeam()."</option>";
                    }else{
                        echo "<option value='".$oppsOption->getIdOpposingClub()."'>".$oppsOption->getOpposingClub()->getNameTeam()."</option>";
                    }                   
                }
                ?>
            </select>
        </div>
        <div style="display:flex">
            <label for="opponentScore">Score équipe adverse</label>
            <input type="number" name="formEditMatch[opponentScore]" id="opponentScore" value="<?= $matchInst->getOpponentScore() ?>">
        </div>
        <button type="submit">Modifier</button>
    </form>
</body>
</html>