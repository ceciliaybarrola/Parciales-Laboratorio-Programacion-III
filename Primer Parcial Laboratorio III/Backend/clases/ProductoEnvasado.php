<?php
    require_once "Producto.php";
    require_once "IParte1.php";
    require_once "IParte2.php";
    require_once "IParte3.php";
    class ProductoEnvasado extends Producto implements IParte1, IParte2, IParte3
    {
        public $id;
        public $codigoBarra;
        public $precio;
        public $pathFoto;

        public function __construct($id = 0, $nombre = ' ', $origen =' ', $codigoBarra=0, $precio= 0, $pathFoto= NULL)
        {
            parent::__construct($nombre, $origen);
            $this->id = $id;
            $this->codigoBarra = $codigoBarra;
            $this->precio = $precio;
            $this->pathFoto = $pathFoto;
        }
        public function ToJSON()
        {
            return json_encode($this);
        }
        public function Agregar()
        {
            $ret = false;
            try {
                $user = 'root';
                $pass = '';
                $conectionString='mysql:host=localhost;dbname=productos_bd';
                $pdo = new PDO($conectionString, $user, $pass);

                $consulta = $pdo->prepare("INSERT INTO productos (codigo_barra, nombre, origen, precio, foto) VALUES (:codigo_barra, :nombre, :origen, :precio, :foto)");

                $consulta->bindValue(":codigo_barra", $this->codigoBarra, PDO::PARAM_INT);
                $consulta->bindValue(":nombre", $this->nombre, PDO::PARAM_STR);
                $consulta->bindValue(":origen", $this->origen, PDO::PARAM_STR);
                $consulta->bindValue(":precio", $this->precio, PDO::PARAM_INT);
                $consulta->bindValue(":foto", $this->pathFoto, PDO::PARAM_STR);

                $consulta->execute();

                $ret = true;
            } catch (Exception $e) {
                echo $e->getMessage();
                return false;
            }
            return $ret;
        }
        public static function Traer()
        {
            try {
                $user = 'root';
                $pass = '';
                $conectionString='mysql:host=localhost;dbname=productos_bd';
                $pdo = new PDO($conectionString, $user, $pass);
                $consulta = $pdo->prepare("SELECT * FROM productos");
                $consulta->execute();
    
                while ($row = $consulta->fetch(PDO::FETCH_ASSOC)) {
                    $empl_array[] = new ProductoEnvasado($row['id'], $row['nombre'], $row['origen'], $row['codigo_barra'], $row['precio'], $row['foto']);
                }
    
                if (isset($empl_array)) {
                    return $empl_array;
                } else {
                    return false;
                }
            } catch (Exception $e) {
                echo $e->getMessage();
                return false;
            }
        }
        public static function Eliminar($id)
        {
            $ret = false;
            try{
                $user = 'root';
                $pass = '';
                $conectionString='mysql:host=localhost;dbname=productos_bd';
                $pdo = new PDO($conectionString, $user, $pass);

                $consulta = $pdo->prepare("DELETE FROM productos WHERE id = :id");
    
                $consulta->bindValue(":id", $id, PDO::PARAM_INT);
    
                $consulta->execute();
    
                $ret = true;
            }catch(Exception $e){
                echo $e->getMessage();
                return false;
            }
            return $ret;
        }
        public function Modificar()
        {
            $ret = false;
            try {
                $user = 'root';
                $pass = '';
                $conectionString='mysql:host=localhost;dbname=productos_bd';
                $pdo = new PDO($conectionString, $user, $pass);

                $consulta = $pdo->prepare("UPDATE productos SET nombre = :nombre, origen = :origen, codigo_barra = :codigo_barra, precio = :precio, foto = :foto WHERE id = :id");

                $consulta->bindValue(":id", $this->id, PDO::PARAM_INT);
                $consulta->bindValue(":nombre", $this->nombre, PDO::PARAM_STR);
                $consulta->bindValue(":origen", $this->origen, PDO::PARAM_STR);
                $consulta->bindValue(":codigo_barra", $this->codigoBarra, PDO::PARAM_INT);
                $consulta->bindValue(":foto", $this->pathFoto, PDO::PARAM_STR);
                $consulta->bindValue(":precio", $this->precio, PDO::PARAM_INT);

                $consulta->execute();

                $ret = true;
            } catch (Exception $e) {
                echo $e->getMessage();
                return false;
            }
            return $ret;
        }
        public function Existe($prodArray)
        {
            $ret = false;
            foreach($prodArray as $producto){
                if($producto->nombre == $this->nombre && $producto->origen == $this->origen)
                {
                    $ret = true;
                    break;
                }
                
            }
            return $ret;
        }
        public static function GuardarFoto($nombre, $origen, $foto){
            $ret = null;
            if($foto != null){
                $tempPath = "./productos/imagenes/" . $foto["name"];
                $extension = pathinfo($tempPath, PATHINFO_EXTENSION);
                $foto["name"] = $nombre . "." . $origen . "." . date("Gis") . "." . $extension;
                $ret = "./productos/imagenes/" . $foto["name"];
    
                if(!move_uploaded_file($foto["tmp_name"], $ret))
                    $ret = null;  
            }
            return $ret;
        }
        public function GuardarEnArchivo()
        {
            $nombreArchivo = "./archivos/productos_envasados_borrados.txt";
            $archivo = fopen($nombreArchivo, "a+");

            if ($archivo) {

                $pathFoto = $this->pathFoto;

                //Si tiene path foto la muevo
                if($pathFoto != null)
                {
                    $fechaActual = date("Gis");

                    $pathviejo = $pathFoto;
                    $imagenTipo = strtolower(pathinfo($pathFoto, PATHINFO_EXTENSION));
                    $pathNuevo = "./productosBorrados/" . $this->id . "." . $this->nombre . "." . "borrado" . "." . $fechaActual . "." . $imagenTipo;

                    if (file_exists($pathviejo)) {
                        rename(chop($pathviejo), chop($pathNuevo));
                    }

                    $this->pathFoto = $pathNuevo; 
                }
            
                fwrite($archivo, $this->id . "-" . $this->nombre . "-" . $this->origen . "-" . $this->codigoBarra . "-" . $this->precio . "-" . $this->pathFoto . "\r\n");

                fclose($archivo);
            }
        }
        public function ExisteId($prodArray)
        {
            $ret = false;
            foreach($prodArray as $producto){
                if($producto->id == $this->id)
                {
                    $ret = true;
                    break;
                }
                
            }
            return $ret;
        }
                public static function MostrarBorradosJSON()
        {
            $ret = array();
            $f = fopen("./archivos/productos_eliminados.json", "r");

            while (!feof($f)) {
                $line = trim(fgets($f));
                $prod = json_decode($line);
                if ($line != null)
                    array_push($ret, new ProductoEnvasado($prod->id, $prod->nombre, $prod->origen, $prod->codigoBarra, $prod->precio, $prod->pathFoto));
            }
            fclose($f);
            return $ret;
        }

        public static function MostrarModificados()
        {
            $directory = "productosModificados";
            $dirint = dir($directory);
            $imgArray = array();

            while(($archivo = $dirint->read()) != false)
            {
                if(strpos($archivo, "jpg") || strpos($archivo, "jpeg") || strpos($archivo, "png") || strpos($archivo, "gif"))
                {
                    array_push($imgArray, '<img src="'.$directory."/".$archivo.'" width=50 height=50 >');
                }
            }

            return $imgArray;
        }
    }

?>