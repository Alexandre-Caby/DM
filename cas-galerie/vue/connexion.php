<?php

if (isset($_REQUEST["nomRep"]))  $nomRep = $_REQUEST["nomRep"];
else $nomRep = false;


?>
<h1>Veuillez vous connectez</h1>
<form action="controleur.php" method="POST">

<input type="text" name="nom" placeholder="nom">
<input type="password" name="pass" placeholder="mdp">
<input type="submit" name="action" value="connexion" class="btn btn-outline-warning" >

</form>

