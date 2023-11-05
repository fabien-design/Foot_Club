<?php
namespace App\Controller;
require '../config/parameters.php';

use App\Model\Team;
use App\Database\Database;
use App\Model\FootMatch;
use App\Model\OpposingClub;
use App\Model\PlayerHasTeam;
use App\Router\Router;
use Exception;

final readonly class TeamController
{
    public static function index() : void
    {
        include "../src/view/allTeams.php";
    }
    public static function getAll() : array
    {
        return Team::all();
    }

    public static function store(OpposingClub $oppsInfos): bool
    {
       
        $team = $oppsInfos->getOpposingClub();
        if(!empty($team->getNameTeam())){
            $teamCreateId = Team::create($team);
            if ($teamCreateId !== 0) {
                
                $oppsInfos->getOpposingClub()->setIdTeam($teamCreateId); // ajout de l'id de la nouvelle team pour insert dans opposing_club

                $oppsCreate = OpposingClub::create($oppsInfos);
                
                if ($oppsCreate === true) {
                    $_SESSION['ajout']['success'] = "Ajout de l'équipe réussi";
                    return true;
                }else{
                    $_SESSION['ajout']['error'] = "Erreur lors de l'ajout de la ville et de l'adresse";
                    return false;
                }
                
            } else {
                $_SESSION['ajout']['error'] = "Erreur lors de l'ajout de l'équipe";
                return false;
            }
                     
        }
        return false;
      
    }

    public static function create() : void
    {
        include '../src/view/addTeam.php';
    }

    public static function edit(int $id) : void
    {
        $infosTeam = Team::find($id);
        if(empty($infosTeam)){
           Router::redirect('allTeams',2);
        }
        $team = new Team();
        $team->setIdTeam($id);
        $team->setNameTeam($infosTeam['nameTeam']);

        $oppsClubInfos = new OpposingClub($team, $infosTeam['addressOpponent'], $infosTeam['cityOpponent']);
        $oppsClubInfos->setIdOpposingClub($infosTeam['idOpponent']);

        include '../src/view/editTeam.php';
    }

    public static function update(OpposingClub $oppsInfos) : bool
    {
        $team = $oppsInfos->getOpposingClub();
        if(!empty($team->getIdTeam())){
            $teamUpdate = Team::update($team);

            if ($teamUpdate === true) {

                $oppsUpdate = OpposingClub::update($oppsInfos);
                if ($oppsUpdate === true) {
                    $_SESSION['modif']['success'] = "Modification de l'équipe réussi";
                    return true;
                }else{
                    $_SESSION['modif']['error'] = "Erreur lors de la modif des infos addresse et ville";
                    return false;
                }
                
            } else {
                $_SESSION['modif']['error'] = "Erreur lors de la modif de l'équipe";
                return false;
            }
            
        }else{
            $_SESSION['modif']['error'] = "Une équipe doit être donné pour être modifié";
            return false;
        }
    }

    public static function delete(int $id) : void
    {
        $id = (int)$id;
    
        $teamHasPlayers = PlayerHasTeam::findPlayerByTeam($id);
        if($teamHasPlayers === false){
            $_SESSION['message']['delete'] = 'Des joueurs sont encore dans cette équipe.';
        }else{
            $teamHasMatches = FootMatch::findMatchByTeam($id);
            if($teamHasMatches === false){
                $_SESSION['message']['delete'] = 'Impossible de supprimer une équipe liée à un match ou un joueur.';
            }else{
                
                $OppsDelete = OpposingClub::deleteByTeamId($id);
                if($OppsDelete === true)
                {
                    $teamDelete = Team::delete($id);
                    if ($teamDelete === true) {
                        $_SESSION['message']['delete'] = 'L\'équipe a été supprimé avec succès.';
                    } else {
                        $_SESSION['message']['delete'] = 'La suppression de l\'équipe a échoué.';
                    }
                }
                else{
                    $_SESSION['message']['delete'] = 'La suppression de l\'équipe a échoué. Les infos sur l\'adresse et la ville n\'ont pas été supprimées.';
                }
            }

        
        }


        
        Router::redirect("allTeams",2);
       
    }

}