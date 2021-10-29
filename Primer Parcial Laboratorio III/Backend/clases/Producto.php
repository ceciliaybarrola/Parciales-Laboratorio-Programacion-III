<?php
echo "";
class Producto
{
    public $nombre;
    public $origen;

    public function __construct($nombre, $origen)
    {
        $this->nombre = $nombre;
        $this->origen = $origen;
    }

    public function ToJSON()
    {
        return json_encode($this);
    }

    public function GuardarJSON($path)
    {
        $ret = new stdClass();
        $ret->exito = true;
        $ret->mensaje = "Se escribio en el archivo con exito";
//
        if (!file_put_contents($path, $this->ToJSON() . "\n", FILE_APPEND)) {
            $ret->exito = false;
            $ret->mensaje = "No se pudo escribir en el archivo";
        }
        return json_encode($ret);
    }

    public static function TraerJSON($path)
    {
        $ret = array();
        $f = fopen($path, "r");

        while (!feof($f)) {
            $line = trim(fgets($f));
            $prod = json_decode($line);
            if ($line != null)
                array_push($ret, new Producto($prod->nombre, $prod->origen));
        }
        fclose($f);
        return $ret;
    } 

    public static function VerificarProductoJSON($producto)
    {
        $ret = new stdClass();
        $ret->exito = false;
        $ret->mensaje = "No se encontro el producto en el archivo";
        $cont = array();

        $array_prod = Producto::TraerJSON("./archivos/productos.json");

        foreach($array_prod as $prod)
        {
            if($prod->nombre == $producto->nombre && $prod->origen == $producto->origen)
            {
                $ret->exito = true;
                if(!isset($cont[$prod->origen]))
                {
                    $cont[$prod->origen] = 1;
                }
                else
                {
                    $cont[$prod->origen]++;
                }
            }
            else{
                if(!isset($cont[$prod->nombre]))
                {
                    $cont[$prod->nombre] = 1;
                }
                else
                {
                    $cont[$prod->nombre]++;
                }
            }
        }

        if($ret->exito)
        {
            $ret->mensaje = "Se encontro el producto, hay ".$cont[$producto->origen]." productos registrados con el mismo origen";
        }
        else
        {
            $aux = 0;
            foreach($cont as $nombre => $cant)
            {
                if($cant > $aux)
                {
                    $aux = $cant;
                    $ret->mensaje = "No se encontro el producto, El producto mas popular es ".$nombre." y aparecio ".$cant." veces";
                }
            }
        }
        return json_encode($ret);
    }
}


?>