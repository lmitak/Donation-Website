<?php
/**
 * Created by PhpStorm.
 * User: lmita_000
 * Date: 9.6.2015.
 * Time: 10:40
 */

session_start();
$hostname = "localhost";
$user = "root";
$pw = "";
$dbName = "donationdb";



if(isset($_SESSION['_sf2_attributes']['user'])){
    try{

        $dbh = new PDO("mysql:host=$hostname;dbname=$dbName", $user, $pw);
        $provjeraKorisnika = "SELECT * FROM korisnik WHERE username = '{$_SESSION['_sf2_attributes']['user']['username']}'";
        $res = $dbh->query($provjeraKorisnika);
        if($row = $res->fetch(PDO::FETCH_ASSOC)){
            $upit = "UPDATE tvrtka
                    SET kolPopusta = {$_POST['kolPopusta']},
                    vrijemePopusta = '{$_POST['vrijemePopusta']}',
                    dostupno = {$_POST['dostupnost']}
                    WHERE id = {$_POST['idTvrtke']}";
            if($dbh->query($upit)){
                echo "success";
            }else{
                echo "fail";
            }

        }else{
            echo "greska autentifikacije";
        }



    }catch (Exception $e){
        echo json_encode($e->getMessage());
    }




}else{
    echo "Greska!";
    //var_dump($_SESSION['_sf2_attributes']['user']);
}