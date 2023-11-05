<?php

namespace App\Model;

use App\Database\Database;

class Player extends Person
{
    private int $id;
    private string $name;
    private string $lastname;
    private string $birthdate;
    private string $picture;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }
    public function getBirthdate(): string
    {
        return $this->birthdate;
    }

    public function setBirthdate(string $birthdate): void
    {
        $this->birthdate = $birthdate;
    }

    public function getId() : int
    {
		return $this->id;
    }

    public function setId(int $id) : static
    {
		$this->id = $id;

		return $this;
    }
	
    public function getPicture() : string
    {
		return $this->picture;
    }

	
    public function setPicture(string $picture) : static
    {
		$this->picture = $picture;
		return $this;
    }

    public static function all() : array
    {
        $connexion = Database::connect();
        $request = $connexion->prepare('SELECT * FROM player p, player_has_team pht, team t WHERE p.idPlayer = pht.player_id AND pht.team_id =  t.idTeam');
        $request->execute();
        return $request->fetchAll();
    }

    public static function find(int $id) : array
    {
        $connexion = Database::connect();
        $request = $connexion->prepare('SELECT * FROM player p, player_has_team pht, team t WHERE p.idPlayer = pht.player_id AND pht.team_id =  t.idTeam AND p.idPlayer = :id');
        $request->bindParam(":id", $id);
        $request->execute();

        $infosPlayer = $request->fetch();

        if ($infosPlayer){
            return $infosPlayer;
        }else{
            return null;
        }
    }

    public static function create(Player $player): ?int 
    {
        $connexion = Database::connect();
        $request = $connexion->prepare('INSERT INTO player (namePlayer, lastnamePlayer, birthdatePlayer, picturePlayer) VALUES (:name, :lastname, :birthdate, :picture);');
        
        $playerName = $player->getName();
        $playerLastname = $player->getLastname();
        $playerBirthdate = $player->getBirthdate();
        $playerPic = $player->getPicture();
        
        $request->bindParam(':name', $playerName);
        $request->bindParam(':lastname', $playerLastname);
        $request->bindParam(':birthdate', $playerBirthdate);
        $request->bindParam(':picture', $playerPic);

        if ($request->execute()) {
            $playerId = $connexion->lastInsertId();
            return $playerId;
        } else {
            return 0;
        }
    }
    public static function update(Player $player): bool
    {
        $connexion = Database::connect();
        
        $playerName = $player->getName();
        $playerLastname = $player->getLastname();
        $playerBirthdate = $player->getBirthdate();
        $playerPic = $player->getPicture();
        if(!empty($PlayerPic)){
            $request = $connexion->prepare('UPDATE player SET namePlayer = :name, lastnamePlayer = :lastname, birthdatePlayer = :birthdate, picturePlayer = :picture WHERE idPlayer = :id');
            $request->bindParam(':picture', $playerPic);
        }else{
            $request = $connexion->prepare('UPDATE player SET namePlayer = :name, lastnamePlayer = :lastname, birthdatePlayer = :birthdate WHERE idPlayer = :id');
        }
        
        $request->bindParam(':name', $playerName);
        $request->bindParam(':lastname', $playerLastname);
        $request->bindParam(':birthdate', $playerBirthdate);
        

        if ($request->execute()) {
            return true;
        } else {
            return false;
        }
    }


    public static function delete(int $id) : bool 
    {
        $connexion = Database::connect();

        $deletePlayer = $connexion->prepare('DELETE FROM player WHERE idPlayer = :id');
        $deletePlayer->bindParam(':id', $id);

        if($deletePlayer->execute()){
            return true;
        }else{
            return false;
        }
    }
}