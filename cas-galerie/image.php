<?php
    include_once("modele/modele.php");
    ob_start();

    function coordonnees_copyright($image,$coordonnes){ // permet de mettre la localisation 
        $dataImg = getimagesize($_GET["lien"]);  
		$width= $dataImg[0];
		$height= $dataImg[1]; 

        global $coordonnes;
        $textcolor = imagecolorallocate($image, 0, 0, 255);
        imagettftext($image, $width / 50, 0, $width, $height - 10, imagecolorallocate($image, 196, 196, 196), "./Herborn.ttf", $coordonnes);
    }

    function date_copyright($image, $nomFichier){ // permet créer la date copyright
        $dataImg = getimagesize($_GET["lien"]);  
		$width= $dataImg[0];
		$height= $dataImg[1]; 
        $verif = appartient2($nomFichier[2]);
        if(empty($verif)){
            $date = recup_date($nomFichier[2]);
            $textcolor = imagecolorallocate($image, 0, 0, 255);
            if($width>$height){
                imagettftext($image, $width / 50, 0, $width /1.4, $height - 10, imagecolorallocate($image, 255, 255, 255), "./Herborn.ttf",$date[0]["FileDateTime"]);
            }
            else{
                imagettftext($image, $height / 50, 0, $width /1.55, $height - 10, imagecolorallocate($image, 255, 255, 255), "./Herborn.ttf",$date[0]["FileDateTime"]);
            }

        }else{
            $date = recup_date_exif($nomFichier[2]);
            $textcolor = imagecolorallocate($image, 0, 0, 255);
            if($width>$height){
                imagettftext($image, $width / 50, 0, $width /1.4, $height - 10, imagecolorallocate($image, 255, 255, 255), "./Herborn.ttf",$date[0]["DateTimeOriginal"]);
            }
            else{
                imagettftext($image, $height / 50, 0, $width /1.55, $height - 10, imagecolorallocate($image, 255, 255, 255), "./Herborn.ttf",$date[0]["DateTimeOriginal"]);
            }
        }
    }

    function traiterImage($image, $nomFichier){ // permet de mettre le logo et le texte copyright sur l'image
        //coordonnees_copyright($image,$coordonnes);
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
            // permet d'ajouter le copyright et le logo
            traiterImage($image, $nomFichier);

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