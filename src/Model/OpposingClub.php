<?php

namespace App\Model;
use App\Database\Database;

class OpposingClub{
    private int $idOpposingClub;
    public function __construct(
        private Team $opposingClub,
        private string $addressOpposingClub,
        private string $cityOpposingClub
    )
    {
        
    }

    public function getOpposingClub() : Team
    {
		return $this->opposingClub;
    }
	
    public function setOpposingClub(Team $opposingClub) : static
    {
		$this->opposingClub = $opposingClub;

		return $this;
    }
	
    public function getAddressOpposingClub() : string
    {
		return $this->addressOpposingClub;
    }
	
    public function setAddressOpposingClub(string $addressOpposingClub) : static
    {
		$this->addressOpposingClub = $addressOpposingClub;

		return $this;
    }
	
    public function getCityOpposingClub() : string
    {
		return $this->cityOpposingClub;
    }

	
    public function setCityOpposingClub(string $cityOpposingClub) : static
    {
		$this->cityOpposingClub = $cityOpposingClub;

		return $this;
    }


	
    public function getIdOpposingClub() : int
    {
		return $this->idOpposingClub;
    }

	
    public function setIdOpposingClub(int $idOpposingClub) : static
    {
		$this->idOpposingClub = $idOpposingClub;

		return $this;
    }

    public static function create(OpposingClub $oppsInfos) : bool
    {
        $connexion = Database::connect();

        $createOpps = $connexion->prepare('INSERT INTO opposing_club (addressOpponent, cityOpponent, idOpponentTeam) VALUES (:address, :city, :idTeam)');
        $oppsAddress = $oppsInfos->getAddressOpposingClub();
        $oppsCity = $oppsInfos->getCityOpposingClub();
        $oppsTeamId = $oppsInfos->getOpposingClub()->getIdTeam();
        $createOpps->bindParam(':idTeam', $oppsTeamId);
        $createOpps->bindParam(':address', $oppsAddress);
        $createOpps->bindParam(':city', $oppsCity);

        return $createOpps->execute();

    }

    public static function update(OpposingClub $oppsInfos) : bool
    {
        $connexion = Database::connect();

        $createOpps = $connexion->prepare('UPDATE opposing_club SET addressOpponent = :address, cityOpponent = :city WHERE idOpponent = :id');
        $oppsAddress = $oppsInfos->getAddressOpposingClub();
        $oppsCity = $oppsInfos->getCityOpposingClub();
        $oppsId = $oppsInfos->getIdOpposingClub();
        $createOpps->bindParam(':id', $oppsId);
        $createOpps->bindParam(':address', $oppsAddress);
        $createOpps->bindParam(':city', $oppsCity);

        return $createOpps->execute();

    }

    public static function deleteByTeamId(int $id) : bool 
    {
        $connexion = Database::connect();
        $selectOppsClub = $connexion->prepare('SELECT * FROM opposing_club WHERE idOpponentTeam = :id');
        $selectOppsClub->bindParam(':id', $id);
        $selectOppsClub->execute();
        
        if($selectOppsClub->rowCount() > 0){
            $deleteReq = $connexion->prepare('DELETE FROM opposing_club WHERE idOpponentTeam = :id');
            $deleteReq->bindParam(':id', $id);
            return $deleteReq->execute();
            
        }
        else
        {
            return true;
        }

    }

    public static function FindAllOpponentsByTeamId(int $idTeam) : ?array
    {
        $connexion = Database::connect();
        $request = $connexion->prepare('SELECT * FROM opposing_club oc, team t WHERE oc.idOpponentTeam = t.idTeam AND t.idTeam != :id');
        $request->bindParam(":id",$idTeam);
        $request->execute();
        return $request->fetchAll();
    }

    public static function FindOpponentInfosById(int $id) : ?array
    {
        $connexion = Database::connect();
        $request = $connexion->prepare('SELECT * FROM opposing_club oc, team t WHERE oc.idOpponentTeam = t.idTeam AND oc.idOpponent = :id ');
        $request->bindParam(":id",$id);
        $request->execute();
        return $request->fetch();
    }


}