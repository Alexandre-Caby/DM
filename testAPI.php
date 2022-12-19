<?php
include_once "config.php";

//connexionAPI_IPSTACK();
connexionAPI_positionstack("test",'3.8353811944444','39.999036638889');


function geolocalisation($name,$apiResult){
    $SQL = "INSERT INTO Geolocalisation (Nom_image, Ville, Pays) VALUES ('$name', '$apiResult[locality]', '$apiResult[country]')";
    return SQLInsert($SQL);
}

function SQLInsert($sql)
{
  global $BDD_host;
  global $BDD_base;
  global $BDD_user;
  global $BDD_password;
  
  try {
    $dbh = new PDO("mysql:host=$BDD_host;dbname=$BDD_base", $BDD_user, $BDD_password);
  } catch (PDOException $e) {
    die("<font color=\"red\">SQLInsert: Erreur de connexion : " . $e->getMessage() . "</font>");
  }

  $dbh->exec("SET CHARACTER SET utf8");
  $res = $dbh->query($sql);
  if ($res === false) {
    $e = $dbh->errorInfo(); 
    die("<font color=\"red\">SQLInsert: Erreur de requete : " . $e[2] . "</font>");
  }

  $lastInsertId = $dbh->lastInsertId();
  $dbh = null; 
  return $lastInsertId;
}

// function connexionAPI_IPSTACK(){
// 	// set IP address and API access key
// 	$ip = '90.110.209.74'; //$_SERVER['REMOTE_ADDR'];
// 	$access_key = 'b650627a68bd2bfd8460a8cfa016d171';
//     echo "$ip\n";

// 	// Initialize CURL:
// 	$ch = curl_init('http://api.ipstack.com/'.$ip.'?access_key='.$access_key.'&fields=city');
// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// 	// Store the data:
// 	$json = curl_exec($ch);
// 	curl_close($ch);

// 	// Decode JSON response:
// 	$api_result = json_decode($json, true);

// 	// Output the "capital" object inside "location"
// 	//print_r($api_result);
//     echo $api_result['city'];

// 	geolocalisation("test", $api_result['city']);

// }

function connexionAPI_positionstack($name, $longitude, $latitude){
    // Set API access_key and query
    $access_key = '8aa764f560dbe046429bf0e1302523f2';
    $query = $longitude.','.$latitude;
    echo $query;

    $ch = curl_init('http://api.positionstack.com/v1/reverse?access_key='.$access_key.'&query='.$query);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $json = curl_exec($ch);
    
    curl_close($ch);
    
    $apiResult = json_decode($json, true);
    
    echo $apiResult['data']['0']['country'];
    print_r($apiResult);

    geolocalisation($name, $apiResult['data']['0']);

}


?>