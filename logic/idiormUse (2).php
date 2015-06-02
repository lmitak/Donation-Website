<?php
/**
 * Created by PhpStorm.
 * User: lmita_000
 * Date: 27.5.2015.
 * Time: 16:49
 */


// podesite parametre za svoju bazu
ORM::configure('mysql:host=localhost;dbname=donationdb');
ORM::configure('username', 'root');
ORM::configure('password', '');

// iduća linija je potrebna kako bi natjerali konekciju prema MySQL-u u utf8
ORM::configure('driver_options', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));