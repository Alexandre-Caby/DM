<?php
include_once("libs/Utils.php");
include_once("libs/Securisation.php");
include_once("modele/modele.php");


$action = $_REQUEST["action"];
echo $action;

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
				supprimer_bdd($fichier);
				supprimer_bdd2($fichier);
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
				$id = getId($fichier);
				$nomFichier = $_GET["nomFichier"]; // nouveau nom 
				

				// renomme le fichier et sa miniature si elle existe
				if (file_exists("./galerie/$nomRep/$fichier"))			
					rename("./galerie/$nomRep/$fichier","./galerie/$nomRep/$nomFichier");

				if (file_exists("./galerie/$nomRep/thumbs/$fichier"))		
					rename("./galerie/$nomRep/thumbs/$fichier","./galerie/$nomRep/thumbs/$nomFichier");

				rennomer_bdd($nomFichier, $id);
				
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
					if(!appartient_image($name)){
						if(isset($hash["GPS"]["GPSLongitude"]) && isset($hash["GPS"]["GPSLatitude"])){
							$longitude = getGps($hash['GPS']["GPSLongitude"], $hash['GPS']['GPSLongitudeRef']);
							$latitude = getGps($hash['GPS']["GPSLatitude"], $hash['GPS']['GPSLatitudeRef']);
							connexionAPI_positionstack($name,$longitude,$latitude);
						}

						if(isset($hash["EXIF"])){
							$dateFile = $hash["FILE"]["FileDateTime"];
							$dateFile = date("d/m/y h:i:s", $dateFile);
							$hash = $hash["EXIF"];
							meta_donnees($hash,$dateFile, $name);
						}
						else {
							$dateFile2 = date("d/m/y h:i:s", time());
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
						supprimer_bdd($fichier);
						supprimer_bdd2($fichier);
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

/**
 * Permet de calculer les coordonnes gps d'une image (latitude et longitude)
 */
function getGps($exifCoord, $hemi) {

    $degrees = count($exifCoord) > 0 ? gps2Num($exifCoord[0]) : 0;
    $minutes = count($exifCoord) > 1 ? gps2Num($exifCoord[1]) : 0;
    $seconds = count($exifCoord) > 2 ? gps2Num($exifCoord[2]) : 0;

    $flip = ($hemi == 'W' or $hemi == 'S') ? -1 : 1;

    return $flip * ($degrees + $minutes / 60 + $seconds / 3600);

}

function gps2Num($coordPart) {

    $parts = explode('/', $coordPart);

    if (count($parts) <= 0)
        return 0;

    if (count($parts) == 1)
        return $parts[0];

    return floatval($parts[0]) / floatval($parts[1]);
}

/**
 * Permet de se connecter à l'API et d'enregistrer dans la bdd le pays et la ville ou la photo a ete prise
 */
function connexionAPI_positionstack($name, $longitude, $latitude){
    // Set API access_key and query
    $access_key = '8aa764f560dbe046429bf0e1302523f2';
    $query = $longitude.','.$latitude;
    //echo $query;

    $ch = curl_init('http://api.positionstack.com/v1/reverse?access_key='.$access_key.'&query='.$query);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $json = curl_exec($ch);
    
    curl_close($ch);
    
    $apiResult = json_decode($json, true);
    
    //echo $apiResult['data']['0']['locality'];
	//echo $apiResult['data']['0']['country'];
    //print_r($apiResult);

    geolocalisation($name, $apiResult['data']['0']);

}

?>