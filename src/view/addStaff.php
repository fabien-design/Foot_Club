<?php

use App\Controller\StaffController;
use App\Model\StaffMember;
use App\Router\Router;
use voku\helper\URLify;

if(isset($_POST['formAddStaff'])){
    
    $extension = pathinfo($_FILES['formAddStaff']['name']['picture'], PATHINFO_EXTENSION);
    $picName = URLify::filter($_FILES['formAddStaff']['name']['picture']) . "." . $extension;
    $staff = new StaffMember();
    $staff->setName($_POST['formAddStaff']['name']);
    $staff->setLastname($_POST['formAddStaff']['lastname']);
    $staff->setRole($_POST['formAddStaff']['role']);
    $staff->setPicture($picName);
    
    $store = StaffController::store($staff);

    if($store === true){
        if(!empty($_FILES['formAddStaff']['tmp_name']['picture'])){
            move_uploaded_file($_FILES['formAddStaff']['tmp_name']['picture'], "images/". $staff->getPicture());
        }
        Router::redirect(Router::use("allStaffs"), 1);
    }
}  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un staff</title>
</head>
<body>
    <?php
    
    echo "<a href='".Router::use("allStaffs")."'> <- Retour </a>";
    ?>
    <h1>Ajout d'un staff</h1>
    
    <form action="" name="formAddStaff" method="POST" enctype="multipart/form-data" style="display:flex; flex-direction: column; gap:40px">
        <div style="display:flex">
            <label for="name">Prénom :</label>
            <input type="text" name="formAddStaff[name]" id="name" placeholder="Prénom du staff" >
        </div>
        <div style="display:flex">
            <label for="lastname">Nom :</label>
            <input type="text" name="formAddStaff[lastname]" id="lastname" placeholder="Nom du staff">
        </div>
        <div style="display:flex">
            <label for="role">Role du staff :</label>
            <input type="text" name="formAddStaff[role]" id="role" placeholder="Role du staff">
        </div>
        <div style="display:flex ; flex-direction:column">
            <label for="picture">Photo :</label>
            <input type="file" name="formAddStaff[picture]" id="picture" placeholder="Photo du staff">
        </div>
        <button type="submit">Ajouter</button>
    </form>
</body>
</html>