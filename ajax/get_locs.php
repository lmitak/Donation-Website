<?php
/**
 * Created by PhpStorm.
 * User: lmita_000
 * Date: 29.5.2015.
 * Time: 20:12
 * AJAX PHP dio za dobivanje obližnjih lokacija oko korisnika
 */


$hostname = "localhost";
$user = "root";
$pw = "";
$dbName = "donationdb";


try{
    $dbh = new PDO("mysql:host=$hostname;dbname=$dbName;charset=utf8", $user, $pw);
    //upit za provjeru da li se nalaze tvrtke u zadanom radijusu oko korisnika
    $sql = "SELECT * FROM tvrtka
        JOIN tipTvrtke ON idTipa = tip
        WHERE
          (latitude BETWEEN {$_POST['lat']}-0.05 AND {$_POST['lat']}+0.05)
          AND (longitude BETWEEN {$_POST['lng']}-0.05 AND {$_POST['lng']}+0.05)
          AND (kolPopusta IS NOT NULL) AND (vrijemePopusta IS NOT NULL)";
    $array = array();
    foreach($dbh->query($sql) as $row){

        array_push($array, $row);
    }
    if(empty($array))
    {
        echo "nema ništa";
    }else{
        echo json_encode($array);
    }



}catch (Exception $e){
    echo json_encode($e->getMessage());
}


