<?php


require '../vendor/autoload.php';

use App\Controller\TeamController;
use App\Model\Team;
use App\Router\Router;
use Carbon\Carbon;

$allTeams = TeamController::getAll();
$allTeamsClass = [];
foreach ($allTeams as $team) {
    $teamInst = new Team();
    $teamInst->setIdTeam($team['idTeam']);
    $teamInst->setNameTeam($team['nameTeam']);
    array_push($allTeamsClass, $teamInst);
    
}
?>

<ol>
    <li><a href="<?= Router::use("addTeam") ?>">Ajouter un adversaire</a></li>
    <li><a href="<?= Router::use("welcome") ?>">Retour</a></li>
</ol>

<table>
    <thead>
        <tr>
            <th>Nom de l'équipe</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($allTeamsClass as $item){ ?>
            <tr>
                <td><?= $item->getNameTeam() ?></td>
                <td>
                    <a href="<?= Router::use("editTeam",$item->getIdTeam())?>">Modifier</a>
                    <a href="<?= Router::use("deleteTeam",$item->getIdTeam())?>">Supprimer</a>
                </td>
            </tr>

        <?php 
        }
        ?>
    </tbody>
</table>

