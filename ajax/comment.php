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

if(is_string($_POST['comment'])){
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
                if($row['commented'] == 1){
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

                $antiSUpit = "INSERT INTO antispamming(cookie, commented, idTvrtke) VALUES('{$hash_cookie}', 1, {$idTvrtke['idTvrtke']})";
                $resASU = $dbh->query($antiSUpit);
                //cookie traje 1 dan
                ini_set('session.gc_maxlifetime', 3600 * 24);
                session_set_cookie_params(3600 * 24);
                $_SESSION['anonymous'] = $hash_cookie;


            }elseif($izvrsi == 2){ //kada je ali moze izvrsiti
                $antiSUpit = "UPDATE antispamming SET commented = 1 WHERE cookie = '{$_SESSION['anonymous']}' AND  idTvrtke = {$idTvrtke['idTvrtke']}";
                $resASU = $dbh->query($antiSUpit);
            }
            $commentUpit = "INSERT INTO komentari(idTvrtke, komentar) VALUES({$idTvrtke['idTvrtke']}, '{$_POST['comment']}')";
            if($resRate = $dbh->query($commentUpit)) {
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
    echo "Greska! Comment.";
}