<?php


require '../vendor/autoload.php';

use App\Controller\TeamController;
use App\Model\Team;
use App\Model\OpposingClub;
use App\Router\Router;
use Carbon\Carbon;

$allTeams = TeamController::getAll();
$allOpposingClass = [];
foreach ($allTeams as $team) {
    $teamInst = new Team();
    $teamInst->setIdTeam($team['idTeam']);
    $teamInst->setNameTeam($team['nameTeam']);

    $oppClub = new OpposingClub($teamInst, $team["addressOpponent"], $team['cityOpponent']);

    array_push($allOpposingClass, $oppClub);

}
?>

<ol>
    <li><a href="<?= Router::use("addTeam") ?>">Ajouter une équipe</a></li>
    <li><a href="<?= Router::use("welcome") ?>">Retour</a></li>
</ol>

<table>
    <thead>
        <tr>
            <th>Nom de l'équipe</th>
            <th>Ville de l'équipe</th>
            <th>addresse de l'équipe</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($allOpposingClass as $item){ ?>
            <tr>
                <td><?= $item->getOpposingClub()->getNameTeam() ?></td>
                <td><?= $item->getCityOpposingClub() ?></td>
                <td><?= $item->getAddressOpposingClub() ?></td>
                <td>
                    <a href="<?= Router::use("editTeam",$item->getOpposingClub()->getIdTeam())?>">Modifier</a>
                    <a href="<?= Router::use("deleteTeam",$item->getOpposingClub()->getIdTeam())?>">Supprimer</a>
                </td>
            </tr>

        <?php 
        }
        ?>
    </tbody>
</table>

