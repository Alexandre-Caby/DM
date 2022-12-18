<?php

include_once("libs/SQL.php");

/**
 * Récupère la date de la photo
 */
function recup_date_exif($name){
    $SQL=("SELECT DateTimeOriginal FROM Data_Exif WHERE Nom_image = '$name'");
    return parcoursRs(SQLSelect($SQL));
}

function recup_date($name){
    $SQL=("SELECT FileDateTime FROM Data_Exif WHERE Nom_image = '$name';");
    return parcoursRs(SQLSelect($SQL));
}

function recup_date_string($name){
    $SQL=("SELECT FileDateTime FROM Data_Exif WHERE Nom_image = '$name';");
    return SQLGetChamp($SQL);
}

/**
 * Permet d'insérer dans la table Data_Exif les meta-donnees
 */
function meta_donnees($exif,$date,$name){
    $SQL = "INSERT INTO Data_Exif (Nom_image, FileDateTime,ExposureTime, FNumber, ISOSpeedRatings, ExifVersion, DateTimeOriginal, DateTimeDigitized, 
                                ComponentsConfiguration, MaxApertureValue, Flash, FocalLength, MakerNote, FlashPixVersion, 
                                ColorSpace, ExifImageWidth, ExifImageLength, InteroperabilityOffset, FocalPlaneYResolution)                                 
            VALUES ('$name', '$date','$exif[ExposureTime]','$exif[FNumber]','$exif[ISOSpeedRatings]','$exif[ExifVersion]', 
                    '$exif[DateTimeOriginal]', '$exif[DateTimeDigitized]', '$exif[ComponentsConfiguration]',
                    '$exif[MaxApertureValue]','$exif[Flash]','$exif[FocalLength]','$exif[MakerNote]',
                    '$exif[FlashPixVersion]', '$exif[ColorSpace]','$exif[ExifImageWidth]','$exif[ExifImageLength]',
                    '$exif[InteroperabilityOffset]', '$exif[FocalPlaneYResolution]')";
    return SQLInsert($SQL);
}

/**
 * Permet d'insérer dans la table Data_Exif le nom de l'image si les donnees exif n'existent pas
 */
function meta_donnees2($name, $dateFile){
    $SQL = "INSERT INTO Data_Exif (Nom_image, FileDateTime) VALUES ('$name', '$dateFile')";
    return SQLInsert($SQL);
}

/**
 * Vérifie si l'image est déjà notée dans la base de donnée
 */
function appartient($name){
    $SQL = "SELECT Nom_image FROM Data_Exif WHERE Nom_image = '$name'";
    return SQLSelect($SQL);
}

/**
 * Vérifie si la date présente dans la base de donnée
 */
function appartient2($name){
    $SQL = "SELECT DateTimeOriginal FROM Data_Exif WHERE Nom_image = '$name'";
    return SQLGetChamp($SQL);
}

/**
 * Supprime les métas-données dans la base de donnée
 */
function supprimer_bdd($name){
    $SQL = "DELETE FROM Data_Exif WHERE Nom_image = '$name'";
    return SQLDelete($SQL);
}

?>