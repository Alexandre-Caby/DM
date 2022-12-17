<DOCTYPE HTML>
<html>
<head>
    <title>Galerie</title>
    <link rel="stylesheet" href="style/gestionRepertoire.css">
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
<br>
<?php
//session_start();

// -----------------------------------------------
// check
// -----------------------------------------------

if(isset($_GET["erreur"])){
    echo $_GET["erreur"];
}

// if(!isset($_SESSION["id"])){
//     include_once "vue/connexion.php";
// }else {
//     ?>
 <!--   <a href="controleur.php?action=Logout"> <button class="btn btn-outline-danger">Se Deconnecter</button></a>
    <br> -->
    <?php 
// }

include_once "vue/gestionRepertoire.php";
?>
</body>
