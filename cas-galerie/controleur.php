<?php

// -----------------------------------------------
// check
// -----------------------------------------------




//session_start();
include_once("libs/Utils.php");
include_once("libs/Securisation.php");
include_once("modele/modele.php");

// $whitelist = array(
//     "index.php","vue/connexion.php","vue/gestionRepertoire.php");


// if (in_array(basename($_SERVER["PHP_SELF"]),$whitelist)){
//     rediriger("index.php");
//     die("");
// }

$action = $_REQUEST["action"];
echo $action;

// if(!isset($action)){
// 	rediriger("index.php");
// }

switch($action)
	{
        case 'Explorer' :
			$nomRep = $_GET["nomRep"];

			$tab = array(
				"nomRep" => $nomRep
			);

			rediriger("index.php",$tab);
        break;  

		case 'Creer' : 
			if (isset($_GET["nomRep"]) && ($_GET["nomRep"] != ""))
			if(!is_dir("./galerie")){
				mkdir("./galerie");
			}
			if (!is_dir("./" . $_GET["nomRep"])) 
			{
				mkdir("./galerie/" . $_GET["nomRep"]);
			}
		break;

		case 'Supprimer' : 
			if (isset($_GET["nomRep"]) && ($_GET["nomRep"] != ""))
			if (isset($_GET["fichier"]) && ($_GET["fichier"] != ""))
			{
				$nomRep = $_GET["nomRep"];
				$fichier = $_GET["fichier"];
				
				unlink("galerie/".$nomRep . "/" . $fichier);
		
				unlink("galerie/".$nomRep . "/thumbs/" . $fichier);	
				$tab = array(
					"nomRep" => $nomRep
				);
				rediriger("index.php",$tab);
				
			}
		break;

		case 'Renommer' : 
			if (isset($_GET["nomRep"]) && ($_GET["nomRep"] != ""))
			if (isset($_GET["fichier"]) && ($_GET["fichier"] != ""))
			if (isset($_GET["nomFichier"]) && ($_GET["nomFichier"] != ""))
			{
				$nomRep = $_GET["nomRep"];
				$fichier = $_GET["fichier"];
				$nomFichier = $_GET["nomFichier"]; // nouveau nom 

				// renomme le fichier et sa miniature si elle existe
				if (file_exists("./galerie/$nomRep/$fichier"))			
					rename("./galerie/$nomRep/$fichier","./galerie/$nomRep/$nomFichier");

				if (file_exists("./galerie/$nomRep/thumbs/$fichier"))			
					rename("./galerie/$nomRep/thumbs/$fichier","./galerie/$nomRep/thumbs/$nomFichier");
				
			$tab = array(
				"nomRep" => $nomRep
			);
			rediriger("index.php",$tab);
			}
		
		break;

		case 'Uploader' : 

            $nomRep = $_POST["nomRep"];
            var_dump($nomRep);
			if (!empty($_FILES["FileToUpload"]))
			{

				if (is_uploaded_file($_FILES["FileToUpload"]["tmp_name"]))
				{
					$name = $_FILES["FileToUpload"]["name"];
					// ---------------------------------------------------------------------------
					$hash = exif_read_data($_FILES["FileToUpload"]["tmp_name"],0,1,0);
					if(!appartient($_FILES["FileToUpload"]["name"])){
						if(isset($hash["EXIF"])){
							$dateFile = $hash["FILE"]["FileDateTime"];
							$dateFile = date("d/m/y h:i:s", $dateFile);
							$hash = $hash["EXIF"];
							meta_donnees($hash,$dateFile, $name);
						}
						else {
							$dateFile2 = $hash["FILE"]["FileDateTime"];
							$dateFile2 = date("d/m/y h:i:s", $dateFile2);
							meta_donnees2($name, $dateFile2);
						}
					}

					
					copy($_FILES["FileToUpload"]["tmp_name"],"./galerie/$nomRep/$name");

					// creer le repertoire miniature s'il n'existe pas
					if (!is_dir("./galerie/$nomRep/thumbs")) 
					{
						mkdir("./galerie/$nomRep/thumbs");
					}
						
					$dataImg = getimagesize("./galerie/$nomRep/$name");  
					$type= substr($dataImg["mime"],6);// on enleve "image/" 

					// creer la miniature dans ce repertoire 
					miniature($type,"./galerie/$nomRep/$name",200,"./galerie/$nomRep/thumbs/$name");
				}
				else
				{
					echo "Problème lors de la création du répertoire miniature";
				}

				$tab = array (
				"nomRep" => $nomRep   
				);
				rediriger("index.php",$tab);
			}

		break;

		case 'Supprimer Repertoire':
            $nomRep= $_GET["nomRep"];
			// On ne peut supprimer que des r�pertoires vide !
			if (isset($nomRep) && ($nomRep != ""))
			{

				if (is_dir("./galerie/$nomRep/thumbs"))
				{
					$rep = opendir("./galerie/$nomRep/thumbs"); 		// ouverture du repertoire 
					while ( $fichier = readdir($rep))	// parcours tout le contenu de ce r�pertoire
					{

						if (($fichier!=".") && ($fichier!=".."))
						{
							// Pour éliminer les autres r�pertoires du menu d�roulant, 
							// on dispose de la fonction 'is_dir'
							if (!is_dir("./galerie/$nomRep/thumbs/" . $fichier))
							{
								unlink("./galerie/$nomRep/thumbs/" . $fichier);
							}
						}
					}
                    
					rmdir("./galerie/$nomRep/thumbs");
				}

				// repertoire principal
				$rep = opendir("./galerie/$nomRep"); 		// ouverture du repertoire 
				while ( $fichier = readdir($rep))	// parcours tout le contenu de ce r�pertoire
				{

					if (($fichier!=".") && ($fichier!=".."))
					{
						// is_dir permet de supprimer le répertoire
						if (!is_dir("./galerie/$nomRep/" . $fichier))
						{
							unlink("./galerie/$nomRep/" . $fichier);
						}
					}
				}

				rmdir("./galerie/$nomRep");
				$nomRep = false;
			}
		break;
	
}

rediriger("index.php");

function miniature($type,$nom,$dw,$nomMin)
{
	// Cr�e une miniature de l'image $nom
	// de largeur $dw
	// et l'enregistre dans le fichier $nomMin 


	// lecture de l'image d'origine, enregistrement dans la zone m�moire $im
	switch($type)
	{
		case "jpeg" : $im = imagecreatefromjpeg($nom);break;
		case "png" : $im = imagecreatefrompng($nom);break;
		case "gif" : $im = imagecreatefromgif($nom);break;		
	}

	$sw = imagesx($im); // largeur de l'image d'origine
	$sh = imagesy($im); // hauteur de l'image d'origine
	$dh = $dw * $sh / $sw;

	$im2 = imagecreatetruecolor($dw, $dh);

	$dst_x= 0;
	$dst_y= 0;
	$src_x= 0; 
	$src_y= 0; 
	$dst_w= $dw ; 
	$dst_h= $dh ; 
	$src_w= $sw ; 
	$src_h= $sh ;
	
	imagecopyresized ($im2,$im,$dst_x , $dst_y  , $src_x  , $src_y  , $dst_w  , $dst_h  , $src_w  , $src_h);
	
	
	switch($type)
	{
		case "jpeg" : imagejpeg($im2,$nomMin);break;
		case "png" : imagepng($im2,$nomMin);break;
		case "gif" : imagegif($im2,$nomMin);break;		
	}

	imagedestroy($im);
	imagedestroy($im2);
}

?>