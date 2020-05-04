<?php

//No te olvides de 
//Instalar composer init
//instalar JWT-https://github.com/firebase/php-jwt
//composer require firebase/php-jwt

//dirname(__DIR__,$numero de veces que queres ir para atras).pathquesigue
//Osea la idea es ir atras en la direccion actual y luego avanzar de nuevo hacia donde tenga que ir.

require __DIR__ . '/vendor/autoload.php';
use \Firebase\JWT\JWT;
require_once __DIR__.'\PHP\tratarPath.php';
require_once __DIR__.'\PHP\archivo.php';
require_once __DIR__.'./PHP/jwtoken.php';
require_once __DIR__.'./PHP/marcaAgua.php';
require_once __DIR__.'./PHP/error.php';
require_once __DIR__.'./PHP/retorno.php';

if(isset($_SERVER['PATH_INFO']))
{
    $path = $_SERVER['PATH_INFO'];
}else
{
    $path = null;
}
if(isset($_SERVER['REQUEST_METHOD']))
{
    $request = $_SERVER['REQUEST_METHOD'];
}else
{
    $request = null;
}
$header = jwtClass::getHeader('token');//ver que en el postman haya escrito bien token;
$retorno = "";
 if($request == 'POST'&& $request =! null)
 {
    switch($path)
    {
        case '/usuario':
        break;
        case '/login':
        break;
        case '/stock':
        break;
        case '/ventas': 
        break;
        default:
            $retorno = Retorno::armarRetorno(false,"path incorrecta \"'.$path. '\" no existe","",null,null);
        break;
    }
 }else
 if($request == 'GET' && $request != null)//nuevo, verificar
 {
    switch($path)
    {
        case '/stock':
        break;
        case '/ventas':
         //   $retorno = tratarVentas($header,$request);//ejemplo
        break;
        default:
    break;
    }
}else
{    
    $retorno = Retorno::armarRetorno(false,"Error request no aceptada","",null,null);
}
echo $retorno;