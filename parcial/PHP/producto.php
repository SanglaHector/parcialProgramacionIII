<?php
require_once __DIR__.'./archivo.php';
class Producto{
    public $tipo;
    public $precio;
    public $stock;
    public $sabor;
    public $foto;
    public $id;

    function __construct($tipo,$precio,$stock,$sabor,$foto,$id)
    {
        $this->tipo = $tipo;
        $this->precio = $precio;
        $this->stock = $stock;
        $this->sabor = $sabor;
        $this->foto = $foto;
        $this->id = $id;
    }
    
    static function registrarProducto($tipo,$precio,$stock,$sabor,$foto)
    {
        try
        {
            if(($tipo == 'molde' || $tipo =='piedra') && ($sabor == 'jamon' || $sabor == 'napo' || $sabor == 'musa') && Producto::validar($tipo,$sabor)) 
            {
                $id = Archivo::generarId(dirname(__DIR__,1).'/archivos/productos.txt',"Producto");
                $rutaFoto = Producto:: tratarFoto($foto,$id);
                if($rutaFoto != null)
                {
                    $nuevoProducto =  new Producto($tipo,$precio,$stock,$sabor,$rutaFoto,$id);
                    Archivo::Guardar($nuevoProducto,dirname(__DIR__,1).'/archivos/productos.txt');
                    return $nuevoProducto;
                }else
                {
                    return 'Error tipo foto, debe ser jpg';
                }
            }else
            {
                return 'Error, producto invalido';
            }
        }catch(Exception $e)
        {
            throw $e;
        }
    }
    static function tratarFoto($foto, $id)
    {
        $explode = explode('.',$foto['name']);
        if(array_reverse($explode)[0] == "jpg" && isset($foto['tmp_name']))
        {
            $origen = $foto['tmp_name'];
            $nombre = $id.'_'.time().'_'. $foto['name'];
            $destino =dirname(__DIR__,1).'/imagenes/'.$nombre;
            if(file_exists($origen))
            {
                move_uploaded_file($origen,$destino);
                return $destino;
            }
        }else{
            return null;
        }
    }
    static function validar($tipo,$sabor)
    {
        try{
            $productos = Archivo::Leer(dirname(__DIR__,1).'/archivos/productos.txt');
                foreach($productos as $p )
                {
                    if(is_a($p,'Producto'))
                    {
                        if($p->tipo == $tipo && $p->sabor == $sabor )
                        { 
                            return false;
                        }
                    }
                }
                return true;//no existe
        }catch(Exception $e)
        {
            throw $e;
        }
    }
    public static function vender($producto,$cantidad)
    {
       // echo 'cantidad: '.$cantidad;
       $productos =  Archivo::Leer(dirname(__DIR__,1).'/archivo/productos.txt');
       $nuevoStock = $producto->stock - $cantidad;
       $producto->stock = $nuevoStock;
       foreach($productos as $p)
       {
           if(is_a($p,'Producto'))
           {
               if($producto->id == $p->id)
               {
                    $p->stock = $nuevoStock;    
               }
           }
       }
     //  copy('C:\xampp\htdocs\parcial2\archivos\productos.txt','C:\xampp\htdocs\parcial2\backup'.time().'txt');
       unlink(dirname(__DIR__,1).'/archivo/productos.txt');
       foreach($productos as $p)
       {
          Archivo::Guardar($p,dirname(__DIR__,1).'/archivo/productos.txt');
       }
    }
}