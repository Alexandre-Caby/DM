<?php
    include_once("modele/modele.php");
    ob_start();

    function geolocalisation_copyright($image,$nomFichier){ // permet de mettre la localisation 
        $dataImg = getimagesize($_GET["lien"]);  
		$width= $dataImg[0];
		$height= $dataImg[1]; 
        $textcolor = imagecolorallocate($image, 0, 0, 255);
        $verif = appartient_geo($nomFichier);
        if(!empty($verif)){
            $coordonnes = recup_geo_pays($nomFichier);
            //echo($coordonnes);
            if($width>$height){
                //imagestring($image, 0, $width, $height-10, $coordonnes, $textcolor);
                imagettftext($image, $width / 25, 0, $width, $height - 10, $textcolor, "./Herborn.ttf", $coordonnes);
            }
            else{
                //imagestring($image, 0, $width/0.4, $height-10, $coordonnes, $textcolor);
                imagettftext($image, $width / 50, 0, $width, $height - 10, $textcolor, "./Herborn.ttf", $coordonnes);
            }
        }else{
            $coordonnes = recup_geo_ville($nomFichier);
            //echo($coordonnes);
            if($width>$height){
                //imagestring($image, 0, $width, $height-10, $coordonnes, $textcolor);
                imagettftext($image, $width / 25, 0, $width, $height - 10, $textcolor, "./Herborn.ttf", $coordonnes);
            }
            else{
                //imagestring($image, 0, $width/0.4, $height-10, $coordonnes, $textcolor);
                imagettftext($image, $width / 50, 0, $width, $height - 10, $textcolor, "./Herborn.ttf", $coordonnes);
            }
        }
    }

    function date_copyright($image, $nomFichier){ // permet créer la date copyright
        $dataImg = getimagesize($_GET["lien"]);  
		$width= $dataImg[0];
		$height= $dataImg[1]; 
        $verif = appartient_date($nomFichier);
        if(empty($verif)){
            $date = recup_date($nomFichier);
            $textcolor = imagecolorallocate($image, 255, 0, 0);
            if($width>$height){
                imagettftext($image, $width / 50, 0, $width /1.4, $height - 10, $textcolor, "./Herborn.ttf",$date);
            }
            else{
                imagettftext($image, $height / 50, 0, $width /1.55, $height - 10, $textcolor, "./Herborn.ttf",$date);
            }
        }else{
            $date = recup_date_exif($nomFichier);
            $textcolor = imagecolorallocate($image, 255, 255, 0);
            if($width>$height){
                imagettftext($image, $width / 50, 0, $width /1.4, $height - 10, $textcolor, "./Herborn.ttf",$date);
            }
            else{
                imagettftext($image, $height / 50, 0, $width /1.55, $height - 10, $textcolor, "./Herborn.ttf",$date);
            }
        }
    }

    function traiterImage($image, $nomFichier){ // permet de mettre la localisation et le texte copyright sur l'image
        //echo $nomFichier;
        geolocalisation_copyright($image,$nomFichier);
        date_copyright($image, $nomFichier);
    }


    if(isset($_GET["lien"])){

        $fichier = $_GET["lien"];
        $extension = strtolower(pathinfo($fichier, PATHINFO_EXTENSION));
        $valide = array('png', 'gif', 'jpeg');
        $nomFichier = explode("/", $fichier);
        //print_r($nomFichier);
        
       header("Content-type: image/$extension");

        if (in_array($extension, $valide))
        { 
            switch($extension){

                case 'jpeg':
                    $image = imagecreatefromjpeg($fichier);
                break;

                case 'png':
                    $image = imagecreatefrompng($fichier);
                break;

                case 'gif':
                    $image = imagecreatefromgif($fichier);
                break;
                
                default:
                    die();
                break;

            }
            // permet d'ajouter le copyright et la localisation
            //echo $image;
            traiterImage($image, $nomFichier[2]);


            switch($extension){

                case 'jpeg':
                    imagejpeg($image);
                break;

                case 'png':
                    imagepng($image);
                break;

                case 'gif':
                    imagegif($image);
                break;
                
                default:
                    die();
                break;

            }

            imagedestroy($image);
        }
    }
   
    ob_end_flush();
?>