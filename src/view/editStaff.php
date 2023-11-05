<?php

use App\Controller\StaffController;
use App\Model\StaffMember;
use App\Router\Router;
use voku\helper\URLify;

if (isset($_POST['formEditStaff'])) {

    $staff = new StaffMember();
    $staff->setId(intval($_POST['formEditStaff']['idStaff']));
    $staff->setName($_POST['formEditStaff']['name']);
    $staff->setLastname($_POST['formEditStaff']['lastname']);
    $staff->setRole($_POST['formEditStaff']['role']);
    if (!empty($_FILES['formEditStaff']['tmp_name']['picture'])) {
        $extension = pathinfo($_FILES['formEditStaff']['name']['picture'], PATHINFO_EXTENSION);
        $picName = URLify::filter($_FILES['formEditStaff']['name']['picture']) . "." . $extension;
        $staff->setPicture($picName);
    } else {
        $picName = $_POST['formEditStaff']['defaultPicture'];
    }

    $staff->setPicture($picName);

    $store = StaffController::update($staff);

    if ($store === true) {
        if (!empty($_FILES['formEditStaff']['tmp_name']['picture'])) {
            move_uploaded_file($_FILES['formEditStaff']['tmp_name']['picture'], "images/" . $staff->getPicture());
        }
        Router::redirect(Router::use("allStaffs"), 2);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un staff</title>
</head>

<body>
    <?php

    echo "<a href='" . Router::use("allStaffs") . "'> <- Retour </a>";
    ?>
    <h1>Modification d'un staff</h1>

    <form action="<?= $id ?>" name="formEditStaff" method="POST" enctype="multipart/form-data" style="display:flex; flex-direction: column; gap:40px">
        <input type="hidden" name="formEditStaff[idStaff]" value="<?= $id ?>">
        <div style="display:flex">
            <label for="name">Prénom :</label>
            <input type="text" name="formEditStaff[name]" id="name" placeholder="Prénom du staff" value="<?= $staff->getName() ?>">
        </div>
        <div style="display:flex">
            <label for="lastname">Nom :</label>
            <input type="text" name="formEditStaff[lastname]" id="lastname" placeholder="Nom du staff" value="<?= $staff->getLastname() ?>">
        </div>
        <div style="display:flex">
            <label for="role">Role du staff :</label>
            <input type="text" name="formEditStaff[role]" id="role" placeholder="Role du staff" value="<?= $staff->getRole() ?>">
        </div>
        <div style="display:flex ; flex-direction:column">
            <label for="picture">Photo :</label>
            <div style="display:flex">
                <p>Photo actuelle : </p>
                <img src="<?= Router::assets("images", $staff->getPicture()) ?>" alt="photo du staff" style="width:50px; height:50px">
            </div>
            <input type="file" name="formEditStaff[picture]" id="picture" placeholder="Photo du staff">
            <input type="hidden" name="formEditStaff[defaultPicture]" id="defaultPicture" value="<?= $staff->getPicture() ?>">
        </div>
        <button type="submit">Enregistrer</button>
    </form>
</body>

</html>