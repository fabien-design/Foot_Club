<?php

namespace App\Model;
use App\Database\Database;

class PlayerHasTeam{

    public function __construct(
        private Player $Player,
        private Team $Team,
        private string $role,
    )
    {
        
    }
 

    public function getRole() : string
    {
            return $this->role;
    }

    public function setRole(string $role) : void
    {
            $this->role = $role;
    }

       
    public function getTeam() : Team
    {
            return $this->Team;
    }

    
    public function setTeam(Team $team) :void
    {
            $this->Team = $team;

    }

    public function getPlayer() : Player
    {
            return $this->Player;
    }
    
    public function setPlayer(Player $player) : void
    {
            $this->Player = $player;

    }

    public static function create(PlayerHasTeam $playerHasTeam): bool 
    {
        $connexion = Database::connect();
        $request = $connexion->prepare('INSERT INTO player_has_team (player_id,team_id, role)  VALUES (:id,:team, :role)');
        $teamId = $playerHasTeam->getTeam()->getIdTeam();
        $playerId = $playerHasTeam->getPlayer()->getId();
        $role = $playerHasTeam->getRole();

        $request->bindValue(":team", $teamId, \PDO::PARAM_INT);
        $request->bindValue(":role", $role, \PDO::PARAM_STR);
        $request->bindValue(":id", $playerId, \PDO::PARAM_INT);
        
        if ($request->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public static function update(PlayerHasTeam $playerHasTeam): bool 
    {
        $connexion = Database::connect();
        $request = $connexion->prepare('UPDATE player_has_team SET team_id= :team, role= :role WHERE player_id = :id');
        $teamId = $playerHasTeam->getTeam()->getIdTeam();
        $playerId = $playerHasTeam->getPlayer()->getId();
        $role = $playerHasTeam->getRole();

        $request->bindValue(":team", $teamId, \PDO::PARAM_INT);
        $request->bindValue(":role", $role, \PDO::PARAM_STR);
        $request->bindValue(":id", $playerId, \PDO::PARAM_INT);
        
        if ($request->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public static function delete(int $idPlayer) : bool
    {
        $connexion = Database::connect();
        
        $selectHasTeam = $connexion->prepare('SELECT * FROM player_has_team WHERE player_id = :id');
        $selectHasTeam->bindParam(':id', $idPlayer);
        $selectHasTeam->execute();
        
        if($selectHasTeam->rowCount() > 0){
            $deleteReq = $connexion->prepare('DELETE FROM player_has_team WHERE player_id = :id');
            $deleteReq->bindParam(':id', $idPlayer);
            $deleteReq->execute();
        }

        return true;

    }

    public static function findPlayerByTeam(int $id) : bool
    {
        $connexion = Database::connect();
        
        $selectHasTeam = $connexion->prepare('SELECT * FROM player_has_team WHERE team_id = :id');
        $selectHasTeam->bindParam(':id', $id);
        $selectHasTeam->execute();
        
        if($selectHasTeam->rowCount() > 0)
        {
           return false;
        }
        else
        {
            return true;  
        }
    }

}