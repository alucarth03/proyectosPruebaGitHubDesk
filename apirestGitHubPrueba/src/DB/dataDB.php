<?php

use App\DB\ConnectionDB;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__,2));
$dotenv->load();

$data = array(
    "user"     => $_ENV['USER'],
    "password" => $_ENV['PASSWORD'],
    "db"       => $_ENV['DB'],
    "host"     => $_ENV['HOST'],
    "port"     => $_ENV['PORT']
);

$host = 'mysql:host='.$data["host"].';port='.$data["port"].';dbname='.$data["db"].';charset=utf8';

ConnectionDB::dataConn($host,$data["user"],$data["password"]);