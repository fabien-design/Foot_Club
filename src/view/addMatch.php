<?php
session_start();
use App\Router\Router;
use App\Model\Team;
use App\Model\FootMatch;
use App\Model\OpposingClub;
use App\Controller\MatchController;
use App\Controller\TeamController;
use voku\helper\URLify;


if (isset($_POST['formAddMatch'])) {
    $erreurs = [];
    if (!empty($_POST['formAddMatch']['teamMatch']) && $_POST['formAddMatch']['teamMatch'] !== "aucun") {
        $team = new Team();
        $team->setIdTeam(intval($_POST['formAddMatch']['teamMatch']));
    } else {
        $erreurs['team1'] = "Choisissez une équipe";
    }

    if (!empty($_POST['formAddMatch']['opponentMatch']) && $_POST['formAddMatch']['opponentMatch'] !== "aucun") {
        $teamOpp = new Team();
        $teamOpp->setIdTeam(intval($_POST['formAddMatch']['opponentMatch']));
    } else {
        $erreurs['team2'] = "Choisissez un adversaire";
    }
    if (!empty($_POST['formAddMatch']['dateMatch'])) {
        $dateMatch = $_POST['formAddMatch']['dateMatch'];
    } else {
        $erreurs['dateMatch'] = "Veuillez saisir la date du match";
    }

    if (!empty($_POST['formAddMatch']['dateMatch'])) {
        $city = $_POST['formAddMatch']['city'];
    } else {
        $erreurs['city'] = "Veuillez saisir le lieu du match";
    }
    if (isset($_POST['formAddMatch']['teamScore']) && $_POST['formAddMatch']['teamScore'] >= 0) {
        $scoreTeam = $_POST['formAddMatch']['teamScore'];
    } else {
        $erreurs['scoreTeam'] = "Saisissez le score de l'équipe";
    }
    if (isset($_POST['formAddMatch']['opponentScore']) && $_POST['formAddMatch']['opponentScore'] >= 0) {
        $scoreTeamOpp = $_POST['formAddMatch']['opponentScore'];
    } else {
        $erreurs['scoreOpponent'] = "Saisissez le score de l'adversaire";
    }

    if (empty($erreurs)) {
        $reponse = MatchController::store($dateMatch, $team, $teamOpp, $scoreTeam, $scoreTeamOpp, $city);

        if ($reponse === true) {
            Router::redirect("allMatches", 1);
        } else {
            var_dump($reponse);
        }
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

$allOppsClub = TeamController::getAll();
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
    <title>Ajout</title>
</head>

<body>
    <a href="<?= Router::use("allMatches") ?>">Retour</a>
    <h1>Ajout d'un match</h1>

    <form name="formAddMatch" method="POST" enctype="multipart/form-data" style="display:flex; flex-direction: column; gap:40px">
        <div style="display:flex">
            <label for="dateMatch">Date du match : </label>
            <input type="date" name="formAddMatch[dateMatch]" id="dateMatch">
            <?= isset($erreurs['dateMatch']) ? "<p style='color:red'>" . $erreurs['dateMatch'] . "</p>" : "" ?>
        </div>
        <div style="display:flex">
            <label for="city">Ville du match : </label>
            <input type="text" name="formAddMatch[city]" id="city">
            <?= isset($erreurs['city']) ? "<p style='color:red'>" . $erreurs['city'] . "</p>" : "" ?>
        </div>
        <div style="display:flex">
            <label for="teamMatch">Équipe 1 : </label>
            <select name="formAddMatch[teamMatch]" id="teamMatch">
                <?php
                echo "<option value='aucun'>-- Choisissez une équipe --</option>";
                foreach ($allTeamsClass as $TeamOption) {
                    echo "<option value='" . $TeamOption->getIdTeam() . "'>" . $TeamOption->getNameTeam() . "</option>";
                }
                ?>
            </select>
            <?= isset($erreurs['team1']) ? "<p style='color:red'>" . $erreurs['team2'] . "</p>" : "" ?>
        </div>
        <div style="display:flex">
            <label for="teamScore">Score équipe 1</label>
            <input type="number" name="formAddMatch[teamScore]" id="teamScore" value="0">
            <?= isset($erreurs['scoreTeam']) ? "<p style='color:red'>" . $erreurs['scoreTeam'] . "</p>" : "" ?>
        </div>
        <div style="display:flex">
            <label for="opponentMatch">Équipe adverse : </label>
            <select name="formAddMatch[opponentMatch]" id="opponentMatch">
                <?php
                echo "<option value='aucun'>-- Choisissez une équipe --</option>";
                foreach ($allOppsClubClass as $oppsOption) {
                    echo "<option value='" . $oppsOption->getIdOpposingClub() . "'>" . $oppsOption->getOpposingClub()->getNameTeam() . "</option>";
                }
                ?>
            </select>
            <?= isset($erreurs['team2']) ? "<p style='color:red'>" . $erreurs['team2'] . "</p>" : "" ?>
        </div>
        <div style="display:flex">
            <label for="opponentScore">Score équipe adverse</label>
            <input type="number" name="formAddMatch[opponentScore]" id="opponentScore" value="0">
            <?= isset($erreurs['scoreOpponent']) ? "<p style='color:red'>" . $erreurs['scoreOpponent'] . "</p>" : "" ?>
        </div>
        <button type="submit">Ajouter</button>
    </form>
</body>

</html>