<?php

namespace App\Model;
use App\Database\Database;

class Team
{
    private int $idTeam;
    private string $nameTeam;
        
    public function getIdTeam() : int
    {
            return $this->idTeam;
    }
    public function setIdTeam(int $idTeam) : static
    {
        $this->idTeam = $idTeam;

        return $this;
    }

    public function getNameTeam() : string
    {
            return $this->nameTeam;
    }
 
    public function setNameTeam(string $nameTeam) : void
    {
            $this->nameTeam = $nameTeam;
    }

    public static function all() : array
    {
        $connexion = Database::connect();
        $request = $connexion->prepare('SELECT * FROM team t INNER JOIN opposing_club oc ON t.idTeam = oc.idOpponentTeam');
        $request->execute();
        return $request->fetchAll();
    }

    public static function create(Team $team) : ?int
    {
        $connexion = Database::connect();
        $request = $connexion->prepare('INSERT INTO team (nameTeam) VALUES (:name)');
        $teamName = $team->getNameTeam();
        $request->bindParam(':name', $teamName);
        if($request->execute())
        {
            return $connexion->lastInsertId();
        }
        else{
            return 0;
        }
    }

    public static function find(int $id) : ?array
    {
        $connexion = Database::connect();
        $request = $connexion->prepare('SELECT * FROM team t INNER JOIN opposing_club oc ON t.idTeam = oc.idOpponentTeam AND t.idTeam = :id');
        $request->bindParam(":id", $id);
        $request->execute();

        $infosTeam = $request->fetch();
        if(empty($infosTeam)){
            return null;
        }else{
            return $infosTeam;
        }
    }

    public static function update(Team $team) : ?bool
    {
        $connexion = Database::connect();
        $request = $connexion->prepare('UPDATE team SET nameTeam = :name WHERE idTeam = :id');
        $teamId = $team->getIdTeam();
        $teamName = $team->getNameTeam();
        $request->bindParam(':id', $teamId);
        $request->bindParam(':name', $teamName);

        return $request->execute();
    }

    public static function delete(int $id) : ?bool
    {
        $connexion = Database::connect();
        $deleteTeam = $connexion->prepare('DELETE FROM team WHERE idTeam = :id');
        $deleteTeam->bindParam(':id', $id);
        return $deleteTeam->execute();
    }


}
