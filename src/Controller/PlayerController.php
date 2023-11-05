<?php

namespace App\Controller;
require '../config/parameters.php';

use App\Model\Player;
use App\Model\Team;
use App\Model\PlayerHasTeam;
use App\Database\Database;
use App\Router\Router;
use Exception;

final readonly class PlayerController
{

    public static function index(): void {

        include "../src/view/allPlayers.php";
    }

    public static function getAll() : array
    {
        return Player::all();
    }

    public static function store(PlayerHasTeam $playerHasTeam): bool
    {   
        $player = $playerHasTeam->getPlayer();
        $teamId = $playerHasTeam->getTeam()->getIdTeam();
        $role = $playerHasTeam->getRole();
        if(!empty($player->getName())){
           $playerId = Player::create($player);

            if ($playerId !== 0) {
                $playerHasTeam->getPlayer()->setId($playerId);
                $playerHasTeamCreate = PlayerHasTeam::create($playerHasTeam);

                
                if ($playerHasTeamCreate === true) {
                    $_SESSION['ajout']['success'] = "Ajout du joueur réussi";
                    return true;
                } else {
                    $_SESSION['ajout']['error'] = "Erreur lors de l'ajout des infos d'équipe";
                    return false;
                }
                
            } else {
                $_SESSION['ajout']['error'] = "Erreur lors de ajout du joueur";
                return false;
            }
            
        }else{
            $_SESSION['ajout']['error'] = "Erreur des informations du joueur donné";
            return false;
        }           
      
    }

    public static function create() : void
    {
        include '../src/view/addPlayer.php';
    }

    public static function edit(int $id) : void 
    {

        $infosPlayer = Player::find($id);
        if(empty($infosPlayer)){
            Router::redirect(Router::use('allPlayers'),2);
        }
        $player = new Player();
        $player->setId($id);
        $player->setName($infosPlayer['namePlayer']);
        $player->setLastname($infosPlayer['lastnamePlayer']);
        $player->setBirthdate($infosPlayer['birthdatePlayer']);
        $player->setPicture($infosPlayer['picturePlayer']);

        $team = new Team();
        $team->setIdTeam($infosPlayer['idTeam']);
        $team->setNameTeam($infosPlayer['nameTeam']);

        $playerHasTeam = new PlayerHasTeam($player, $team, $infosPlayer['role']);

        include '../src/view/editPlayer.php';
    }

    public static function update(PlayerHasTeam $playerHasTeam) : ?bool
    {
        $player = $playerHasTeam->getPlayer();
        $teamId = $playerHasTeam->getTeam()->getIdTeam();
        $role = $playerHasTeam->getRole();
        
        if(!empty($player->getId())){
            
            $playerUpdate = Player::update($player);
            
            if ($playerUpdate === true) {
                $_SESSION['modif']['success'] = "Ajout du joueur réussi";
                
            } else {
                $_SESSION['modif']['error'] = "Erreur lors de modif du joueur";
                return false;
            }
            
            if(!empty($teamId) && !empty($role)){
                $playerHasTeamUpdate = PlayerHasTeam::update($playerHasTeam);
                if ($playerHasTeamUpdate === true) {
                    $_SESSION['modif']['success'] = "Modif du joueur réussi";
                    return true;
                } else {
                    $_SESSION['modif']['error'] = "Erreur lors de la modif des infos d'équipe";
                    return false;
                }

            }else{
                $_SESSION['modif']['error'] = "Une équipe doit être donné au joueur";
                return false;
            }

        }else{
            $_SESSION['modif']['error'] = "Un joueur doit être modifier";
            return false;
        }
    }

    public static function delete(int $id) : void
    {
        $id = (int)$id;
    
        $playerHasTeamDelete = PlayerHasTeam::delete($id);
        if($playerHasTeamDelete === true){
            $playerDelete = Player::delete($id);
            if ($playerDelete === true) {
                $_SESSION['message']['delete'] = 'Le joueur a été supprimé avec succès.';
            } else {
                $_SESSION['message']['delete'] = 'La suppression du joueur a échoué.';
            }
        }else{
            $_SESSION['message']['delete'] = 'La suppression du joueur dans son équipe a échoué.';
        }

        Router::redirect(Router::use('allPlayers'),2);
       
    }

}