<?php
 namespace App\DB;

use App\Config\ResponseHttp;
use PDOException;

 class Sql extends ConnectionDB
 {
    /**
     * Con este metodo podemos verificar si ya existen datos con las mismas referencias
     * 
     * @return boolean true / false
     */
    public static function exists(string $request, string $condition, $param){
        try {
            $con = self::getConnection();
            $query = $con->prepare($request);
            $query->bindParam($condition,$param);
            $query->execute();            
            $res = $query->rowCount()==0 ? false: true;
            
            return $res;
        } catch (PDOException $e) {
            error_log('Sql::exists -> '.$e->getMessage());
            die(json_encode(ResponseHttp::status500()));
        }
    }
 }