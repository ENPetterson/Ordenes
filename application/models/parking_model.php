<?php

require_once APPPATH."/third_party/PHPExcel.php";

class Parking_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public $id;
    public $tramo;
    public $numComitente;
    public $moneda;
    public $cable;
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
    
    public $cierreparking_id;
    public $fechahora;
    public $plazos;
    public $minimos;
    public $usuario;
//    public $colocacionPesos;
    public $colocacionDolares;
    public $feriados;
    /*
    public $colocacionLebacsNov;
    public $colocacionLebacsDic;
    */
//    public $colocacionA2J9;
    private $workbook;
    private $sheetIndex;
    
    
    public function getLosFeriados(){
        
        $sql = "SELECT * FROM feriado";
        $feriados = R::getAll($sql);
                
        return $feriados;
    }
    
    
    public function saveOrden(){
        
        $usuarioParam = $this->session->userdata('usuario');
        $usuario = R::load('usuario', $usuarioParam['id']);

        $orden = R::load('parking', $this->id);
        
        $moneda = R::load('moneda', $this->moneda);

        $cierreActual = $this->getCierreActual();
        
        $sql1 = "SELECT sum(cantidad) as sumaAprobado
                FROM parking
                WHERE numComitente = {$this->numComitente}
                AND especie = '{$this->especie}'
                AND cierreparking_id = {$cierreActual['id']}
                AND estado_id IN (6,8)
                GROUP BY numComitente, especie;";
        $sumaAprobado = R::getRow($sql1);
                
//        $ordenAnterior = $orden;
        if ($orden->id == 0 ){
            $estadoorden = R::load('estadoorden', 1);
            if (isset($cierreActual['cerrado'])){
                return array('id'=>0);
            } else {
                $cierre = R::load('cierreparking', $cierreActual['id']);
                $orden->cierreparking = $cierre;
            }
            $orden->usuario = $usuario;
            $orden->estado = $estadoorden;
        }        
        
        $orden->operador = $this->operador;
//        $orden->tipoOperacion = $this->tipoOperacion;
//        $orden->precioComitente = $this->precioComitente;
//        $orden->precioCartera = $this->precioCartera;
        $orden->numComitente = $this->numComitente;
        $orden->descripcionComitente = $this->descripcionComitente;
        $orden->especie = $this->especie;
        $orden->especieDescripcion = $this->especieDescripcion;
//        $orden->arancel = $this->arancel;
//        $orden->plazo = $this->plazo;
        $orden->parking = $this->parking;
        $orden->cantidad = $this->cantidad;
        $orden->sumaAprobado = $sumaAprobado['sumaAprobado'];
        $orden->codigo = $this->codigo;
//        $orden->brutoCliente = $this->brutoCliente;
        $orden->observaciones = $this->observaciones;
        $orden->moneda = $this->moneda;
        $orden->esMismaMoneda = $this->esMismaMoneda;

        $orden->esCableMep = $this->esCableMep;
//        $orden->numComitenteContraparte = $this->numComitenteContraparte;
        
        $orden->fechaActualizacion =  R::isoDateTime();
        
        $this->id = R::store($orden);       
        
        return $orden->export();
    }
    
    
    public function saveParkingEsco(){
        
//        $usuarioParam = $this->session->userdata('usuario');
//        $usuario = R::load('usuario', $usuarioParam['id']);
//        $usuario = R::load('usuario', $orden['usuario_id']);            

        $orden = R::load('parking', $this->orden);
        
        $mensaje = '';
        $parking = (float)$this->parking;
//        $parking = 1000;
               
        $fecha = new DateTime();
        $fechaStr = $fecha->format('Y-m-d H:i:s');
        
        $cierreActual = $this->getCierreActual();
        
        $sql1 = "SELECT sum(cantidad) as sumaAprobado
                FROM parking
                WHERE numComitente = {$this->numComitente}
                AND especie = '{$this->especie}'
                AND cierreparking_id = {$cierreActual['id']}
                AND estado_id IN (6,8)
                GROUP BY numComitente, especie;";
        $sumaAprobado = R::getRow($sql1);       
        $sumaAprobada = (float)$sumaAprobado['sumaAprobado'];
        
        $cantidad = (float)$orden['cantidad'];
        
        $cantidadTotal = $sumaAprobada + $cantidad;

        $orden->parking = $parking;
        $orden->sumaAprobado = $sumaAprobada;
        $orden->cantidadTotal = $cantidadTotal;
        
        // Si es misma moneda se tiene que aprobar
        // Y entonces cuando vaya a la grilla aprobar, 
        // La tarea automática, si tiene la DDJJ la vá a mandar a OMS
        // Si no es misma moneda, se tiene que analizar para aprobarla o no     
        if($orden['esMismaMoneda'] == 'true' || $orden['esCableMep'] == 'true'){
            
            ////////////////      Acá lo mando a OMS       /////////////////////
            $ordenes = array();
            array_push($ordenes, $orden);
            
            $this->load->model('EnvioAutorizacion_model');
            $this->EnvioAutorizacion_model->ordenes = $ordenes;
            $this->EnvioAutorizacion_model->numComitente = $orden['numComitente'];
            $this->EnvioAutorizacion_model->cierre = $cierreActual;
            $this->EnvioAutorizacion_model->tipo = 'aprobar';
            $respuesta = $this->EnvioAutorizacion_model->getEnviarAutorizacion();
            
            if($respuesta[1]['response'] == 'confirmado'){
                
                $mensaje = $respuesta[1]['response'].' '.$respuesta[1]['message'];
                
                $orden->autorizadores = 'Misma Moneda Autorización Automática';
                $estado = R::load('estadoorden', 8);
                $orden->estado = $estado;
            }else{
                
                $mensaje = $respuesta[1]['response'].' '.$respuesta[1]['message'];
                
                $orden->autorizadores = 'Misma Moneda Autorización Automática';
                $estado = R::load('estadoorden', 6);
                $orden->estado = $estado;
            }
            ///////////         FIN ENVIO A OMS          ///////////////////////
        }else{
            // Acá o lo aprueba o se vá a estado revisión, segu´n los totales
            // Y según cuánto dé la cuenta
            
//            Si PARKING == 0 || negativo : A analizar
//            Si CANTIDADTOTAL > PARKING : A analizar
            if($parking == 0 || $parking < 0 || $cantidadTotal > $parking){
                
                $fecha = new DateTime();
                $fechaStr = $fecha->format('Y-m-d H:i:s');

                $textoEmail = " <meta charset=\"utf-8\">
                                <p>Parking </p>
                                <p>Estimados: </p>
                                <p>La orden: {$orden['id']} requiere su análisis</p>
                                <p>Gracias!</p>
                                <p>Saludos.</p>";

                $this->load->model('Mailing_model');
                
//                $mailTo = array('micaela.petterson@allaria.com.ar', 'alejandro.oliveira@allaria.com.ar');
//                $mailTo = array('micaela.petterson@allaria.com.ar', 'ivana.cools@allaria.com.ar');

                $mailTo = array('micaela.petterson@allaria.com.ar', 
                    'ivana.cools@allaria.com.ar',
                    'cecilia.politi@allaria.com.ar',
                    'asilvestri@allaria.com.ar',
                    'fkatavic@allaria.com.ar',
                    'lsanfilippo@allaria.com.ar',
                    'lgiovannelli@allaria.com.ar',
                    'luisa.boiko@allaria.com.ar',
                    'nicolas.fredes@allaria.com.ar',
                    'ayelen.froiz@allaria.com.ar'
                    );



                $mailEnviado = $this->Mailing_model->enviarMail($textoEmail, 'Allaria Ledesma', 'no-responder@allaria.com.ar', 'Ordenes Parking', $mailTo, 'Ordenes Parking', array(), array());
                                
                
                
                $orden->autorizadores = 'Análisis Automático';
                $estado = R::load('estadoorden', 9);
                $orden->estado = $estado;
            }
//            Si CANTIDADTOTAL == PARKING : Aprobada
//            Si CANTIDADTOTAL < PARKING : Aprobada
            else if($cantidadTotal <= $parking){
                
                
                
                
                
                /////////////      Acá lo mando a OMS       ////////////////////
                $ordenes = array();
                array_push($ordenes, $orden);

                $this->load->model('EnvioAutorizacion_model');
                $this->EnvioAutorizacion_model->ordenes = $ordenes;
                $this->EnvioAutorizacion_model->numComitente = $orden['numComitente'];
                $this->EnvioAutorizacion_model->cierre = $cierreActual;
                $this->EnvioAutorizacion_model->tipo = 'aprobar';
                $respuesta = $this->EnvioAutorizacion_model->getEnviarAutorizacion();

                if($respuesta[1]['response'] == 'confirmado'){

                    $mensaje = $respuesta[1]['response'].' '.$respuesta[1]['message'];

                    $orden->autorizadores = 'Autorización Automática';
                    $estado = R::load('estadoorden', 8);
                    $orden->estado = $estado;
                }else{
                    
                    $mensaje = $respuesta[1]['response'].' '.$respuesta[1]['message'];

                    $orden->autorizadores = 'Autorización Automática';
                    $estado = R::load('estadoorden', 6);
                    $orden->estado = $estado;
                }
                ///////////         FIN ENVIO A OMS          ///////////////////
                
                
                
                
//                $orden->autorizadores = 'Autorización Automática';
//                $estado = R::load('estadoorden', 6);
//                $orden->estado = $estado;
            }

            
            
            
        }        
                
        $orden->fechaEstado = $fechaStr;

        $this->id = R::store($orden);   
        
        if($estado['id'] == 6 || $estado['id'] == 8){
//            $resultado = array('exito'=>1, 'resultado'=>'Ordenes '.$orden['id'].' enviadas para Aprobación Correctamente');
            $resultado = $mensaje;            
        }else{
            $resultado = $orden['id'].' '.' a analisis'; 
        }
        
        return $resultado;
    }
    
    
    public function getOrdenesParking(){
                
        if ($this->cierreparking_id == 0){
            $cierre = $this->getCierreActual();
            $this->cierreparking_id = $cierre['id'];
        }        
                
        $sql = "(select 
                l.id,
                l.numComitente,
                l.especie,
                l.cantidad,
                l.cantidadTotal,
                eo.estado 
         from   parking l
         join   estadoorden eo 
         on     l.estado_id = eo.id
         join   cierreparking c
         on     l.cierreparking_id = c.id
         join   usuario u
         on     l.usuario_id = u.id
         where  l.estado_id <> 4
         and    l.cierreparking_id = ?
         and    l.numComitente = ?
         order by l.id desc)"; 
        
        $resultado = R::getAll($sql, array($this->cierreparking_id, $this->numComitente));
        
        return $resultado;
    }
    
    
    
    public function comprobarOMS(){
         
        
        $cierre = $this->getCierreActual();
        $this->cierre = $cierre['id'];
        
        if($this->cierre){
            $cierre = R::load('cierreparking', $this->cierre);
        
            $fechaDesde = DateTime::createFromFormat('Y-m-d H:i:s',$cierre['fechahora'])->format('Y-m-d');
            $fechaHasta = date('Y-m-d', strtotime($fechaDesde . ' +1 day'));

            $sql = "(select 
                    l.id,
                    l.numComitente,
                    (SELECT MIN(conf.fechaHora) 
                    FROM operaciones.confirmacionddjj conf 
                    INNER JOIN operaciones.emailddjj e 
                    ON conf.mailId = e.id 
                    WHERE e.numComitente = l.numComitente 
                    AND conf.fechaHora BETWEEN '$fechaDesde' and '$fechaHasta' 
                    ) as fechaAceptacion
             from   parking l
             join   estadoorden eo 
             on     l.estado_id = eo.id
             join   cierreparking c
             on     l.cierreparking_id = c.id
             where  l.estado_id = 6
             and    l.cierreparking_id = ?
             order by l.id desc)"; 

            $resultado = R::getAll($sql, array($this->cierre));

            foreach($resultado as $k => $res){
                if($res['fechaAceptacion'] == null){
                    unset($resultado[$k]); 
                }
            }
            
        }
        
        
        return $resultado;
    }
    
    
    
    
    
    
    
    
    
    public function getOrden(){
        $orden = R::load('parking', $this->id);
        return $orden->export();
    }
    
    public function delOrden(){
        foreach ($this->ordenes as $id){
            $parking = R::load('parking', $id);
            R::trash($parking);
        }
    }
    
    
    public function grilla(){
        
        
        
        $sql = "select l.id,
                l.operador,
                l.numComitente,
                l.descripcionComitente,
                l.especie,
                l.especieDescripcion,
                l.cantidad,
                l.cantidadTotal,
                l.parking,
                CASE WHEN moneda = 1 THEN 'Mep' WHEN moneda = 2 THEN 'Cable' ELSE '' END AS moneda,
                CASE WHEN esMismaMoneda = 'true' THEN 'Si' ELSE 'No' END AS esMismaMoneda,
                CASE WHEN esCableMep = 'true' THEN 'Si' ELSE 'No' END AS esCableMep,
                l.codigo,
                l.observaciones,
                
                eo.estado,
                l.estado_id,
                l.fechaActualizacion,
                l.fechaEstado,
                c.fechahora as cierre

         from parking l
         left join cierreparking c
         on     l.cierreparking_id = c.id
         left join   estadoorden eo 
         on     l.estado_id = eo.id
         where  l.usuario_id = ?
         and    l.cierreparking_id = ?
         order by l.fechaActualizacion desc";

        $resultado = R::getAll($sql, array($this->usuario_id, $this->cierreparking_id));
        

        
        return $resultado;

    }
    
    public function enviarOrdenes(){
        $resultado = array('exito'=>1, 'resultado'=>'Ordenes Enviadas para Aprobación Correctamente');
        
        foreach ($this->ordenes as $id) {
            $orden = R::load('parking', $id);            
            $usuario = R::load('usuario', $orden['usuario_id']);            

            $fecha = new DateTime();
            $fechaStr = $fecha->format('Y-m-d H:i:s');

            $textoEmail = " <meta charset=\"utf-8\">
                            <p>Parking </p>
                            <p>Estimado: </p>
                            <p>Se ha generado una nueva orden Parking y enviado para su aprobación. {$id} {$usuario['nombre']} {$usuario['apellido']}</p>
                            <p>Saludos.</p>";

            $this->load->model('Mailing_model');
            
            if($orden['esMismaMoneda'] == 'true' || $orden['esCableMep'] == 'true'){
                $estado = R::load('estadoorden', 6);
                $orden->fechaEstado = $fechaStr;
                $orden->autorizadores = 'Autorización Automática';
            }else{
                $estado = R::load('estadoorden', 2);
            }

//            $mailTo = array('micaela.petterson@allaria.com.ar');
//            $respuesta = $this->Mailing_model->enviarMail($textoEmail, 'Allaria Ledesma', 'no-responder@allaria.com.ar', 'Ordenes Parking', $mailTo, 'Ordenes Parking', array(), array());

            $orden->estado = $estado;
            R::store($orden);
        }
        return $resultado;
    }
    
    public function anularOrdenes(){
        $estado = R::load('estadoorden', 4);
        $resultado = array('exito'=>1, 'resultado'=>'Ordenes Anuladas Correctamente');
        foreach ($this->ordenes as $id) {
            $orden = R::load('parking', $id);

            $usuario = R::load('usuario', $orden['usuario_id']);            
            
            
            $autorizador = R::load('usuario', $this->usuario); 
                        
            $fecha = new DateTime();
            $fechaStr = $fecha->format('Y-m-d H:i:s');

            $textoEmail = " <meta charset=\"utf-8\">
                            <p>Orden Parking Anulada </p>
                            <p>Estimado: </p>
                            <p>Se ha anulado una Orden Parking. {$id} {$usuario['nombre']} {$usuario['apellido']}, Comitente: {$orden['numComitente']}, Especie: {$orden['especie']} </p>
                            <p>Saludos.</p>";

            $this->load->model('Mailing_model');

            $mailTo = array('micaela.petterson@allaria.com.ar', $usuario['email']);
            
            
//            $mailTo = array('micaela.petterson@allaria.com.ar');
            $respuesta = $this->Mailing_model->enviarMail($textoEmail, 'Allaria Ledesma', 'no-responder@allaria.com.ar', 'Ordenes Parking', $mailTo, 'Ordenes Parking', array(), array());

            $orden->estado = $estado;
            $orden->fechaEstado = $fechaStr;
            R::store($orden);
        }
        return $resultado;
    }
    
    public function procesarOrdenes(){
        $estado = R::load('estadoorden', 3);
        $resultado = array('exito'=>1, 'resultado'=>'Ordenes Procesadas Correctamente');
        foreach ($this->ordenes as $id) {

            $orden = R::load('parking', $id);
            $procesador = R::load('usuario', $this->usuario); 

            $orden->estado = $estado;
            $orden->procesadores = $procesador['nombre'] . ' ' . $procesador['apellido'];

            R::store($orden);
        }
        return $resultado;
    }
    
    
    public function aprobarOrdenes(){
        $estado = R::load('estadoorden', 6);
        $ahora = new DateTime();
             
        $msj = '';
        
        foreach ($this->ordenes as $id) {
            $orden = R::load('parking', $id);            
            $usuario = R::load('usuario', $orden['usuario_id']);            
            
            $autorizador = R::load('usuario', $this->usuario); 
                        
                $fecha = new DateTime();
                $fechaStr = $fecha->format('Y-m-d H:i:s');

                $textoEmail = " <meta charset=\"utf-8\">
                                <p>Parking </p>
                                <p>Estimado: </p>
                                <p>Se ha aprobado una nueva orden Parking. {$id} {$usuario['nombre']} {$usuario['apellido']}</p>
                                <p>Saludos.</p>";

                $this->load->model('Mailing_model');
                
//                $mailTo = array('micaela.petterson@allaria.com.ar', 'alejandro.oliveira@allaria.com.ar');
//                $mailTo = array('micaela.petterson@allaria.com.ar');

//                $respuesta = $this->Mailing_model->enviarMail($textoEmail, 'Allaria Ledesma', 'no-responder@allaria.com.ar', 'Ordenes Parking', $mailTo, 'Ordenes Parking', array(), array());
                
                $orden->estado = $estado;
                $orden->fechaEstado = $fechaStr;
                $orden->autorizadores = $autorizador['nombre'] . ' ' . $autorizador['apellido'];
                R::store($orden);
                
                if($orden){
                    $msj .= 'Orden: ' . $orden['id'] . ' Aprobada. ';
                }
                
//            }
        }
        
        $resultado = array('exito'=>1, 'resultado'=>'Ordenes Aprobadas Correctamente', 'msj'=>$msj);

        
        return $resultado;
    }
    
    
    
    public function aprobarOrdenesOMS(){
        
        $estado = R::load('estadoorden', 8);
        $ahora = new DateTime();
             
        $msj = '';
        
        
//        var_dump($this->ordenes); die;
        
        foreach ($this->ordenes as $id) {
            $orden = R::load('parking', $id);            
            $usuario = R::load('usuario', $orden['usuario_id']);            
            
            $autorizador = R::load('usuario', $this->usuario); 
                        
                $fecha = new DateTime();
                $fechaStr = $fecha->format('Y-m-d H:i:s');

                $textoEmail = " <meta charset=\"utf-8\">
                                <p>Parking </p>
                                <p>Estimado: </p>
                                <p>Se ha aprobado una nueva orden Parking. {$id} {$usuario['nombre']} {$usuario['apellido']}</p>
                                <p>Saludos.</p>";

                $this->load->model('Mailing_model');
                
//                $mailTo = array('micaela.petterson@allaria.com.ar', 'alejandro.oliveira@allaria.com.ar');
//                $mailTo = array('micaela.petterson@allaria.com.ar');

//                $respuesta = $this->Mailing_model->enviarMail($textoEmail, 'Allaria Ledesma', 'no-responder@allaria.com.ar', 'Ordenes Parking', $mailTo, 'Ordenes Parking', array(), array());
                 
//                Autorización Automática
//                print_r($orden['autorizadores']); die;
                
                $orden->estado = $estado;
                $orden->fechaEstado = $fechaStr;                
//                $orden->autorizadores = $autorizador['nombre'] . ' ' . $autorizador['apellido'];
                R::store($orden);
                
                if($orden){
                    $msj .= 'Orden: ' . $orden['id'] . ' Aprobada. ';
                }
                
//            }
        }
        
        $resultado = array('exito'=>1, 'resultado'=>'Ordenes Aprobadas OMS Correctamente', 'msj'=>$msj);

        
        return $resultado;
    }
    
    
    
    
    
    
    
    public function controlarOrdenes(){
        $estado = R::load('estadoorden', 7);
        $resultado = array('exito'=>1, 'resultado'=>'Ordenes Controladas Correctamente');
        foreach ($this->ordenes as $id) {

            $orden = R::load('parking', $id);
            
            $procesador = R::load('usuario', $this->usuario); 

//            print_r('procesador');
//            print_r($procesador);
//            die;
            
            $orden->estado = $estado;
//            $orden->controladores = 'Asd';
            $orden->controladores = $procesador['nombre'] . ' ' . $procesador['apellido'];

            R::store($orden);
        }
        return $resultado;
    }
    
    
    public function getCierre(){
        $cierre = R::load('cierreparking', $this->cierreparking_id);
        return $cierre->export();
    }
    
    public function saveCierre(){
        $cierre = R::load('cierreparking', $this->cierreparking_id);
        $cierre->fechahora = $this->fechahora;
//        $cierre->plazos = $this->plazos;
//        $cierre->minimos = $this->minimos;
//        $cierre->colocacionPesos = $this->colocacionPesos;
//        $cierre->colocacionDolares = $this->colocacionDolares;
        /*
        $cierre->colocacionLebacsNov = $this->colocacionLebacsNov;
        $cierre->colocacionLebacsDic = $this->colocacionLebacsDic;
         * 
         */
//        $cierre->colocacionA2J9 = $this->colocacionA2J9;
        R::store($cierre);
        return $cierre->export();
    }
    
    public function delCierre(){
        $cierre = R::load('cierreparking', $this->cierreparking_id);
        R::trash($cierre);
    }
    
    public function getCierreActual(){
        $cierreActual = R::findOne('cierreparking', 'fechahora > NOW() order by fechahora' );
                
        if (is_null($cierreActual)){
            return array('cerrado'=>true);
        } else {
            return $cierreActual->export();
        }
    }
    
    public function getCierres(){
        $cierres = R::getAll('select * from cierreparking order by fechahora desc');
        return $cierres;
    }
    
    public function getMonedas(){
        $monedas = R::getAll('select * from moneda order by id');
        return $monedas;
    }
 
    public function grillaResumen(){        
        $ordenes_in = implode(',', $this->ordenes);
        $sql = "SELECT  plazo, 
                        moneda, 
                        count(*)      cantidadOrdenes, 
                        sum(cantidad) sumaCantidad
                        FROM    parking
                WHERE   id in ({$ordenes_in})
                GROUP BY plazo, moneda";
        $resultado = R::getAll($sql);
        return $resultado;
    }
    
    
    public function getFechaAceptacion(){
//        $ordenes_in = implode(',', $this->ordenes);
                
//        R::debug(true);
        
        $cierre = R::load('cierreparking', $this->cierre);
        
        $fechaDesde = DateTime::createFromFormat('Y-m-d H:i:s',$cierre['fechahora'])->format('Y-m-d');
        $fechaHasta = date('Y-m-d', strtotime($fechaDesde . ' +1 day'));
        
        $sql = "SELECT MIN(conf.fechaHora) AS fecha
        FROM operaciones.confirmacionddjj conf 
        INNER JOIN operaciones.emailddjj e 
        ON conf.mailId = e.id 
        WHERE e.numComitente = {$this->numComitente} 
        AND conf.fechaHora BETWEEN '$fechaDesde' and '$fechaHasta' 
        ";
        $resultado = R::getRow($sql);
        return $resultado;
    }
    
    
                
    
    
    public function controlGrilla(){
        
        $cierre = R::load('cierreparking', $this->cierreparking_id);

        
        $fechaDesde = DateTime::createFromFormat('Y-m-d H:i:s',$cierre['fechahora'])->format('Y-m-d');
        $fechaHasta = date('Y-m-d', strtotime($fechaDesde . ' +1 day'));
        
        $sql = "(select 
            
                l.id,
                l.operador,
                l.numComitente,
                l.descripcionComitente,
                l.codigo,
                l.especie,
                l.especieDescripcion,
                l.parking,
                l.cantidad,
                l.cantidadTotal,
                l.sumaAprobado,
                CASE WHEN moneda = 1 THEN 'Mep' WHEN moneda = 2 THEN 'Cable' ELSE '' END AS moneda,
                CASE WHEN esMismaMoneda = 'true' THEN 'Si' ELSE 'No' END AS esMismaMoneda,
                CASE WHEN esCableMep = 'true' THEN 'Si' ELSE 'No' END AS esCableMep,
                l.codigo,
                l.observaciones,
                l.autorizadores,
                l.procesadores,
                l.controladores,

                eo.estado,
                l.estado_id,
                l.fechaActualizacion,
                l.fechaEstado,
                c.fechahora as cierre,
                concat(u.apellido, ' ', u.nombre) as usuario,

                (SELECT MIN(conf.fechaHora) 
                FROM operaciones.confirmacionddjj conf 
                INNER JOIN operaciones.emailddjj e 
                ON conf.mailId = e.id 
                WHERE e.numComitente = l.numComitente 
                AND conf.fechaHora BETWEEN '$fechaDesde' and '$fechaHasta' 
                ) as fechaAceptacion


         from   ordenes.parking l
         join   estadoorden eo 
         on     l.estado_id = eo.id
         join   cierreparking c
         on     l.cierreparking_id = c.id
         join   usuario u
         on     l.usuario_id = u.id
         where  l.estado_id IN(3, 6, 7, 8, 9)
         and    l.cierreparking_id = ?
         order by l.fechaActualizacion desc)"; 
        
        $resultado = R::getAll($sql, array($this->cierreparking_id));//'1', 'Pendiente','2', 'Enviada','3', 'Procesada','4', 'Anulada','5', 'Retirada','6', 'Aprobada'
                
        return $resultado;
    }
    
    public function procesarGrilla(){
        $sql = "(
                select l.id,
                l.operador,
                l.numComitente,
                l.descripcionComitente,
                l.especie,
                l.especieDescripcion,
                l.parking,
                l.cantidad,
                l.cantidadTotal,
                l.sumaAprobado,
                CASE WHEN moneda = 1 THEN 'Mep' WHEN moneda = 2 THEN 'Cable' ELSE '' END AS moneda,
                CASE WHEN esMismaMoneda = 'true' THEN 'Si' ELSE 'No' END AS esMismaMoneda,
                CASE WHEN esCableMep = 'true' THEN 'Si' ELSE 'No' END AS esCableMep,                
                l.codigo,
                l.observaciones,
                l.moneda,
                l.esMismaMoneda,
                l.esCableMep,
                l.autorizadores,
                l.procesadores,

                eo.estado,
                l.estado_id,
                l.fechaActualizacion,
                l.fechaEstado,
                c.fechahora as cierre,
                concat(u.apellido, ' ', u.nombre) as usuario
                
         from   parking l
         left join   estadoorden eo 
         on     l.estado_id = eo.id
         left join   cierreparking c
         on     l.cierreparking_id = c.id
         left join   usuario u
         on     l.usuario_id = u.id
         where  l.estado_id IN (3, 6, 7)
         and    l.cierreparking_id = ?
         order by l.fechaActualizacion desc)"; 
        
        $resultado = R::getAll($sql, array($this->cierreparking_id));
        
        return $resultado;
    }

        
    
    public function aprobarGrilla(){
        $sql = "select l.id,
                l.operador,
                l.numComitente,
                l.descripcionComitente,
                l.especie,
                l.especieDescripcion,
                l.parking,
                l.cantidad,
                l.cantidadTotal,
                l.sumaAprobado,
                h.cantidad as cantidadsuma,
                CASE WHEN moneda = 1 THEN 'Mep' WHEN moneda = 2 THEN 'Cable' ELSE '' END AS moneda,
                CASE WHEN esMismaMoneda = 'true' THEN 'Si' ELSE 'No' END AS esMismaMoneda,
                CASE WHEN esCableMep = 'true' THEN 'Si' ELSE 'No' END AS esCableMep,
                l.codigo,
                l.observaciones,
                
                l.autorizadores,
                l.procesadores,
                
                eo.estado,
                l.estado_id,
                l.fechaActualizacion,
                l.fechaEstado,
                c.fechahora as cierre,
                concat(u.apellido, ' ', u.nombre) as usuario
                
         from   parking l
            LEFT JOIN 
            (SELECT SUM(cantidad) as cantidad, numComitente, especie, estado_id, cierreparking_id FROM parking GROUP BY numComitente, especie, estado_id, cierreparking_id) h
            ON l.numComitente = h.numComitente
            AND l.especie = h.especie
            AND l.estado_id = h.estado_id
            AND l.cierreparking_id = h.cierreparking_id            
         left join   estadoorden eo 
         on     l.estado_id = eo.id
         left join   cierreparking c
         on     l.cierreparking_id = c.id
         left join   usuario u
         on     l.usuario_id = u.id
         where  l.estado_id IN (2, 6, 3, 4, 7, 8, 9) 
         and    l.cierreparking_id = ?
         order by l.fechaActualizacion desc"; 
        
        $resultado = R::getAll($sql, array($this->cierreparking_id)); //'1', 'Pendiente','2', 'Enviada','3', 'Procesada','4', 'Anulada','5', 'Retirada','6', 'Aprobada'
        
        return $resultado;
    }
    
        
    public function enviarMercado(){
        $estado = R::load('estadoorden', 3);
        $resultado = array('exito'=>1, 'resultado'=>'Ordenes Enviadas Correctamente');
        foreach ($this->ordenes as $id) {
            $orden = R::load('parking', $id);
            $orden->estado = $estado;
            $orden->envio = 'M';
            $orden->fhenvio = R::isoDateTime();
            R::store($orden);
        }
        return $resultado;
    }

    
    public function formatearCuit($cuit){
        return substr((string) $cuit, 0, 2) . '-' . substr((string) $cuit, 2, 8) . '-' . substr((string) $cuit, 10, 1);
    }
    
    
    public function enviarMailsCierre(){
        
        $cierre = $this->getCierreActual();
        
        if (!$cierre['cerrado']){
            $fechaCierre = new DateTime($cierre['fechahora']);
            $ahora = new DateTime();
            $diferencia = $fechaCierre->diff($ahora);
            $minutos = $diferencia->d * 24 *60 + $diferencia->h * 60 + $diferencia->i;
            if ($minutos <= 10){
                $this->load->library('email');
                $sql = "select  *
                        from    usuario
                        where   id in (
                            select distinct usuario_id 
                            from   parking
                            where  cierreparking_id = ?
                            and    estado_id = 1
                            order by usuario_id)";
                $usuarios = R::getAll($sql, array($cierre['id']));
                foreach ($usuarios as $usuario) {
                    $sql = "select  id,
                                    tramo,
                                    numcomitente,
                                    moneda,
                                    plazo,
                                    comision,
                                    cantidad,
                                    precio,
                                    comitente,
                                    tipopersona,
                                    oficial,
                                    cuit
                            from    parking
                            where   usuario_id = ?
                            and     cierreparking_id = ?
                            and     estado_id = 1";
                    $ordenes = R::getAll($sql, array($usuario['id'], $cierre['id']));
                    $html = "
                            <html>
                                <p>Estimado/a {$usuario['nombre']} {$usuario['apellido']}</p>
                                <br>    
                                <p>Ud tiene las siguientes ordenes sin enviar a Backoffice:</p>
                                <br>
                                <table border='1'>
                                    <tr>
                                        <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Id</font></th>
                                        <!--
                                        <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Tramo</font></th>
                                        -->
                                        <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Nro Comitente</font></th>
                                        <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Moneda</font></th>
                                        <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Plazo</font></th>
                                        <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Comision</font></th>
                                        <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Cantidad</font></th>
                                        <!--
                                        <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Precio</font></th>
                                        -->
                                        <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Comitente</font></th>
                                        <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Tipo Persona</font></th>
                                        <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Oficial</font></th>
                                        <th bgcolor='#5D7B9D'><font color='#FFFFFF'>CUIT</font></th>
                                    </tr>";
                    foreach ($ordenes as $orden){
                        $html .= "
                                    <tr>
                                        <td align='right'>{$orden['id']}</td>
                                        <!--
                                        <td>{$orden['tramo']}</td>
                                        -->
                                        <td align='right'>{$orden['numcomitente']}</td>    
                                        <td align='center'>{$orden['moneda']}</td>
                                        <td align='right'>{$orden['plazo']}</td>
                                        <td align='right'>{$orden['comision']}</td>
                                        <td align='right'>{$orden['cantidad']}</td>
                                        <!--
                                        <td align='right'>{$orden['precio']}</td>
                                        -->
                                        <td>{$orden['comitente']}</td>
                                        <td>{$orden['tipopersona']}</td>
                                        <td>{$orden['oficial']}</td>
                                        <td>{$orden['cuit']}</td>
                                    </tr>
                                ";
                    }
                    $html .= "
                                </table>
                                <br>
                                <p>
                                    Recuerde que el cierre esta pactado para hoy a las {$fechaCierre->format('H:i:s')}
                                </p>
                                <br>
                                <p>
                                    Saludos
                                </p>
                            </html>
                            ";
                    $this->email->from('no-responder@allaria.com.ar', 'Sistema de Ordenes');
                    $this->email->to($usuario['email']); 
//                    $this->email->bcc(array('andy.glustman@allaria.com.ar'));
                    $this->email->bcc(array('micaela.petterson@allaria.com.ar'));

                    $this->email->subject('Tiene ordenes sin enviar a backoffice');
                    $this->email->message($html);	

                    $this->email->send();

                    echo $this->email->print_debugger();
                }
            }
        }
        
    }
    
    
    
    
    
    public function grabarExcel(){
                  
        $usuarioParam = $this->session->userdata('usuario');
                
        $orden = R::load('parking', $this->id);
        $cierre = R::load('cierreparking', $this->cierre);
        $usuario = R::load('usuario', $usuarioParam['id']);
        $estadoorden = R::load('estadoorden', 1);  
        
        

        
//        $this->moneda = '$';

//        $plazos = $this->Canje_model->getPlazos();
               
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

        

        
        $sheetname = 'Hoja1';
        
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
                        
            if($nombreHojas[0] == 'comitente'){
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
                                
                $numeroComitente = $sheet->getCellByColumnAndRow(0,$row)->getFormattedValue();                
                
                
                $numeroComitente = str_replace(',', '', $numeroComitente);
                $numeroComitente = str_replace('.', '', $numeroComitente);
                if (strlen(trim($numeroComitente)) > 0) {
                    
                    $orden = R::dispense('parking');
                    $orden->numcomitente = $numeroComitente;
                    
                    // Comitente
                    $this->load->model('Esco_model');
                    $this->Esco_model->numComitente = $numeroComitente;
                    $resultado = $this->Esco_model->getComitente();
                    
                    
                    if($resultado){
                        $orden->descripcionComitente = $resultado['comitente']; // Lo levanto del Esco
//                        if ($resultado['esFisico'] == -1){
//                            $orden->tipopersona = 'FISICA';
//                        } else {
//                            $orden->tipopersona = 'JURIDICA'; 
//                        }
//                        $orden->oficial = $resultado['oficial'];
//                        $orden->cuit = $resultado['cuit'];    
                    } else {
                        $error.="Número de comitente inválido en fila {$row} <br>";
                        $valido = false;
                    }
                    
                    $especie = null;
                    $especieDescripcion = null;
                    
                    if($sheet->getCellByColumnAndRow(1,$row)->getFormattedValue() != null){
                        $especieCod = $sheet->getCellByColumnAndRow(1,$row)->getFormattedValue();                
                        $this->load->model('Esco_model');
                        $this->Esco_model->codEspecie = $especieCod;
                        $resultado = $this->Esco_model->getEspecie();
                        $especieCodigo = $resultado['CodigoEspecie'];
                        $especieAbreviatura = $resultado['Abreviatura'];
                        $especieDescripcion = $resultado['Descripcion'];
                    }
                    
                    if($sheet->getCellByColumnAndRow(2,$row)->getFormattedValue() != null){                        
                        $especieDesc = $sheet->getCellByColumnAndRow(2,$row)->getFormattedValue();                
                        $this->load->model('Esco_model');
                        $this->Esco_model->especie = $especieDesc;
                        $resultado = $this->Esco_model->getEspecieDescripcion();  
                        $especieCodigo = $resultado['CodigoEspecie'];
                        $especieAbreviatura = $resultado['Abreviatura'];
                        $especieDescripcion = $resultado['Descripcion'];
                    }
                    
                    // Cantidad
                    $cantidad = $sheet->getCellByColumnAndRow(3,$row)->getOldCalculatedValue();     
                    if($cantidad == 0){
                      $cantidad = $sheet->getCellByColumnAndRow(3,$row)->getCalculatedValue();
                    }
                    $cantidad = (int)$cantidad;
                    
                    if(!is_int($cantidad)){
                        $error.="Cantidad inválida en fila {$row} <br>";
                        $valido = false;
                    }
                    
                    $moneda = $sheet->getCellByColumnAndRow(4,$row)->getCalculatedValue();     
                    if($moneda == 'Mep'){
                        $moneda = 1;
                    }else if($moneda == 'Cable'){
                        $moneda = 2;   
                    }else{
                        $error.="Moneda inválida en fila {$row} <br>";
                        $valido = false;
                    }
                    
                    
                    $esMismaMoneda = $sheet->getCellByColumnAndRow(5,$row)->getCalculatedValue();   
                    
                    
                    if($esMismaMoneda == 'Si'){
                        $esMismaMoneda = 'true';
                    }else if($esMismaMoneda == 'No'){
                        $esMismaMoneda = 'false';   
                    }else{
                        $error.="Valor es Misma Moneda inválido en fila {$row} <br>";
                        $valido = false;
                    }
                    
                    
                    
                    $esCableMep = $sheet->getCellByColumnAndRow(6,$row)->getCalculatedValue();     
                    if($esCableMep == 'Si'){
                        $esCableMep = 'true';
                    }else if($esCableMep == 'No'){
                        $esCableMep = 'false';   
                    }else{
                        $error.="Valor es Cable MEP inválido en fila {$row} <br>";
                        $valido = false;
                    }
                    
                    $observaciones = $sheet->getCellByColumnAndRow(7,$row)->getCalculatedValue();     
                    
                     
                    $orden->especie = $especieAbreviatura;
                    $orden->especieDescripcion = $especieDescripcion;
                    $orden->codigo = $especieCodigo;
                    $orden->cantidad = $cantidad;
                    $orden->moneda = $moneda;
                    $orden->esMismaMoneda = strval($esMismaMoneda);
                    $orden->esCableMep = $esCableMep;
                    $orden->observaciones = $observaciones;

                    $orden->operador = $usuarioParam['nombre'].' '.$usuarioParam['apellido'];
                    
                    $orden->estado = $estadoorden;
                    $orden->fechaActualizacion = R::isoDateTime();
                    $orden->usuario = $usuario;
                    $orden->cierreparking = $cierre;

//                    echo "<pre>";
//                    print_r($orden);
//                    echo "<pre>";
//                    die;
                    
                    if ($valido){
                        $this->id = R::store($orden);    
                    }

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

class Model_Parking extends RedBean_SimpleModel {
    private $prev;
    
    function open(){
        $this->prev = $this->bean->export();
    }
    
    function after_update(){
        if (json_encode($this->prev) != json_encode($this->bean->export())){
            $CI =& get_instance();
            $usuarioParam = $CI->session->userdata('usuario');
            $usuario = R::load('usuario', $usuarioParam['id']);
            $auditoria = R::dispense('auditoria');
            $auditoria->usuario = $usuario;
            $auditoria->table = 'parking';
            $auditoria->tableId = $this->bean->id;
            $auditoria->anterior = json_encode($this->prev);
            $auditoria->actual = json_encode($this->bean->export());
            R::store($auditoria);
        }
    }

    function after_delete(){
        $CI =& get_instance();
        $usuarioParam = $CI->session->userdata('usuario');
        $usuario = R::load('usuario', $usuarioParam['id']);
        $auditoria = R::dispense('auditoria');
        $auditoria->usuario = $usuario;
        $auditoria->table = 'parking';
        $auditoria->tableId = $this->prev['id'];
        $auditoria->anterior = json_encode($this->prev);
        $auditoria->actual = json_encode(array('operacion'=>'Registro Borrado'));
        R::store($auditoria);        
    }    
}

class Model_CierreParking extends RedBean_SimpleModel {
    private $prev;
    
    function open(){
        $this->prev = $this->bean->export();
    }
    
    function after_update(){
        if (json_encode($this->prev) != json_encode($this->bean->export())){
            $CI =& get_instance();
            $usuarioParam = $CI->session->userdata('usuario');
            $usuario = R::load('usuario', $usuarioParam['id']);
            $auditoria = R::dispense('auditoria');
            $auditoria->usuario = $usuario;
            $auditoria->table = 'cierreparking';
            $auditoria->tableId = $this->bean->id;
            $auditoria->anterior = json_encode($this->prev);
            $auditoria->actual = json_encode($this->bean->export());
            R::store($auditoria);
        }
    }
    
    function after_delete(){
        $CI =& get_instance();
        $usuarioParam = $CI->session->userdata('usuario');
        $usuario = R::load('usuario', $usuarioParam['id']);
        $auditoria = R::dispense('auditoria');
        $auditoria->usuario = $usuario;
        $auditoria->table = 'cierreparking';
        $auditoria->tableId = $this->prev['id'];
        $auditoria->anterior = json_encode($this->prev);
        $auditoria->actual = json_encode(array('operacion'=>'Registro Borrado'));
        R::store($auditoria);        
    }
}
