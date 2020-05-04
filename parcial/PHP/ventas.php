<?php
class Ventas
{
    public $idProducto;
    public $cantidad;
    public $precioTotal;
    public $fecha;
    public $idUsuario;
    public $tipo;
    public $sabor;

    function __construct($producto,$cantidad,$precioTotal,$usuario,$tipo,$sabor)
    {
        $this->idProducto = $producto;
        $this->cantidad = $cantidad;
        $this->precioTotal = $precioTotal;
        $this->idUsuario = $usuario;
        $this->fecha = time();
        $this->tipo =$tipo;
        $this->sabor = $sabor;
    }

    public static function verificarStock($tipo,$sabor,$cantidad)
    {
        $productos = Archivo::Leer(dirname(__DIR__,1).'/archivos/productos.txt');
        foreach ($productos as $producto)
        {
            if(is_a($producto, 'Producto'))
            {
                if($tipo == $producto->tipo && $producto->sabor == $sabor/* && $producto->stock >= $cantidad*/)
                {
                    return $producto;
                }
            }
        }
        return null;
    }
    public static function registrarVenta($producto, $usuario,$cantidad)
    {
        try
        {
            $precioTotal = $producto->precio * $cantidad;
            Producto::vender($producto,$cantidad);
            $venta = new Ventas($producto->id,$cantidad,$precioTotal,$usuario,$producto->tipo,$producto->sabor);
            Archivo::Guardar($venta,dirname(__DIR__,1).'/archivos/ventas.txt');
            return $venta;
        }catch(Exception $e)
        {
            throw($e);
        }
    }
    static function filtarVentas($path,$id)
    {
        $ventas = Archivo::Leer($path);
        $ventasUsuario = array();
        foreach($ventas as $venta)
        {
            if(is_a($venta,"Ventas"))
            {
                if($venta->idUsuario == $id)
                {
                    array_push($ventasUsuario,$venta);
                }
            }
        }
        return $ventasUsuario;
    }
}