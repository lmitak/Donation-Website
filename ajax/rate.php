<?php
/**
 * Created by PhpStorm.
 * User: lmita_000
 * Date: 4.6.2015.
 * Time: 19:03
 */
session_start();
$hostname = "localhost";
$user = "root";
$pw = "";
$dbName = "donationdb";

if(is_numeric($_POST['stars'])){
    try{

        $dbh = new PDO("mysql:host=$hostname;dbname=$dbName", $user, $pw);
        $izvrsi = 0;

        $upitTvrtke = "SELECT idTvrtke FROM tvrtka WHERE naziv = '{$_POST['nazivTvrtke']}' AND adresa = '{$_POST['adresaTvrtke']}'";
        $idTvrtke = $dbh->query($upitTvrtke);
        $idTvrtke = $idTvrtke->fetch(PDO::FETCH_ASSOC);
        if($idTvrtke == null || empty($idTvrtke)) {
            echo "error";
        }

        if(isset($_SESSION['anonymous'])){

            $odobrenjeUpit = "SELECT * FROM antispamming WHERE cookie = '{$_SESSION['anonymous']}' AND  idTvrtke = {$idTvrtke['idTvrtke']}";
            $res = $dbh->query($odobrenjeUpit);
            $row = $res->fetch(PDO::FETCH_ASSOC);
            if(empty($row) || $row == false){
                $izvrsi = 1;
            }else{
                if($row['rated'] == 1){
                    $izvrsi = 0;
                }else{
                    $izvrsi = 2;
                }
            }
        }else{
            $izvrsi = 1;
        }

        if($izvrsi > 0){

            if($izvrsi == 1){ //kada nije uopce upisan u anti spammignb
                $hash_cookie = password_hash(time(), PASSWORD_DEFAULT);

                $antiSUpit = "INSERT INTO antispamming(cookie, rated, idTvrtke, rateAmount) VALUES('{$hash_cookie}', 1, {$idTvrtke['idTvrtke']}, {$_POST['stars']})";
                $resASU = $dbh->query($antiSUpit);
                //cookie traje 1 dan
                ini_set('session.gc_maxlifetime', 3600 * 24);
                session_set_cookie_params(3600 * 24);
                $_SESSION['anonymous'] = $hash_cookie;


            }elseif($izvrsi == 2){ //kada je ali moze izvrsiti
                $antiSUpit = "UPDATE antispamming SET rated = 1, rateAmount = {$_POST['stars']}  WHERE cookie = '{$_SESSION['anonymous']}' AND  idTvrtke = {$idTvrtke['idTvrtke']}";
                $resASU = $dbh->query($antiSUpit);
            }
            $ratingUpit = "UPDATE tvrtka as tv
                                SET rating = ((SELECT rating FROM (SELECT * FROM tvrtka WHERE tvrtka.idTvrtke = {$idTvrtke['idTvrtke']}) as x) + {$_POST['stars']}) / 2
                                WHERE tv.idTvrtke = {$idTvrtke['idTvrtke']}";
            if($resRate = $dbh->query($ratingUpit)) {
                echo "success";
            }else{
                echo "fail";
            }


        }else{
            echo "cookie greska";
        }

    }catch (Exception $e){
        echo json_encode($e->getMessage());
    }




}else{
    echo "Greska! Kriva ocjena ili si vec ocjenio tvrtku.";
}