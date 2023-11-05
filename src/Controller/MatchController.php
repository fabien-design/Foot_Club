<?php
namespace App\Controller;
require '../config/parameters.php';

use App\Model\Team;
use App\Model\FootMatch;
use App\Database\Database;
use App\Model\OpposingClub;
use App\Router\Router;
use Exception;

final readonly class MatchController
{
    public static function index() : void
    {
        include "../src/view/allMatches.php";
    }
    
    public static function getAll() : array
    {
        return FootMatch::all();
    }

    public static function getAllOpponents(int $idTeam) : array
    {
        
        return OpposingClub::FindAllOpponentsByTeamId($idTeam);
    }

    public static function getOpponentInfos(int $id) : array
    {
        return OpposingClub::FindOpponentInfosById($id);
    }

    public static function store($dateMatch,Team $team, Team $teamOpp,  $scoreTeam, $scoreTeamOpp, $city): bool
    {
        
        $dateMatch = new \DateTime($dateMatch);

        $matchCreate = FootMatch::create($team, $teamOpp, $scoreTeam, $scoreTeamOpp, $dateMatch, $city);
        if ($matchCreate === true) {
            $_SESSION['ajout']['success'] = "Ajout du match réussi";
            return true;
            
        } else {
            $_SESSION['ajout']['error'] = "Erreur lors de ajout du match";
            return false;
        }
                     
    }

    public static function create() : void
    {
        include '../src/view/addMatch.php';
    }

    public static function edit(int $id) : void
    {
       
        $infosMatch = FootMatch::find($id);
        if(empty($infosMatch)){
            Router::redirect("allMatches",2);
        }
        
        include '../src/view/editMatch.php';
    }

    public static function update($idMatch ,$dateMatch,Team $team, Team $teamOpp,  $scoreTeam, $scoreTeamOpp, $city) : bool
    {
        if(!empty($idMatch)){

            $dateMatch = new \DateTime($dateMatch);
            $matchUpdate = FootMatch::update($idMatch, $team, $teamOpp, $scoreTeam, $scoreTeamOpp, $dateMatch, $city);
            if ($matchUpdate === true) {
                $_SESSION['modif']['success'] = "Modification de l'équipe réussi";
                return true;
                
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
        // On ne peux pas supprimer un match.
    }

}