<?php

namespace App\Model;

use App\Database\Database;
use DateTime;

class FootMatch
{ //Pascal Case
    private int $id;
    private int $teamScore;
    private int $opponentScore;

    public function __construct(

        private \DateTime $date,
        private string $city,
        private Team $team,
        private OpposingClub $opponent,
    ) {
    }


    public function getId(): int
    {
        return $this->id;
    }


    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getTeamScore(): ?int
    {
        return $this->teamScore;
    }


    public function setTeamScore(?int $teamScore = null): static
    {
        $this->teamScore = $teamScore;
        return $this;
    }

    public function getOpponentScore(): ?int
    {
        return $this->opponentScore;
    }


    public function setOpponentScore(?int $opponentScore = null): static
    {
        $this->opponentScore = $opponentScore;
        return $this;
    }


    public function getDate(): \DateTime
    {
        return $this->date;
    }


    public function setDate(\DateTime $date): static
    {
        $this->date = $date;
        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;
        return $this;
    }

    public function getTeam(): Team
    {
        return $this->team;
    }

    public function setTeam(Team $team): static
    {
        $this->team = $team;
        return $this;
    }

    public function getOpponent(): OpposingClub
    {
        return $this->opponent;
    }

    public function setOpponent(OpposingClub $opponent): static
    {
        $this->opponent = $opponent;
        return $this;
    }

    public static function all(): array
    {
        $connexion = Database::connect();
        $request = $connexion->prepare('SELECT * FROM footmatch fm, team t, opposing_club oc WHERE fm.teamId = t.idTeam AND fm.opponentId = oc.idOpponent');
        $request->execute();
        return $request->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $connexion = Database::connect();
        $request = $connexion->prepare('SELECT * FROM footmatch fm, team t, opposing_club oc WHERE fm.teamId = t.idTeam AND fm.opponentId = oc.idOpponent AND idMatch = :id');
        $request->bindParam(":id", $id);
        $request->execute();
        $infos = $request->fetch();
        if ($infos) {
            return $infos;
        } else {
            return null;
        }
    }

    public static function findMatchByTeam(int $id): bool
    {
        $connexion = Database::connect();

        $selectHasTeam = $connexion->prepare('SELECT * FROM footmatch WHERE teamId = :id');
        $selectHasTeam->bindParam(':id', $id);
        $selectHasTeam->execute();

        if ($selectHasTeam->rowCount() > 0) {
            return false;
        } else {
            return true;
        }
    }

    public static function create(Team $team, Team $teamOpp, int $teamScore, int $opponentScore, DateTime $dateMatch, string $city): bool
    {
        $connexion = Database::connect();

        $dateFormatted = $dateMatch->format('Y-m-d');

        $createMatch = $connexion->prepare('INSERT INTO footmatch (teamId, opponentId, teamScore, opponentScore, dateMatch,city) VALUES (:teamId, :OppId, :teamScore, :opponentScore, :dateMatch, :city)');
        $createMatch->bindParam(':teamId', $team->getIdTeam());
        $createMatch->bindParam(':OppId', $teamOpp->getIdTeam());
        $createMatch->bindParam(':teamScore', $teamScore);
        $createMatch->bindParam(':opponentScore', $opponentScore);
        $createMatch->bindParam(':dateMatch', $dateFormatted);
        $createMatch->bindParam(':city', $city);

        return $createMatch->execute();
    }

    public static function update(int $matchId, Team $team, Team $teamOpp, int $teamScore, int $opponentScore, DateTime $dateMatch, string $city): bool
    {
        $connexion = Database::connect();

        $dateFormatted = $dateMatch->format('Y-m-d');

        $updateMatch = $connexion->prepare('UPDATE footmatch SET teamId = :teamId, opponentId = :OppId, teamScore = :teamScore, opponentScore = :opponentScore, dateMatch = :dateMatch, city = :city WHERE idMatch = :matchId');
        $matchId = intval($matchId);
        $updateMatch->bindParam(':matchId', $matchId);
    
        $teamId = $team->getIdTeam();
        $updateMatch->bindParam(':teamId', $teamId);
        
        $OppId = $teamOpp->getIdTeam();
        $updateMatch->bindParam(':OppId', $OppId);
        
        $updateMatch->bindParam(':teamScore', $teamScore);
        $updateMatch->bindParam(':opponentScore', $opponentScore);
        $updateMatch->bindParam(':dateMatch', $dateFormatted);
        $updateMatch->bindParam(':city', $city);

        return $updateMatch->execute();
    }
}
