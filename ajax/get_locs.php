<?php
/**
 * Created by PhpStorm.
 * User: lmita_000
 * Date: 29.5.2015.
 * Time: 20:12
 */


$hostname = "localhost";
$user = "root";
$pw = "";
$dbName = "donationdb";

try{

    $dbh = new PDO("mysql:host=$hostname;dbname=$dbName", $user, $pw);
//dodati još provjeru da li je dostupno sadržaja
    $sql = "SELECT * FROM tvrtka
        JOIN tipTvrtke ON idTipa = tip
        WHERE (latitude BETWEEN {$_POST['lat']}-0.08 AND {$_POST['lat']}+0.08) AND (longitude BETWEEN {$_POST['lng']}-0.08 AND {$_POST['lng']}+0.08)";
    $array = array();
    foreach($dbh->query($sql) as $row){
        array_push($array, $row);
    }
    if(empty($array))
    {
        echo "nema ništa";
    }else
        echo json_encode($array);

}catch (Exception $e){
    echo json_encode($e->getMessage());
}


