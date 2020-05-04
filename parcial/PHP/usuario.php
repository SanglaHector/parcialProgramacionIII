<?php

use function PHPSTORM_META\type;

require_once 'C:\xampp\htdocs\parcial\vendor\autoload.php';
require_once 'C:\xampp\htdocs\parcial\PHP\jwtoken.php';
require_once 'C:\xampp\htdocs\parcial\PHP\archivo.php';

class Usuario
{
    public $email;
    public $id;
    public $clave;
    public $tipo;

    function __construct($email,$clave,$tipo)
    {
        $this->email = $email;
        $this->id = Archivo::generarId(dirname(__DIR__,1).'/archivos/users.txt','Usuario');
        $this->clave = $clave;
        $this->tipo = $tipo;
    }
    public static function registrar($email,$clave,$tipo){
        try{
            if(($tipo == 'encargado' || $tipo == 'cliente'))
            {
                $nuevoUsuario = new Usuario($email,$clave,$tipo);
                return $nuevoUsuario;
            }
            return null;
        }catch(Exception $e)
        {
            throw $e;
        }
    }
    public static function validar($email,$clave)
    {
        try{
            $usuarios = Archivo::Leer(dirname(__DIR__,1).'/archivos/users.txt');
            foreach($usuarios as $usuario )
            {
                if(is_a($usuario,'Usuario'))
                {
                    if($usuario->email == $email && $clave == $usuario->clave)
                    { 
                        return $usuario;
                    }
                    if($usuario->email == $email)
                    {
                        return true;
                    }
                }
            }
            return false;
        }
        catch(Exception $e)
        {
            throw $e;
        }
    }
}