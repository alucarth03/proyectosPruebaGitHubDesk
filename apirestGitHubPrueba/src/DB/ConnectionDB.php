<?php

namespace App\DB;

use App\Config\ResponseHttp;
use PDO;
use PDOException;

require __DIR__.'/dataDB.php';

class ConnectionDB 
{
    private static $host='';
    private static $user='';
    private static $passsword='';

    final public static function dataConn($host, $user, $passsword){
        self::$host = $host;
        self::$user = $user;
        self::$passsword = $passsword;
    }

    final public static function getConnection(){
        try {
            $opt = [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];
            $con = new PDO(self::$host,self::$user,self::$passsword,$opt);
            $con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            error_log("Conexion exitosa");
            return $con;
        }catch(PDOException $e){
            error_log("Error de conexion: ".$e->getMessage());
            die(json_encode(ResponseHttp::status500()));
        }
    }
}