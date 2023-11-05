<?php


require '../vendor/autoload.php';

use App\Controller\PlayerController;
use App\Model\Player;
use App\Model\PlayerHasTeam;
use App\Model\Team;
use App\Router\Router;
use Carbon\Carbon;

$test = new PlayerController();

$allPlayers = PlayerController::getAll();

$allPlayersTeamClass = [];
foreach ($allPlayers as $player) {
    $playerInst = new Player();
    $playerInst->setId($player['idPlayer']);
    $playerInst->setName($player['namePlayer']);
    $playerInst->setLastname($player['lastnamePlayer']);
    $playerInst->setBirthdate($player['birthdatePlayer']);
    $playerInst->setPicture($player['picturePlayer']);

    $team = new Team();
    $team->setIdTeam($player['idTeam']);
    $team->setNameTeam($player['nameTeam']);

    $playerHasTeam = new PlayerHasTeam($playerInst, $team, $player['role']);
    array_push($allPlayersTeamClass, $playerHasTeam);
}

?>




<ol>
    <li><a href="<?= Router::use("addPlayers") ?>">Ajouter un joueur</a></li>
    <li><a href="<?= Router::use("welcome") ?>">Retour</a></li>
</ol>

<table>
    <thead>
        <tr>
            <th>Prenom</th>
            <th>Nom de Famille</th>
            <th>Date de naissance</th>
            <th>Photo</th>
            <th>Équipe</th>
            <th>Poste</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach($allPlayersTeamClass as $item){ 
        ?>
            <tr>
                <td><?= $item->getPlayer()->getName() ?></td>
                <td><?= $item->getPlayer()->getLastname() ?></td>
                <td><?= Carbon::parse($item->getPlayer()->getBirthdate())->locale("fr_FR")->isoFormat("D MMMM Y") ?></td>
                <td><?= $item->getPlayer()->getPicture() ?></td>
                <td><?= $item->getTeam()->getNameTeam() ?></td>
                <td><?= $item->getRole() ?></td>
                <td>
                    <a href="<?= Router::use("editPlayer",$item->getPlayer()->getId())?>">Modifier</a>
                    <a href="<?= Router::use("deletePlayer",$item->getPlayer()->getId())?>">Supprimer</a>
                </td>
            </tr>

        <?php 
        }
        ?>
    </tbody>
</table>

