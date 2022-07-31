<?php

require_once APPPATH."/third_party/PHPExcel.php";

class Generartxt_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public $id;
    public $tramo;
    public $numComitente;
    public $moneda;
    public $plazo;
    public $comision;
    public $cantidad;
    public $precio;
    public $comitente;
    public $tipoPersona;
    public $oficial;
    public $cuit;
    public $ordenes;
    
    public $usuario_id;
    
    public $cierre_id;
    public $fechahora;
    public $plazos;
    public $plazosBorrar;
    
    public $instrumento;
    
    public function getOrden(){
        $orden = R::load('generartxt', $this->id);        
        return $orden->export();
    }    
    
    public function grilla(){
        $sql = "select l.id,
                l.tramo,
                l.numComitente,
                l.moneda,
                l.plazo,
                l.comision,
                l.cantidad,
                l.precio,
                l.comitente,
                l.tipoPersona,
                l.oficial,
                l.cuit,
         from   generartxt l
         on     l.cierre_id = c.id
         where  l.usuario_id = ?
         and    l.cierre_id = ?
         order by l.fhmodificacion desc"; 
        
        $resultado = R::getAll($sql, array($this->usuario_id, $this->cierre_id));
        
        return $resultado;

    }
    
    
    public function getCierre(){
        
        $cierreBean = R::load('cierre', $this->cierre_id);
        $cierre = $cierreBean->export();
        $sql = "select * from plazo where cierre_id = ? order by moneda, plazo";
        $plazos = R::getAll($sql, array($cierreBean->id));
        $cierre['plazos'] = $plazos;
        return $cierre;
    }
    

    public function getCierreActual(){
        $cierreActual = R::findOne('cierre', 'fechahora > NOW() order by fechahora' );
        if (is_null($cierreActual)){
            return array('cerrado'=>true);
        } else {
            $this->cierre_id = $cierreActual->id;
            $cierre = $this->getCierre();
            return $cierre;
        }
    }
    
    
    public function getCierres(){
        $cierres = R::getAll('select * from cierre order by fechahora desc');
        return $cierres;
    }
 
    public function grillaResumen(){        
        $ordenes_in = implode(',', $this->ordenes);
        $sql = "SELECT  tramo, 
                        cantidad, 
                        count(*)      cantidadOrdenes, 
                        sum(cantidad) sumaCantidad
                        FROM    generartxt
                WHERE   id in ({$ordenes_in})
                GROUP BY tramo, cantidad";
        $resultado = R::getAll($sql);
        return $resultado;
    }    
    
/*    public function getOrdenes(){
        
        if ($this->cierre_id == 0){
            $this->getCierreActual();
        }
        
        $sql = "(select l.id,
                l.moneda,
                l.plazo,
                l.cantidad,
                l.precio
         from   lebac l
         join   cierre c
         on     l.cierre_id = c.id
         join   usuario u
         on     l.usuario_id = u.id
         where  l.estado_id <> 4
         and    l.cierre_id = ?
         and    l.numComitente = ?
         order by l.fhmodificacion desc)"; 
        
        $resultado = R::getAll($sql, array($this->cierre_id, $this->numComitente));
        
        return $resultado;
    }
*/    

/*    public function generarTxt(){        
        $estado = R::load('estadoorden', 3);
        $resultado = array('exito'=>1, 'resultado'=>'Ordenes Enviadas Correctamente');
        foreach ($this->ordenes as $id) {
            $orden = R::load('generarTxt', $id);
            $orden->estado = $estado;
            $orden->envio = 'M';
            $orden->fhenvio = R::isoDateTime();
            R::store($orden);
        }
        return $resultado;
    }
*/
    
    public function grabarExcel(){
                  
        $usuarioParam = $this->session->userdata('usuario');

        $orden = R::load('generartxt', $this->id);
               
        $this->load->helper('file');
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/tmp/';
       
        try {
            $inputFileName = $uploadDir . $this->archivo;         

            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);   
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);


        } catch(Exception $e) {
            die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }


        $sheetname = 'Sheet1'; 

        $sheet = $objPHPExcel->getSheetByName($sheetname);

        if($sheet){
            for ($row = 1; $row < 2; $row++){
                for($column = 0; $column < 11; $column++){
                    
                    $nombreHoja = str_replace(
                                            array('á','é','í','ó','ú'),
                                            array('a','e','i','o','u'),
                                            $sheet->getCellByColumnAndRow($column,$row)->getFormattedValue()
                                        );
                    $nombreHoja = strtolower($nombreHoja);                    
                    $nombreHojas[] = $nombreHoja;                                    
                }
            }
                        
            if($nombreHojas[0] == 'tramo' && $nombreHojas[3] == 'plazo'){
                $aprobado = 1;
            }
        }
        
        if($aprobado){
            $highestRow = $sheet->getHighestDataRow();
            
            $valido = true;
            $error = '';
            
            R::freeze(true);
            R::begin();
        
            for ($row = 2; $row <= $highestRow; $row++){

                $columna1 = $sheet->getCellByColumnAndRow(0,$row)->getCalculatedValue();

                $columna2 = $sheet->getCellByColumnAndRow(1,$row)->getCalculatedValue();                                                           
                $columna3 = $sheet->getCellByColumnAndRow(2,$row)->getCalculatedValue();   

                $columna4 = $sheet->getCellByColumnAndRow(3,$row)->getCalculatedValue();   

                $columna5 = $sheet->getCellByColumnAndRow(4,$row)->getCalculatedValue();   

                $columna6 = $sheet->getCellByColumnAndRow(5,$row)->getCalculatedValue();   

                $columna7 = $sheet->getCellByColumnAndRow(6,$row)->getCalculatedValue();   

                $columna8 = $sheet->getCellByColumnAndRow(7,$row)->getCalculatedValue();   

                $columna9 = $sheet->getCellByColumnAndRow(8,$row)->getCalculatedValue();   

                $columna10 = $sheet->getCellByColumnAndRow(9,$row)->getCalculatedValue();   

                //if(is_null($resultadoColor)){
                //    $error.="La funcion consultó con mysql y no encontró ese color en fila {$row} <br>";
                //    $valido = false;
                //}

                //if(is_null($resultadoComida)){
                //    $error.="La funcion consultó con mysql y no encontró esa comida en fila {$row} <br>";
                //    $valido = false;
                //}

                //if(!is_numeric($musica)){
                //    $error.="musica inválida en fila {$row} <br>";
                //    $valido = false;
                //}

                //if(!is_numeric($pelicula)){
                //    $error.="pelicula inválida en fila {$row} <br>";
                //    $valido = false;
                //}

                //if(!is_bool($esSoltero)){
                //    $error.="es soltero inválido en fila {$row} <br>";
                //    $valido = false;
                //}

                //if(!is_bool($esDeportista)){
                //    $error.="es deportista inválido en fila {$row} <br>";
                //    $valido = false;
                //}

                //if(!is_bool($esVegetariano)){
                //    $error.="es vegetariano inválido en fila {$row} <br>";
                //    $valido = false;
                //}

                $orden = R::dispense('generartxt'); 

                $orden->tramo = $columna0; 
                $orden->numComitente = $columna1;        
                $orden->moneda = $columna2;
                $orden->plazo = $columna3;
                $orden->comision = $columna4;
                $orden->cantidad = $columna5;
                $orden->precio = $columna6;
                $orden->comitente = $columna7;  
                $orden->tipoPersona = $columna8; 
                $orden->oficial = $columna9;                  
                $orden->cuit = $columna10; 


                if ($valido){ 
                               
                    $this->id = R::store($orden);    
                }

            
            }      
                        
            if ($valido){
                R::commit();
                $resultado = array('resultado'=>'OK');
            } else {
                R::rollback();
                $resultado = array('resultado'=>'Error', 'mensaje'=>$error);
            }
            
            R::freeze(false); 

            return $resultado;
            
        } else {
            
            $error = 'Títulos inválidos.';
            $resultado = array('resultado'=>'Error', 'mensaje'=>$error);
            return $resultado;
        }
    }        
}