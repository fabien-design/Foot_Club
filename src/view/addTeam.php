<?php

use App\Controller\TeamController;
use App\Model\Team;
use App\Model\OpposingClub;
use App\Router\Router;
if(isset($_POST['formAddTeam'])){
    $erreurs = [];
    if(empty($_POST["formAddTeam"]["nameTeam"])){
        $erreurs["nameTeam"]="Le nom de l'équipe est obligatoire";
    }
    if(!preg_match("/^[\p{L} -]+$/u", $_POST["formAddTeam"]["nameTeam"])) {
        $erreurs["nameTeam"]= "Le nom de l'équipe n'est pas valide.";
    }
    if(empty($_POST["formAddTeam"]["addressTeam"])){
        $erreurs["addressTeam"]="L'adresse de l'équipe est obligatoire";
    }
    if(empty($_POST["formAddTeam"]["cityTeam"])){
        $erreurs["cityTeam"]="La ville de l'équipe est obligatoire";
    }
    if(empty($erreurs)){
        $team = new Team();
        $team->setNameTeam($_POST["formAddTeam"]["nameTeam"]);
        
        $oppsClub = new OpposingClub($team, $_POST["formAddTeam"]["addressTeam"], $_POST["formAddTeam"]["cityTeam"]);

        $store = TeamController::store($oppsClub);
        if($store === true){
            echo '
            <script type="text/javascript">
                function changerURL(nouvelleURL) {
                    var urlActuelle = window.location.href;
                    var partiesURL = urlActuelle.split("/");
                    partiesURL.pop(); // Supprime le dernier élément
                    var nouvelleURL = partiesURL.join("/") + "/" + nouvelleURL;
                    window.location.href = nouvelleURL;
                }
                changerURL("'.Router::use('allTeams').'");
            </script>
            ';
        }else{
            echo $store;
        }
    }else{
        var_dump($erreurs);
    }

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une équipe</title>
</head>
<body>
    <?php
    echo "<a href='".Router::use("allTeams")."'> <- Retour </a>";
    ?>
    <h1>Ajout d'une équipe</h1>
  
    <form action="" name="formAddTeam" method="POST" enctype="multipart/form-data" style="display:flex;flex-direction:column;gap: 40px;">
        <div style="display:flex">
            <label for="nameTeam">Nom de l'équipe : </label><br />
            <input type="text" name="formAddTeam[nameTeam]" id="nameTeam" placeholder="Nom de l'équipe">
            <?= isset($erreurs['nameTeam']) ? "<span style='color:red'>".$erreurs['nameTeam']."</span>": ""?>
        </div>
        <div style="display:flex">
            <label for="cityTeam">Ville de l'équipe</label>
            <input type="text" name="formAddTeam[cityTeam]" id="cityTeam" placeholder="Ville de l'équipe">
        </div>
        <div style="display:flex">
            <label for="addressTeam">Adresse de l'équipe</label>
            <input type="text" name="formAddTeam[addressTeam]" id="addressTeam" placeholder="Adresse de l'équipe">
        </div>
        <button type="submit">Ajouter</button>
    </form>
</body>
</html>