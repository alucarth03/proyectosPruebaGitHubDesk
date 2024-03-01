<?php

namespace App\Models;

use App\Config\ResponseHttp;
use App\Config\Security;
use App\DB\ConnectionDB;
use App\DB\Sql;
use PDO;
use PDOException;

class userModel extends ConnectionDB
{
    private static string $nombre;
    private static string $dni;
    private static string $correo;
    private static int $rol;
    private static string $password;
    private static string $IDToken;
    private static string $fecha;

    public function __construct(array $data)
    {
        self::$nombre   = $data['name'];
        self::$dni      = $data['dni'];
        self::$correo   = $data['correo'];
        self::$rol      = $data['rol'];
        self::$password = $data['password'];
        self::$IDToken  = '';
        self::$fecha    = '';
    }

    final public static function getName(){return self::$nombre;}
    final public static function getDni(){return self::$dni;}
    final public static function getEmail(){return self::$correo;}
    final public static function getRol(){return self::$rol;}
    final public static function getPassword(){return self::$password;}
    final public static function getIDToken(){return self::$IDToken;}
    final public static function getDate(){return self::$fecha;}

    final public static function setName(string $nombre){self::$nombre=$nombre;}
    final public static function setDni(string $dni){self::$dni=$dni;}
    final public static function setEmail(string $correo){self::$correo=$correo;}
    final public static function setRol(int $rol){self::$rol=$rol;}
    final public static function setPassword(string $password){self::$password=$password;}
    final public static function setIDToken(string $IDToken){self::$IDToken=$IDToken;}
    final public static function setDate(string $fecha){self::$fecha=$fecha;}

    final public static function post(){
        if(Sql::exists("SELECT dni FROM usuario WHERE dni = :dni",":dni",self::getDni())){
            return ResponseHttp::status400('El DNI ya está registrado');
        }else if(Sql::exists("SELECT correo FROM usuario WHERE correo = :correo",":correo",self::getEmail())){
            return ResponseHttp::status400('El Correo ya esta registrado');
        }else{
            self::setIDToken(hash('sha512',self::getDni().self::getEmail()));
            self::setDate(date('Y-m-d H:i:s'));

            try {
                $con = self::getConnection();
                $sqlQuery = "INSERT INTO usuario (nombre,dni,correo,rol,password,IDToken,fecha) VALUES";
                $sqlQuery .="(:nombre,:dni,:correo,:rol,:password,:IDToken,:fecha)";
                $query = $con->prepare($sqlQuery);
                $query->bindValue(':nombre',self::getName(),PDO::PARAM_STR);
                $query->bindValue(':dni',self::getDni(),PDO::PARAM_STR);
                $query->bindValue(':correo',self::getEmail(),PDO::PARAM_STR);
                $query->bindValue(':rol',self::getRol(),PDO::PARAM_INT);
                $query->bindValue(':password',Security::createPassword(self::getPassword()),PDO::PARAM_STR);
                $query->bindValue(':IDToken',self::getIDToken(),PDO::PARAM_STR);
                $query->bindValue(':fecha',self::getDate(),PDO::PARAM_STR);

                $query->execute();
                $resultado = $query->rowCount()>0 ? ResponseHttp::status200('Datos registrados con exito') : ResponseHttp::status500('Los datos no pudieron registrarse');
                return $resultado;
            } catch (PDOException $e) {
                error_log('UserModel::post -> '. $e->getMessage());
                die(json_encode(ResponseHttp::status500()));
            }
        }
    }
}