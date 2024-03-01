<?php

namespace App\Config;
use Dotenv\Dotenv;
use Firebase\JWT\JWT;

class Security
{
    private static $jwt_data;

    final public static function secretKey(){
        //Cargamos variables de entorno
        $dotenv = Dotenv::createImmutable(dirname(__DIR__,2));
        $dotenv->load();
        return $_ENV['SECRET_KEY'];
    }

    final public static function createPassword(string $pwd){
        $pass = password_hash($pwd,PASSWORD_DEFAULT);
        return $pass;
    }

    final public static function validatePassword(string $pwd, string $pwdHash){
        if(password_verify($pwd,$pwdHash)){
            return true;
        }else{
            error_log("La contraseña es incorrecta");
            return false;
        }
    }

    final public static function createTokenJwt(string $key, array $data){
        $payload = array(
            "iat" => time(),
            "exp" => time() + 60,
            "data" => $data
        );

        $jwt = JWT::encode($payload,$key,"HS256");
        return $jwt;
    }

    final public static function validateTokenJwt(array $token, string $key){
        if (!isset($token['autorization'])) {
            die(json_encode(ResponseHttp::status400()));
            exit;
        }
        try{
            $jwt = explode(" ",$token['autorization']);
            $data = JWT::decode($jwt[1],$key);
            self::$jwt_data = $data;
            return $data;
            exit;
        }catch(\Exception $th){
            error_log("Token Invalido");
            die(json_encode(ResponseHttp::status401("Token invalido o expirado")));
        }        
    }

    final public static function getDataJwt(){
        $jwt_decoded_array = json_decode(json_encode(self::$jwt_data),true);
        return $jwt_decoded_array['data'];
        exit;
    }
}