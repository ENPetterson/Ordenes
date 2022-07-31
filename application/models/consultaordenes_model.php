<?php

require_once APPPATH."/third_party/PHPExcel.php";

class Consultaordenes_model extends CI_Model{
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
    
    
    public function getComitentes(){
        
        
//                print_r("LLEGA ACA"); die;

        $sql = "select  numComitente,
                        nombre
                from    operaciones.comitente
                order by numComitente
        ";
        $result = R::getAll($sql);
        return $result;
    }
    
    public function getConsultaOrdenes(){
        
        
//                print_r("LLEGA ACA"); die;
        if (!$this->numComitente) {
            $numComitente = 0;
        } else {
            $numComitente = $this->numComitente;
        }
        
//        R::debug(true);
        
        $sql = "SELECT 
                cu.id,
                cu.numComitente, 
                cu.cantidad, 
                cupla.plazo,
                cupla.especie,
                cu.bonoNombre,
                cu.tipo,
                cu.cierrecupon_id,
                'Canje Extranjero' as orden,
                cuci.fechahora,
                CONCAT(cuus.nombre,' ',cuus.apellido) as usuario,
                '' as fhmodificacion,
                cues.estado
                FROM cupon cu
                LEFT JOIN plazocupon cupla
                ON cu.plazo = cupla.id
                LEFT JOIN cierrecupon cuci
                ON cu.cierrecupon_id = cuci.id
                LEFT JOIN usuario cuus
                ON cu.usuario_id = cuus.id
                LEFT JOIN estadoorden cues
                ON cu.estado_id = cues.id
                WHERE cuci.fechaHora > now()
                AND numComitente = {$numComitente}
                AND estado_id IN (2,3,4,5,6,7);
        ";
                
        $result1 = R::getAll($sql);
        
        
        $sql2 = "SELECT 
                ca.id, 
                ca.numComitente, 
                ca.cantidad, 
                capla.plazo,
                capla.especie,
                '' as bonoNombre,
                '' as tipo,
                ca.cierrecanje_id, 
                'Canje Letes' as orden,
                caci.fechahora,
                CONCAT(caus.nombre,' ',caus.apellido) as usuario,
                '' as fhmodificacion,
                caes.estado
                FROM canje ca
                LEFT JOIN plazocanje capla
                ON ca.plazo = capla.id
                LEFT JOIN cierrecanje caci
                ON ca.cierrecanje_id = caci.id
                LEFT JOIN usuario caus
                ON ca.usuario_id = caus.id
                LEFT JOIN estadoorden caes
                ON ca.estado_id = caes.id
                WHERE caci.fechaHora > now()
                AND numComitente = {$numComitente}
                AND estado_id IN (2,3,4,5,6,7);
        ";
                
        $result2 = R::getAll($sql2);
        
        $sql3 = "SELECT 
                cl.id, 
                cl.numComitente, 
                cl.cantidad, 
                clpla.plazo,
                clpla.especie,
                cl.bonoNombre,
                cl.tipo,
                cl.cierrecanjelocal_id,
                'Canje Local' as orden,
                clci.fechahora,
                CONCAT(clus.nombre,' ',clus.apellido) as usuario,
                fhmodificacion,
                cles.estado
                FROM canjelocal cl
                LEFT JOIN plazocanjelocal clpla
                ON cl.plazo = clpla.id
                AND cl.cierrecanjelocal_id = clpla.cierrecanjelocal_id
                LEFT JOIN cierrecanjelocal clci
                ON cl.cierrecanjelocal_id = clci.id
                LEFT JOIN usuario clus
                ON cl.usuario_id = clus.id
                LEFT JOIN estadoorden cles
                ON cl.estado_id = cles.id
                WHERE clci.fechaHora > now()
                AND numComitente = {$numComitente}
                AND estado_id IN (2,3,4,5,6,7,10);
        ";
                
        $result3 = R::getAll($sql3);
        
        
//        echo chr(13);
//        print_r($result1);
//        
//        echo chr(13);
//        print_r($result2);
        
        $result = array_merge($result1, $result2, $result3);
        
//        echo chr(13);
//        print_r($result);
//        die;
        
        return $result;
    }
    
            
}
