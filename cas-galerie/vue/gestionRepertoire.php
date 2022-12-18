<?php

include_once("modele/modele.php");


if (isset($_REQUEST["nomRep"]))  $nomRep = $_REQUEST["nomRep"];
 else $nomRep = false;
?>

<div id="repertoire">
	
	<h1>Gestion des répertoires </h1>
	<form action="controleur.php" style="width:25%">
	<label>Créer un nouveau répertoire : </label>
	<div class="input-group mb-3">
		<input type="text" class="form-control" placeholder="Nom repertoire" aria-label="Recipient's username" aria-describedby="basic-addon2" name="nomRep">
		<input type="submit" name="action" value="Creer" class="btn btn-outline-success"/>

	</div>
	</form>

	<form action="controleur.php" style="width:25%">
	<label>Choisir un répertoire : </label>
	<select name="nomRep" class="form-select form-select-sm" aria-label=".form-select-sm example">
</div>
<?php
	$rep = opendir("galerie/"); // ouverture du repertoire 
	while ( $fichier = readdir($rep))
	{
		// On élimine le résultat '.' (répertoire courant) 
		// et '..' (répertoire parent)

		if (($fichier!=".") && ($fichier!=".."))
		{
			// Pour éliminer les autres fichiers du menu déroulant, 
			// on dispose de la fonction 'is_dir'
			if (is_dir("galerie/" . $fichier))
				printf("<option value=\"$fichier\">$fichier</option>");
		}
	}
	closedir($rep);
?>
</select>
<input type="submit" name="action" value="Explorer" class="btn btn-outline-info"> <input type="submit" name="action" value="Supprimer Repertoire" class="btn btn-outline-danger">
</form>

<?php
	if (!$nomRep)  die(""); 
	// interrompt immédiatement l'exécution du code php
?>

<hr />
<h2> Contenu du répertoire '<?php echo$_GET["nomRep"]?>' </h2>


<form action="controleur.php" enctype="multipart/form-data" method="post" style="width:50%">
	<input type="hidden" name="MAX_FILE_SIZE" value="10000000">
	<input type="hidden" name="nomRep" value="<?php echo $nomRep; ?>">
	<label>Ajouter un fichier image : </label>
	<div class="input-group mb-3">
		<input type="file" class="form-control" id="inputGroupFile02" name="FileToUpload">
		<input type="submit" value="Uploader" name="action" class="btn btn-outline-success">
	</div>
</form>

<?php

	$numImage = 0;
	$rep = opendir("./galerie/$nomRep"); 		// ouverture du repertoire 
	while ( $fichier = readdir($rep))	// parcours tout le contenu de ce répertoire
	{
	
		if (($fichier!=".") && ($fichier!=".."))
		{
			// Pour éliminer les autres répertoires du menu déroulant, 
			// on dispose de la fonction 'is_dir'
			if (!is_dir("./galerie/$nomRep/" . $fichier))
			{
				// vérification du fichier (si c'est une image)
				$formats = ".jpeg.jpg.gif.png";
				if (strstr($formats,strrchr($fichier,"."))) 
				{
					$numImage++;
					$dataImg = getimagesize("./galerie/$nomRep/$fichier"); 

					// récupérer le type d'une image et sa taille 
					$width= $dataImg[0];
					$height= $dataImg[1]; 
					$type= substr($dataImg["mime"],6);

					echo "<div class=\"mini\">\n";

					$src = "";
					$srcThumb = "";
					
					$verif = appartient2($fichier);
					if(empty($verif)){
						$verif = recup_date_string($fichier);
					}

					$src = "image.php?lien=galerie/$nomRep/$fichier";
					$srcThumb = "image.php?lien=galerie/$nomRep/thumbs/crow.png";

					echo "<a target=\"_blank\" href=\"$src\" class='img-fluid'><img src=\"$srcThumb\"/></a>\n";


					echo "<div>$fichier - $verif \n";			
					echo "<a href=\"controleur.php?nomRep=$nomRep&fichier=$fichier&action=Supprimer\" >Supp</a>\n";
					echo "<br />($width * $height $type)\n";
					echo "<br />\n";

					echo "<form action=\"controleur.php\">\n";
					echo "<input type=\"hidden\" name=\"fichier\" value=\"$fichier\" />\n";
					echo "<input type=\"hidden\" name=\"nomRep\" value=\"$nomRep\" />\n";
					echo "<input type=\"hidden\" name=\"action\" value=\"Renommer\" />\n";
					echo "<input type=\"text\" class=\"renommer\" name=\"nomFichier\" value=\"$fichier\" onclick=\"this.select();\" />\n";
					echo "<input type=\"submit\" class=\"btn_renommer\" value=\">\" />\n";
					echo "</form>\n";

					echo "</div></div>\n";

					// si on a affiché 5 images sur la ligne actuelle alors placer sur la ligne du dessous
					
					if (($numImage%5) ==0)
					echo "<br style=\"clear:left;\" />";
				}
				else{
					echo "<p>Mauvais format</p>";
				}
			}
		}

	
	}
	closedir($rep);

	// afficher un message lorsque le répertoire est vide
	if ($numImage==0) echo "<h3>Aucune image dans le répertoire</h3>";

?>

