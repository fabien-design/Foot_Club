<?php

require '../vendor/autoload.php';

use App\Controller\StaffController;
use App\Model\StaffMember;
use App\Router\Router;
use Carbon\Carbon;

$test = new StaffController();

$allStaffs = StaffController::getAll();
$allStaffsClass = [];
foreach ($allStaffs as $staff) {
    $staffInst = new StaffMember();
    $staffInst->setId($staff['idStaff']);
    $staffInst->setName($staff['nameStaff']);
    $staffInst->setLastname($staff['lastnameStaff']);
    $staffInst->setRole($staff['roleStaff']);
    $staffInst->setPicture($staff['pictureStaff']);
    array_push($allStaffsClass, $staffInst);
}

?>


<ol>
    <li><a href="<?= Router::use("addStaff") ?>">Ajouter un staff</a></li>
    <li><a href="<?= Router::use("welcome") ?>">Retour</a></li>
</ol>

<table>
    <thead>
        <tr>
            <th>Prenom</th>
            <th>Nom de Famille</th>
            <th>Role</th>
            <th>Photo</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($allStaffsClass as $staff) {
        ?>
            <tr>
                <td><?= $staff->getName() ?></td>
                <td><?= $staff->getLastname() ?></td>
                <td><?= $staff->getRole() ?></td>
                <td><?= $staff->getPicture() ?></td>
                <td>
                    <a href="<?= Router::use("editStaff", $staff->getId()) ?>">Modifier</a>
                    <a href="<?= Router::use("deleteStaff", $staff->getId()) ?>">Supprimer</a>
                </td>
            </tr>

        <?php
        }
        ?>
    </tbody>
</table>