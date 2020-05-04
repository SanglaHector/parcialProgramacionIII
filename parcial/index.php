<?php
require __DIR__ . '/vendor/autoload.php';
use \Firebase\JWT\JWT;
require_once __DIR__.'\PHP\tratarPath.php';
require_once __DIR__.'\PHP\archivo.php';
require_once __DIR__.'./PHP/jwtoken.php';
require_once __DIR__.'./PHP/marcaAgua.php';
require_once __DIR__.'./PHP/error.php';
require_once __DIR__.'./PHP/retorno.php';
require_once __DIR__.'/PHP/usuario.php';
require_once __DIR__.'/PHP/producto.php';
require_once __DIR__.'/PHP/ventas.php';


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
            $retorno = tratarUsuario();
        break;
        case '/login':
            $retorno = tratarLogIn();
        break;
        case '/pizzas':
            $retorno = tratarStock($header,$request);
        break;
        case '/ventas': 
            $retorno = tratarVentas($header,$request);
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
        case '/pizzas':
            $retorno = tratarStock($header,$request);
        break;
        case '/ventas':
            $retorno = tratarVentas($header,$request);
        break;
        default:
    break;
    }
}else
{    
    $retorno = Retorno::armarRetorno(false,"Error request no aceptada","",null,null);
}
echo $retorno;