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

//iconv_set_encoding("input_encoding", "UTF-8");
//iconv_set_encoding("output_encoding", "UTF-8");
//iconv_set_encoding("internal_encoding", "UTF-8");

try{
    $dbh = new PDO("mysql:host=$hostname;dbname=$dbName;charset=utf8", $user, $pw);
//dodati još provjeru da li je dostupno sadržaja
    $sql = "SELECT * FROM tvrtka
        JOIN tipTvrtke ON idTipa = tip
        WHERE
          (latitude BETWEEN {$_POST['lat']}-0.05 AND {$_POST['lat']}+0.05)
          AND (longitude BETWEEN {$_POST['lng']}-0.05 AND {$_POST['lng']}+0.05)
          AND (kolPopusta IS NOT NULL) AND (vrijemePopusta IS NOT NULL)";
    $array = array();
    foreach($dbh->query($sql) as $row){
        //$row['14'];
        //$picolo = iconv("UTF8", "UTF8", $row['14']);
        //echo $row['14'];
        //var_dump($picolo);
        //var_dump(iconv_get_encoding('all'));

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


