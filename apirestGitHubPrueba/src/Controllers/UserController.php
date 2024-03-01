<?php

namespace App\Controllers;

use App\Config\ResponseHttp;
use App\Models\userModel;

class UserController
{
    private static $validar_rol = '/^[1,2,3]{1,1}$/';
    private static $validar_number = '/^[0-9]+$/';
    private static $validar_text = '/^[a-zA-Z]+$/';


    public function __construct(
        private string $method, 
        private string $route, 
        private array $params, 
        private $data, 
        private $headers
    ){
        
    }

    final public function login(string $endpoint){
        if($this->method=='post' && $endpoint == $this->route){

        }
    }

    final public function post(string $endpoint){
        if($this->method=='post' && $endpoint == $this->route){
            if(empty($this->data['name']) || empty($this->data['dni']) || empty($this->data['correo']) ||
            empty($this->data['rol']) || empty($this->data['password']) || empty($this->data['confirmPassword'])){
                echo json_encode(ResponseHttp::status400('Todos los campos son requeridos'));
            }else if (!preg_match(self::$validar_text,$this->data['name'])){
                echo json_encode(ResponseHttp::status400('El campo nombre solo admite texto'));
            }else if (!preg_match(self::$validar_number,$this->data['dni'])){
                echo json_encode(ResponseHttp::status400('El campo DNI solo admite numeros'));
            }else if (!filter_var($this->data['correo'], FILTER_VALIDATE_EMAIL)){
                echo json_encode(ResponseHttp::status400('Formato de correo incorrecto'));
            }else if (!preg_match(self::$validar_rol, $this->data['rol'])){
                echo json_encode(ResponseHttp::status400('Rol invalido'));
            }else if (strlen($this->data['password']) < 8 || strlen($this->data['confirmPassword']) < 8){
                echo json_encode(ResponseHttp::status400('La contraseña debe tener minimo 8 caracteres'));
            }else if ($this->data['password'] !== $this->data['confirmPassword']){
                echo json_encode(ResponseHttp::status400('Las contraseñas no coinciden'));
            }else{
                new UserModel($this->data);
                echo json_encode(UserModel::post());
            }
        }
    }
}