<?php

namespace App\Model;

use App\Database\Database;

class StaffMember extends Person
{

    private int $id;
    protected string $name;
    protected string $lastname;
    protected string $picture = '';
    private string $role;


    public function getId(): int
    {
        return $this->id;
    }
    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }
    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPicture(): string
    {
        return $this->picture;
    }
    public function setPicture(string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    public function getRole(): string
    {
        return $this->role;
    }
    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public static function all(): array
    {
        $connexion = Database::connect();
        $request = $connexion->prepare('SELECT * FROM staff_member');
        $request->execute();
        return $request->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $connexion = Database::connect();
        $request = $connexion->prepare('SELECT * FROM staff_member WHERE idStaff = :id');
        $request->bindParam(":id", $id);
        $request->execute();
        $infosStaff = $request->fetch();

        if ($infosStaff) {
            return $infosStaff;
        } else {
            return null;
        }
    }

    public static function create(StaffMember $staff): bool
    {
        $connexion = Database::connect();

        $name = $staff->getName();
        $lastname = $staff->getLastname();
        $picture = $staff->getPicture();
        $role = $staff->getRole();

        $create = $connexion->prepare('INSERT INTO staff_member (nameStaff, lastnameStaff, pictureStaff, roleStaff) VALUES (:nameStaff, :lastnameStaff, :pictureStaff, :roleStaff)');
        $create->bindParam(":nameStaff", $name);
        $create->bindParam(":lastnameStaff", $lastname);
        $create->bindParam(":pictureStaff", $picture);
        $create->bindParam(":roleStaff", $role);

        if ($create->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public static function update(StaffMember $staff): bool
    {
        $connexion = Database::connect();

        $id = $staff->getId();
        $name = $staff->getName();
        $lastname = $staff->getLastname();
        $role = $staff->getRole();
        $picture = $staff->getPicture();

        $update = $connexion->prepare('UPDATE staff_member SET nameStaff = :nameStaff, lastnameStaff = :lastnameStaff, pictureStaff = :pictureStaff, roleStaff = :roleStaff WHERE idStaff = :idStaff');
        $update->bindParam(":idStaff", $id);
        $update->bindParam(":nameStaff", $name);
        $update->bindParam(":lastnameStaff", $lastname);
        $update->bindParam(":roleStaff", $role);
        $update->bindValue(':pictureStaff', $picture);

        if ($update->execute()) {
            return true;
        } else {
            return false;
        }
    }


    public static function delete(int $id): bool
    {
        $connexion = Database::connect();

        $delete = $connexion->prepare("DELETE FROM staff_member WHERE idStaff = :idStaff");
        $delete->bindParam(":idStaff", $id);
        if ($delete->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
