<?php


require '../vendor/autoload.php';

use App\Controller\TeamController;
use App\Controller\MatchController;
use App\Model\FootMatch;
use App\Model\Team;
use App\Model\OpposingClub;
use App\Router\Router;
use Carbon\Carbon;

$allMatches = MatchController::getAll();
$allMatchesClass = [];
foreach ($allMatches as $match) {
    $team = new Team();
    $team->setIdTeam($match['teamId']);
    $team->setNameTeam($match['nameTeam']);

    $opponentInfos = MatchController::getOpponentInfos($match['idOpponent']);
    $opponentTeam = new Team();
    $opponentTeam->setIdTeam($opponentInfos['idTeam']);
    $opponentTeam->setNameTeam($opponentInfos['nameTeam']);
    $opponent = new OpposingClub($opponentTeam, $opponentInfos['addressOpponent'], $opponentInfos['cityOpponent']);

    $dateMatch = new DateTime($match['dateMatch']);
    $matchInst = new FootMatch($dateMatch,$match['city'], $team, $opponent);
    $matchInst->setId($match['idMatch']);
    $matchInst->setTeamScore($match['teamScore']);
    $matchInst->setOpponentScore($match['opponentScore']);
    array_push($allMatchesClass, $matchInst);
    
}
?>

<ol>
    <li><a href="<?= Router::use("addMatch") ?>">Ajouter un match</a></li>
    <li><a href="<?= Router::use("welcome") ?>">Retour</a></li>
</ol>

<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Ville</th>
            <th>Equipe 1</th>
            <th>Score equipe 1</th>
            <th>Equipe 2</th>
            <th>Score equipe 2</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($allMatchesClass as $item){ ?>
            <tr>
                <td><?= Carbon::parse($item->getDate())->locale("fr_FR")->isoFormat("D MMMM Y") ?></td>
                <td><?= $item->getCity() ?></td>
                <td><?= $item->getTeam()->getNameTeam() ?></td>
                <td><?= $item->getTeamScore() ?? "aucun" ?></td>
                <td><?= $item->getOpponent()->getOpposingClub()->getNameTeam() ?></td>
                <td><?= $item->getOpponentScore() ?></td>
                <td>
                    <a href="<?= Router::use("editMatch",$item->getId())?>">Modifier</a>
                </td>
            </tr>
        <?php 
        }
        ?>
    </tbody>
</table>

