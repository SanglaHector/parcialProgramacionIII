<?php
require_once dirname(__DIR__,1).'/PHP/archivo.php';
require_once dirname(__DIR__,1).'/PHP/retorno.php';

function tratarUsuario()
{
    try
    {
        if( isset($_REQUEST['email']) &&  
            isset($_REQUEST['clave']) &&
            isset($_REQUEST['tipo']))
            {
                $usuario = Usuario::validar($_REQUEST['email'],$_REQUEST['clave']);
                if($usuario == false)//no existe usuario
                {
                    $usuario = Usuario::registrar($_REQUEST['email'],$_REQUEST['clave'],$_REQUEST['tipo']);
                    if($usuario != null)
                    {
                        Archivo::Guardar($usuario,'./archivos/users.txt');
                        return Retorno::armarRetorno(true,"","",null,json_encode($usuario));
                    }else
                    {
                        return Retorno::armarRetorno(false,"error al registrar usuario","",null,null);
                    }
                }else
                {
                    return Retorno::armarRetorno(false,"Nombre de usuario ya existente","",null,null);
                }
            }
            
    }catch(Exception$e)
    {
        return Retorno::armarRetorno(false,"",$e->getMessage(),null,null);
    }
}
function tratarLogIn()
{
    try
    {
        if(isset($_REQUEST['email']) && isset($_REQUEST['clave']))
        {
            $usuario = Usuario::validar($_REQUEST['email'],$_REQUEST['clave']);
            if(is_a($usuario, 'Usuario'))
            {   
                $retorno = jwtClass::encodeJWT(jwtClass::armoPayLoad3(json_encode($usuario)));
                return Retorno::armarRetorno(true,"","",$retorno,null);
            }
            else if($usuario == false)
            {
                return Retorno::armarRetorno(false,"Usuario inexistente","",null,null);
            }
            else if($usuario == true)
            {
                return Retorno::armarRetorno(false,"Contraseña incorrecta","",null,null);
            }
        }else
        {
            return Retorno::armarRetorno(false,"Datos incompletos","",null,null);
        }
    }catch(Exception $e)
    {
        return Retorno::armarRetorno(false,"",$e->getMessage(),null,null);
    }
}
function tratarStock($header,$request)
{
    try{
        if($request == 'POST')
        {
            if($header != null)
            {
                $usuario = jwtClass::autenticarToken($header);
                if($usuario != false && isset($usuario->email) && isset($usuario->clave))
                {
                    $usuarioValidado = Usuario::validar($usuario->email,$usuario->clave);// ver esto
                    if( is_a($usuarioValidado,"Usuario")  && $usuarioValidado->tipo == 'encargado')
                    {
                        if(isset($_REQUEST['tipo']) &&
                            isset($_REQUEST['sabor']) &&
                            isset($_REQUEST['precio']) &&
                            isset($_REQUEST['stock']) &&
                            isset($_FILES['foto']))
                        {
                            $retorno =  Producto::registrarProducto($_REQUEST['tipo'],$_REQUEST['precio'],$_REQUEST['stock'],$_REQUEST['sabor'],$_FILES['foto']);
                            if(is_a($retorno,'Producto'))
                            {
                                return Retorno::armarRetorno(true,"","",null,json_encode($retorno));
                            }else
                            {
                                return Retorno::armarRetorno(false,$retorno,"",null,null);
                            }
                        }
                    }
                    else
                    {
                        return Retorno::armarRetorno(false,"Usuario invalido","",null,null);
                    }
                }
                return Retorno::armarRetorno(false,"Error en datos","",null,null);
            }
        }
        else if($request == 'GET')
        {
            if($header != null)
            {
                $usuario = jwtClass::autenticarToken($header);
                if($usuario != false && isset($usuario->email) && isset($usuario->clave))
                {
                    $usuarioValidado = Usuario::validar($usuario->email,$usuario->clave);// ver esto
                    if( is_a($usuarioValidado,"Usuario")  && $usuarioValidado->tipo == 'encargado')
                    {
                        $retorno = Archivo::Leer(dirname(__DIR__,1).'/archivos/productos.txt');
                        return Retorno::armarRetorno(true,"","",null,json_encode($retorno));
                    }
                    if( is_a($usuarioValidado,"Usuario")  && $usuarioValidado->tipo == 'cliente')
                    {
                        $retorno = Archivo::Leer(dirname(__DIR__,1).'/archivos/productos.txt');
                        $standarClase = array();
                        foreach($retorno as $prod)
                        {
                            if(is_a($prod, 'Producto'))
                            {
                                $sinStock = new stdClass();
                                $sinStock->tipo = $prod->tipo;
                                $sinStock->precio = $prod->precio;
                                $sinStock->sabor = $prod->sabor;
                                $sinStock->foto = $prod->foto;
                                array_push($standarClase,$sinStock);
                            }
                        }
                        return Retorno::armarRetorno(true,"","",null,json_encode($standarClase));
                    }
                }
            }


            //  return Retorno::armarRetorno(true,"","",null,json_encode(Archivo::Leer(dirname(__DIR__,2)."/archivos/productos.txt")));
        }
        else
        {
            return Retorno::armarRetorno(false, "Error en request","",null,null);
        }
    }catch(Exception $e)
    {
        return Retorno::armarRetorno(false,"",$e->getMessage(),null,null);
    }
}



function tratarVentas($header , $request)
{
    try
    {
        if($request == 'POST')
        {
            if($header != null)
            {
                $usuario = jwtClass::autenticarToken($header);
                if($usuario != false && isset($usuario->email) && isset($usuario->clave))
                {
                    $usuarioValidado = Usuario::validar($usuario->email,$usuario->clave);// ver esto
                    if( is_a($usuarioValidado,"Usuario")  && $usuarioValidado->tipo == 'cliente')
                    {
                        if(isset($_REQUEST['tipo']) && isset($_REQUEST['sabor']))
                        {
                            $producto = Ventas::verificarStock($_REQUEST['tipo'],$_REQUEST['sabor'],1);
                            if ($producto != null)
                            {
                                $retorno = Ventas::registrarVenta($producto,$usuario->email,1);
                                return Retorno::armarRetorno(true,"","",null,json_encode($retorno));
                            }
                            return Retorno::armarRetorno(false,"Producto sin stock","",null,null);
                        }
                        return Retorno::armarRetorno(false,"Datos de productos invalidos","",null,null);
                    }
                    return Retorno::armarRetorno(false,"Usuario invalido","",null,null);
                }
                return Retorno::armarRetorno(false,"Datos de usuario invalidos","",null,null);
            }
            return Retorno::armarRetorno(false,"Error en header","",null,null);
        }else if($request == 'GET')
        {
            if($header != null)
            {
                $usuario = jwtClass::autenticarToken($header);
                if($usuario != false && isset($usuario->email) && isset($usuario->clave))
                {
                    $usuarioValidado = Usuario::validar($usuario->email,$usuario->clave);// ver esto
                    if( is_a($usuarioValidado,"Usuario"))
                    {
                        $path = dirname(__DIR__,1).'/archivos/ventas.txt';
                        if($usuarioValidado->tipo == 'cliente')
                        {
                            $ventasUsuario = Ventas::filtarVentas($path,$usuarioValidado->email);
                            return Retorno::armarRetorno(true,"","",null,json_encode($ventasUsuario));
                        }else if ($usuarioValidado->tipo == 'encargado')
                        {
                            $ventas =Archivo::Leer($path);
                            $cantidad = 0;
                            $total = 0;
                            foreach($ventas as $venta)
                            {
                                if(is_a($venta,'Ventas'))
                                {
                                    $cantidad = $cantidad +1;
                                    $total = $venta->precioTotal + $total;
                                }
                            }
                            $retorno = new stdClass();
                            $retorno->catidad = $cantidad;
                            $retorno->total = $total;
                            return Retorno::armarRetorno(true,"","",null,json_encode($retorno));
                        }
                    }else
                    {
                        return Retorno::armarRetorno(false,"Usuario invalido","",null,null);
                    }
                }else
                {
                    return Retorno::armarRetorno(false,"Datos de usuario invalidos","",null,null);
                }
            }
            else
            {
                return Retorno::armarRetorno(false,"Error en header","",null,null);
            }
        }
    }catch(Exception $e)
    {
        return Retorno::armarRetorno(false,"",$e->getMessage(),null,null);
    }
}