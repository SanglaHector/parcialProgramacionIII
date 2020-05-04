<?php
class Archivo
{
    public static function Guardar($objeto,$path)
    {
        try{
            $archivo = fopen($path,'a+');
            $serializado = serialize($objeto).PHP_EOL;
            $bytes = fwrite($archivo,$serializado);
            fclose($archivo);
        }catch(Exception $e)
        {
            throw $e;
        }
    }
    public static function Leer($path)
    {
        try{
            if(file_exists($path))
            {
                $archivo = fopen($path,'r+');
                $datos = array();
                while(!feof($archivo))
                {
                    $objeto = (unserialize(fgets($archivo)));  
                    array_push($datos,$objeto);
                }
                if($datos == null)
                {
                    return "";
                }
                unset($datos[sizeof($datos) - 1]); 
                return $datos;
            }else{
                return null; 
            }
        }catch(Exception $e)
        {
            throw $e;
        }
    }
    public static function generarId($path,$clase)
    {
        $id = 1;
        $objetos = Archivo::Leer($path);
        if($objetos != null)
        {
            foreach($objetos as $objeto )
            {
                if(is_a($objeto,$clase))
                {
                    if($objeto->id >= $id)
                    { 
                        $id = $objeto->id + 1;
                    }
                }
            }
        }
        return $id;
    }
    /*
    public static function leerDinamico($path,$convertidoEn)
    {
        try{
            if(file_exists($path))
            {
                $archivo = fopen($path,'r+');
                $datos = array();
                while(!feof($archivo))
                {
                    $objeto = "";
                    switch($convertidoEn)
                    {
                        case 's':
                            $objeto = unserialize(fgets($archivo));  
                        break;
                        case 'jwt':
                            $objeto = jwtClass::decodeJWT(fgets($archivo));
                        break;
                        case 'json':
                            $objeto = json_decode(fgets($archivo));
                        break;
                    }
                    array_push($datos,$objeto);
                }
                if($datos == null)
                {
                    return "";
                }
                unset($datos[sizeof($datos) - 1]); 
                return $datos;
            }else{
                return null; 
            }
        }catch(Exception $e)
        {
            throw $e;
        }
    }
    static function guardarDinamico($objeto,$path,$convertidoEn)
    {
        try{

            $archivo = fopen($path,'a+');
            $serializado = "";
            switch($convertidoEn)
            {
                case 's':
                    $serializado = serialize($objeto).PHP_EOL;
                break;
                case 'jwt':
                    $serializado = jwtClass::encodeJWT($objeto).PHP_EOL;
                break;
                case 'json':
                    $serializado = json_encode($objeto).PHP_EOL;
                break;
            }
            $bytes = fwrite($archivo,$serializado);
            fclose($archivo);
        }catch(Exception $e)
        {
            throw $e;
        }
    }*/ 
    // evitar usar ya que es un poco desprolijo. Por lo general las clases se guardan en los archivos como
    //serializados para luego podes deserializarlos y tenes ya el objeto definido. Espero que esto sirva
    
}