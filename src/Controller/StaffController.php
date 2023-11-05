<?php

namespace App\Controller;

require '../config/parameters.php';

use App\Model\StaffMember;
use App\Router\Router;

final readonly class StaffController
{

    public static function index(): void
    {

        include "../src/view/allStaffs.php";
    }

    public static function getAll(): array
    {
        return StaffMember::all();
    }

    public static function store(StaffMember $staff): bool
    {
        if (!empty($staff->getName())) {
            $staffCreate = StaffMember::create($staff);

            if ($staffCreate === true) {
                $_SESSION['ajout']['success'] = "Ajout du staff réussi";
                return true;
            } else {
                $_SESSION['ajout']['error'] = "Erreur lors de ajout du staff";
                return false;
            }
        } else {
            $_SESSION['ajout']['error'] = "Erreur des informations du staff donné";
            return false;
        }
    }

    public static function create(): void
    {
        include '../src/view/addStaff.php';
    }

    public static function edit(int $id): void
    {

        $infosStaff = StaffMember::find($id);
        if (empty($infosStaff)) {
            Router::redirect(Router::use('allStaffs'), 2);
        }
        $staff = new StaffMember();
        $staff->setId($id);
        $staff->setName($infosStaff['nameStaff']);
        $staff->setLastname($infosStaff['lastnameStaff']);
        $staff->setRole($infosStaff['roleStaff']);
        $staff->setPicture($infosStaff['pictureStaff']);

        include '../src/view/editStaff.php';
    }

    public static function update(StaffMember $staff): ?bool
    {
        if (!empty($staff->getId())) {

            $staffUpdate = StaffMember::update($staff);

            if ($staffUpdate === true) {
                $_SESSION['modif']['success'] = "Modif du staff réussi";
                return true;
            } else {
                $_SESSION['modif']['error'] = "Erreur lors de la modif du staff";
                return false;
            }
        } else {
            $_SESSION['modif']['error'] = "Un staff doit être modifier";
            return false;
        }
    }

    public static function delete(int $id): void
    {
        $id = (int)$id;
        $staff = StaffMember::find($id);
        if($staff === null){
            $_SESSION['message']['delete'] = 'Le membre du staff n\'a pas été trouvé.';
            Router::redirect(Router::use('allStaffs'), 2);
        }
        
        $staffDelete = StaffMember::delete($id);
        if ($staffDelete === true) {
            $_SESSION['message']['delete'] = 'Le staff a été supprimé avec succès.';
        } else {
            $_SESSION['message']['delete'] = 'La suppression du staff a échoué.';
        }
       
        Router::redirect(Router::use('allStaffs'), 2);
    }
}
