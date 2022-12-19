<?php
include_once "testAPI.php";


function dataImg($nom)
{
	// obtenir des méta-données sur un fichier image
	echo "<pre>";
		print_r (getimagesize($nom));
		echo "<hr />";
		$exif = exif_read_data($nom,0,1,0);
		
		$longitude = getGps($exif['GPS']["GPSLongitude"], $exif['GPS']['GPSLongitudeRef']);
		$latitude = getGps($exif['GPS']["GPSLatitude"], $exif['GPS']['GPSLatitudeRef']);
		echo "$latitude\n";
		echo "$longitude\n";
		connexionAPI_positionstack($nom, $longitude, $latitude);
		// ne pas récupérer de miniature	
		print_r ($exif);
	echo "</pre>";
}

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


$cheminImage = "photo-restau.jpg"; 

dataImg($cheminImage);



?>
