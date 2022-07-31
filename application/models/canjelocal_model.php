<?php

require_once APPPATH."/third_party/PHPExcel.php";

class Canjelocal_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public $id;
    public $numComitente;
//    public $comision;
    public $cantidad;
    public $arancel;
//    public $cantidadACrecer;
//    public $precio;
//    public $segundaParte;
//    public $cantidadAcrecerSegunda;
    public $comitente;
    public $tipoPersona;
    public $oficial;
    public $cuit;

    public $ordenes;
    
    public $usuario_id;
    
    public $cierrecanjelocal_id;
    public $fechahora;
    
    public function saveOrden(){
        $usuarioParam = $this->session->userdata('usuario');
        $usuario = R::load('usuario', $usuarioParam['id']);

        $orden = R::load('canjelocal', $this->id);
        if ($orden->id == 0 ){
            $estadoorden = R::load('estadoorden', 1);
            $cierreActual = $this->getCierreActual();
            if (isset($cierreActual['cerrado'])){
                return array('id'=>0);
            } else {
                $cierre = R::load('cierrecanjelocal', $cierreActual['id']);
                $orden->cierrecanjelocal = $cierre;
            }
            $orden->usuario = $usuario;
            $orden->estado = $estadoorden;
        }
        $orden->numcomitente = $this->numComitente;
//        $orden->comision = $this->comision;
        $orden->cantidad = $this->cantidad;
        $orden->plazo = $this->plazo;
        $orden->arancel = $this->arancel;
        $orden->bono = $this->bono;
        $orden->bonoNombre = $this->bonoNombre;
        $orden->tipo = $this->tipo;
//        $orden->cantidadACrecer = $this->cantidadACrecer;
//        $orden->precio = $this->precio;
//        $orden->segundaParte = $this->segundaParte;
//        $orden->cantidadAcrecerSegunda = $this->cantidadAcrecerSegunda;
        $orden->comitente = $this->comitente;
        $orden->tipopersona = $this->tipoPersona;
        $orden->oficial = $this->oficial;
        $orden->cuit = (double) $this->cuit;
        $orden->posicion = $this->posicion;
        $orden->estaConfirmado = $this->estaConfirmado;
        $orden->fhmodificacion =  R::isoDateTime();
        $this->id = R::store($orden);
        
        return $orden->export();
    }
    
    public function getOrden(){
        $orden = R::load('canjelocal', $this->id);
        return $orden->export();
    }
    
    public function delOrden(){
        foreach ($this->ordenes as $id){
            $orden = R::load('canjelocal', $id);
            R::trash($orden);
        }
    }
    
    
    public function getPlazos(){
        if ($this->cierrecanjelocal_id > 0){
            $sql = "select id, plazo, especie from plazocanjelocal where cierrecanjelocal_id = ? order by plazo";
            $plazos = R::getAll($sql, array($this->cierrecanjelocal_id));
        } else {
            $sql = "select id, plazo, especie from plazocanjelocal where cierrecanjelocal_id = (SELECT id FROM cierrecanjelocal where fechahora > NOW() order by fechahora limit 0,1) order by plazo";
            $plazos = R::getAll($sql);
        }
        return $plazos;
    }
    
    
    public function getEspecie(){
                
        $sql = 'select * from plazocanjelocal where id = ? order by plazo';
        $plazos = R::getRow($sql, array($this->plazo));
        
        return $plazos['especie'];
    }
    
    
    public function getPlazosEspecies(){
        if ($this->cierrecanjelocal_id > 0){
            $sql = 'select id, plazo, CONCAT(plazo, " ", especie) as especie from plazocanjelocal where cierrecanjelocal_id = ? order by plazo';
            $plazos = R::getAll($sql, array($this->cierrecanjelocal_id));
        } else {
            $sql = 'select id, plazo, CONCAT(plazo, " ", especie) as especie from plazocanjelocal where cierrecanjelocal_id = (SELECT id FROM cierrecanjelocal where fechahora > NOW() order by fechahora limit 0,1) order by plazo';
            $plazos = R::getAll($sql);
        }
        return $plazos;
    }
    
    public function getPlazosBono(){
        if ($this->plazocanjelocal_id > 0){
            $sql = 'select id, plazocanjelocal_id, plazodescripcion, descripcion, tipo, cierrecanjelocal_id from plazocanjelocalbono where plazocanjelocal_id = ? order by id';
            $plazos = R::getAll($sql, array($this->plazocanjelocal_id));
        } 
        return $plazos;
    }
    
    public function grilla(){
        $sql = "select p.id,
                p.numComitente,
                p.cantidad,
                p.arancel,
                pla.especie,
                pla.plazo,
                p.tipo,
                p.comitente,
                p.tipoPersona,
                p.oficial,
                concat(u.apellido, ' ', u.nombre) as usuario,
                p.cuit,
                p.posicion,
                eo.estado,
                p.estado_id,
                p.fhmodificacion,
                p.envio,
                p.fhenvio,
                p.estaConfirmado
         from   canjelocal p
         join   estadoorden eo 
         on     p.estado_id = eo.id
         join   cierrecanjelocal c
         on     p.cierrecanjelocal_id = c.id
         LEFT join   usuario u
         on     p.usuario_id = u.id
         
         LEFT JOIN plazocanjelocal pla
         on     pla.id = p.plazo

         where  p.usuario_id = ?
         and    p.cierrecanjelocal_id = ?
         order by p.fhmodificacion desc"; 
        
        $resultado = R::getAll($sql, array($this->usuario_id, $this->cierrecanjelocal_id));
        
        return $resultado;
    }
    
    public function enviarOrdenes(){        
        $estado = R::load('estadoorden', 2);
        $resultado = array('exito'=>1, 'resultado'=>'Ordenes Enviadas Correctamente');
        $ahora = new DateTime();
        foreach ($this->ordenes as $id) {
            $orden = R::load('canjelocal', $id);
            $fechaCierre = new DateTime($orden->cierrecanjelocal->fechahora);
            if ($fechaCierre < $ahora){
                $resultado = array('exito'=>0, 'resultado'=>'Algunas ordenes no se pudieron enviar porque ya estaban cerradas');
            } else {
                $orden->estado = $estado;
                R::store($orden);
            }
        }
        return $resultado;
    }
    
    /*
    public function anularOrdenes(){        
        $estado = R::load('estadoorden', 4);
        $resultado = array('exito'=>1, 'resultado'=>'Ordenes Anuladas Correctamente');
        foreach ($this->ordenes as $id) {
            $orden = R::load('canjelocal', $id);
            $orden->estado = $estado;
            R::store($orden);
        }
        return $resultado;
    }
    */
    
        public function anularOrdenes(){
        $estado = R::load('estadoorden', 4);
        $resultado = array('exito'=>1, 'resultado'=>'Ordenes Anuladas Correctamente');
        foreach ($this->ordenes as $id) {
            $orden = R::load('canjelocal', $id);
            $usuario = R::load('usuario', $orden['usuario_id']);            
            
            $autorizador = R::load('usuario', $this->usuario); 
                        
            $fecha = new DateTime();
            $fechaStr = $fecha->format('Y-m-d H:i:s');

            $textoEmail = " <meta charset=\"utf-8\">
                            <p>Orden Canje Local Anulada </p>
                            <p>Estimado: </p>
                            <p>Se ha anulado una Orden de Canje Local. {$id} {$usuario['nombre']} {$usuario['apellido']}, Comitente: {$orden['numComitente']}, Especie: {$orden['especie']} </p>
                            <p>Saludos.</p>";

            $this->load->model('Mailing_model');

//            $mailTo = array('micaela.petterson@allaria.com.ar', $usuario['email']);
            $mailTo = array('micaela.petterson@allaria.com.ar', $usuario['email']);
            
//            $mailTo = array('micaela.petterson@allaria.com.ar');
            $respuesta = $this->Mailing_model->enviarMail($textoEmail, 'Allaria Ledesma', 'no-responder@allaria.com.ar', 'Ordenes Canje Local', $mailTo, 'Ordenes Canje Local', array(), array());

            $orden->estado = $estado;
            $orden->fechaEstado = $fechaStr;
            R::store($orden);
        }
        return $resultado;
    }

    
    
    public function getCierre(){
        
        $cierreBean = R::load('cierrecanjelocal', $this->cierrecanjelocal_id);
        $cierre = $cierreBean->export();
        $sql = "select * from plazocanjelocal where cierrecanjelocal_id = ? order by moneda, plazo";
        $plazos = R::getAll($sql, array($cierreBean->id));
        $cierre['plazos'] = $plazos;
        return $cierre;
    }
    
    public function saveCierre(){
        $cierre = R::load('cierrecanjelocal', $this->cierrecanjelocal_id);
        $cierre->fechahora = $this->fechahora;
        $cierre->pausarCierre = $this->pausarCierre;        
//        $cierre->instrumento = $this->instrumento;
        R::store($cierre);
        
        foreach ((array) $this->plazos as $plazoItem) {
            $plazo = R::load('plazocanjelocal', $plazoItem['id']);
            $plazo->moneda = $plazoItem['moneda'];
            $plazo->plazo = $plazoItem['plazo'];
            $plazo->especie = $plazoItem['especie'];
            $plazo->colocacion = $plazoItem['colocacion'];
//            $plazo->tituloC = $plazoItem['tituloC'];
//            $plazo->tituloNCJ = $plazoItem['tituloNCJ'];
//            $plazo->tituloNCF = $plazoItem['tituloNCF'];
            $plazo->cierrecanjelocal = $cierre;
            R::store($plazo);
        }
        
        foreach ((array) $this->plazosBorrar as $plazocanjelocal_id){
            $plazo = R::load('plazo', $plazocanjelocal_id);
            R::trash($plazo);
        }
        
        return $cierre->export();
    }
    
    public function comprobarEstadoCierre(){
        
        $sql = "SELECT pausarCierre FROM cierrecanjelocal where id = ?";
        $cierre = R::getRow($sql, array($this->cierre));
        $result = $cierre['pausarCierre'];
                
        return $result;
    }
    
    public function delCierre(){
        $cierre = R::load('cierrecanjelocal', $this->cierrecanjelocal_id);
        $cierre->ownPlazocanjelocal = array();
        R::store($cierre);
        R::trash($cierre);
    }
    
    public function getCierreActual(){
        $cierreActual = R::findOne('cierrecanjelocal', 'fechahora > NOW() order by fechahora' );
        if (is_null($cierreActual)){
            return array('cerrado'=>true);
        } else {
            $this->cierrecanjelocal_id = $cierreActual->id;
            $cierre = $this->getCierre();
            return $cierre;
        }
    }
    
    public function getCierres(){
        $cierres = R::getAll('select * from cierrecanjelocal order by fechahora desc');
        return $cierres;
    }
 
    public function grillaResumen(){        
        $ordenes_in = implode(',', $this->ordenes);
        $sql = "SELECT  count(*)      cantidadOrdenes, 
                        sum(cantidad) sumaCantidad
                        FROM    canjelocal
                WHERE   id in ({$ordenes_in})
                /* GROUP BY plazo, moneda */";
        $resultado = R::getAll($sql);
        return $resultado;
    }
    
    public function procesarGrilla(){
        $sql = "(select 
                p.id,
                p.numComitente,
                p.cantidad,
                p.arancel,
                pla.especie,
                pla.plazo,
                p.tipo,
                p.bonoNombre,
                p.comitente,
                p.tipoPersona,
                p.oficial,
                concat(u.apellido, ' ', u.nombre) as usuario,
                p.cuit,
                p.posicion,
                eo.estado,
                p.estado_id,
                p.fhmodificacion,
                p.envio,
                p.fhenvio,
                p.estaConfirmado
         from   canjelocal p
         join   estadoorden eo 
         on     p.estado_id = eo.id
         join   cierrecanjelocal c
         on     p.cierrecanjelocal_id = c.id
         join   usuario u
         on     p.usuario_id = u.id
         
         LEFT JOIN plazocanjelocal pla
         on     pla.id = p.plazo

         where  p.estado_id <> 1
         and    p.cierrecanjelocal_id = ?
         order by p.fhmodificacion desc)"; 
        
        $resultado = R::getAll($sql, array($this->cierrecanjelocal_id));
        
        return $resultado;
    }
    
    public function previewSantander(){
        $ordenes_in = implode(',', $this->ordenes);
        $sql = "SELECT  p.cierre_id,
                        sum(p.cantidad) sumaCantidad
                        FROM    canjelocal p                        
                WHERE   p.id in ($ordenes_in)
                GROUP BY p.cierre_id";
        $resultado = R::getAll($sql);
        return $resultado;
    }
 
    public function enviarSantander(){        
        $estado = R::load('estadoorden', 3);
        $resultado = array('exito'=>1, 'resultado'=>'Ordenes Enviadas Correctamente');
        foreach ($this->ordenes as $id) {
            $orden = R::load('canjelocal', $id);
            $orden->estado = $estado;
            $orden->envio = 'S';
            $orden->fhenvio = R::isoDateTime();
            R::store($orden);
        }
        return $resultado;
    }
    
    
//    public function previewMercado(){
//        $ordenes_in = implode(',', $this->ordenes);
//        $sql = "SELECT  cierrecanjelocal_id,
//                        tipopersona,
//                        numcomitente,
//                        cantidad,
//                        cuit,
//                        FROM    canjelocal
//                WHERE   id in ({$ordenes_in})
//                ORDER BY cierrecanjelocal_id ";
//        $resultado = R::getAll($sql);
//        
//        $contenido = "";
//        $colocacionAnterior = 0;
//        $contenidoInd = 0;
//        $colocacion = 'colocacion';
//        foreach ($resultado as $indice=>$fila){
//            
//            
//            $contenido[$contenidoInd]['colocacion'] = $colocacion;
//            $contenido[$contenidoInd]['datos'] .= $colocacion . "\t\t" . $fila['cantidad'] . "\t\t" . $fila['numcomitente'] . "\t\t" . $fila['cuit']  . "\t\t\t\t" . $fila['tipopersona'] . "\t\r\n";
//            
//        }
//        $this->load->library('zip');
//        foreach ($contenido as $data){
//            //$archivo = FCPATH . 'generadas/' . date('Y-m-d-H-i-s') . '-' . $data['colocacion'] . '-lebacs.dat';
//            $archivo = date('Y-m-d-H-i-s') . '-' . $data['colocacion'] . '-bonos.txt';
//            $this->zip->add_data($archivo, $data['datos']);
//            //file_put_contents($archivo, $data['datos']);
//            //array_push($uris, base_url() . 'generadas/' . basename($archivo));
//        }
//        $nombreZip = FCPATH . 'generadas/' . date('Y-m-d-H-i-s') . '-canjelocales.zip';
//        $this->zip->archive($nombreZip);
//        
//        return array('uris'=>array(base_url() . 'generadas/' . basename($nombreZip)));
//    }
    
    
    public function previewMercado(){
        $ordenes_in = implode(',', $this->ordenes);
        
        $sql = "SELECT  cierrecanjelocal_id,
                        tipopersona,
                        numcomitente,
                        tipopersona,
                        cantidad,
                        arancel,
                        plazo,
                        cuit,
                        posicion
                        FROM    canjelocal
                WHERE   id in ({$ordenes_in})
                ORDER BY cierrecanjelocal_id, plazo ";
        $resultado = R::getAll($sql);
        
//        $contenido = array();
        $colocacionAnterior = 0;
        $contenidoInd = 0;
        foreach ($resultado as $indice=>$fila){

            
            $plazo = R::findOne('plazocanjelocal', 'cierrecanjelocal_id = ? and id = ?', array($fila['cierrecanjelocal_id'], $fila['plazo']));    
//            $plazo = R::getRow('SELECT * FROM plazocanjelocal WHERE cierrecanjelocal_id = '{$fila['cierrecanjelocal_id']}' AND , $fila['plazo']));    
////            $plazo = R::findOne('plazo', 'cierre_id = ? and moneda = ? and plazo = ?', array($fila['cierre_id'], $fila['moneda'], $fila['plazo']));


            
            $titulo = utf8_decode("CANASTA DE LETRAS Y BONOS");

            
            switch ($fila['plazo']){
                
                case '18':
                    $titulo = utf8_decode("CANASTA DE LETRAS Y BONOS");
                    break;                
                
//                case '5266':
//                    $titulo = utf8_decode("BONO DEL TESORO NACIONAL EN PESOS AJUSTADO POR CER 1% vto. 5 agosto 2021 BONCER 2021");
////                    switch($fila['bono']){
////                    
////                        case 'BONCER 2021 (T)':
////                            $titulo = utf8_decode("BONO DEL TESORO NACIONAL EN PESOS AJUSTADO POR CER 1% vto. 5 agosto 2021 BONCER 2021");
////                        break;
////                        case 'BONCER 2022 (U)':
////                            $titulo = utf8_decode("BONO DEL TESORO NACIONAL EN PESOS AJUSTADO POR CER 1,2% vto. 18 de marzo de 2022 BONCER 2022");
////                        break;
////                        case 'BONCER 2023 (X)':
////                            $titulo = utf8_decode("BONO DEL TESORO NACIONAL EN PESOS AJUSTADO POR CER 1,4% vto. 25 de marzo de 2023 BONCER 2023");
////                        break;
////                        case 'BONCER 2024 (Y)':
////                            $titulo = utf8_decode("BONO DEL TESORO NACIONAL EN PESOS AJUSTADO POR CER 1,5% vto. 25 de marzo de 2024 BONCER 2024");
////                        break;
////                    
////                    }
//                    break;
                
            }

            
            if ($fila['tipopersona'] == 'FISICA'){
                $tipoPersona = 'Persona Fisica';
            } else {
                $tipoPersona = 'Persona Juridica';
            }

            $colocacion = $plazo->colocacion;
            if ($indice == 0){
                $colocacionAnterior = $colocacion;
            }
            
            if ($colocacion <> $colocacionAnterior){
                $contenidoInd++;
                $colocacionAnterior = $colocacion;
            }
            
            
            $contenido[$contenidoInd]['colocacion'] = $colocacion;
            $contenido[$contenidoInd]['datos'] .= $colocacion . "\t" . $titulo . "\t\t" . $fila['cantidad'] . "\t\t" . $fila['numcomitente'] . "\t\t" . $fila['cuit']  . "\t\t\t\t" . $tipoPersona .  "\t\t\tCUIT\r\n";
        }
        
        $this->load->library('zip');

        foreach ($contenido as $data){
            
            $archivo = date('Y-m-d-H-i-s') . '-' . $data['colocacion'] . '-canjelocal.txt';
            $this->zip->add_data($archivo, $data['datos']);
            //file_put_contents($archivo, $data['datos']);
            //array_push($uris, base_url() . 'generadas/' . basename($archivo));
        }
        $nombreZip = FCPATH . 'generadas/' . date('Y-m-d-H-i-s') . '-canjelocal.zip';
        $this->zip->archive($nombreZip);
        
        return array('uris'=>array(base_url() . 'generadas/' . basename($nombreZip)));
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    public function enviarMercado(){        
        $estado = R::load('estadoorden', 3);
        $resultado = array('exito'=>1, 'resultado'=>'Ordenes Enviadas Correctamente');
        foreach ($this->ordenes as $id) {
            $orden = R::load('canjelocal', $id);
            $orden->estado = $estado;
            $orden->envio = 'M';
            $orden->fhenvio = R::isoDateTime();
            R::store($orden);
        }
        return $resultado;
    }

    
    public function previewArchivo(){
        $ordenes_in = implode(',', $this->ordenes);
        
        $sql = "SELECT  
                        cl.cierrecanjelocal_id,
                        cl.tipopersona,
                        cl.numcomitente,
                        cl.tipopersona,
                        cl.cantidad,
                        cl.arancel,
                        clpla.plazo as plazo,
                        clpla.especie as especie,
                        cl.tipo,
                        cl.cuit,
                        cl.posicion
                FROM    canjelocal cl
                LEFT JOIN plazocanjelocal clpla
                ON cl.plazo = clpla.id
                AND cl.cierrecanjelocal_id = clpla.cierrecanjelocal_id
                WHERE cl.id in ({$ordenes_in})
                ORDER BY cl.cierrecanjelocal_id, cl.plazo ";
        $resultados = R::getAll($sql);
        
        
//        [cierrecanjelocal_id] => 1
//        [tipopersona] => FISICA
//        [numcomitente] => 24149
//        [cantidad] => 31000
//        [arancel] => 
//        [plazo] => 1
//        [cuit] => 27045331240
//        [posicion] => 31000.0000000000
        
                
        $fechaArchivo = new DateTime('NOW');
        $fechaArchivo = $fechaArchivo->format('Ymd');
        
        $fechaDMA = new DateTime('NOW');
        $fechaDMA = $fechaDMA->format('ymd');
                
        $fechaLimite = new DateTime('NOW');
        $fechaLimite = $fechaLimite->format('dm');
        
        
        $app_id = uniqid();//give each process a unique ID for differentiation
        $log_name = "canjelocaarchivo".$fechaArchivo.$app_id.".txt";
        $log = fopen('/var/www/ordenes/application/downloads/'.$log_name,'a');
        
        
        $tipoRegistro = 0;
        $fecha = $fechaDMA;
        $idArchivo = 'FTFAOT';
        $codigoParticipante = '0006';
//        $libre53 = (str_pad((string) $libre53,53," ",STR_PAD_LEFT));
        
        //00Aftfaot    20200821800001000000022
        $hora = '800001';
        $total = count($resultados);       
        $total = $total + 1;
        
        
        
        $total = (str_pad((string) $total,9,"0",STR_PAD_LEFT));
        $linea0 = '00Aftfaot    '.$fechaArchivo.$hora.$total;  

        $linea99 = '99Aftfaot    '.$fechaArchivo.$hora.$total;  

        
        //0200820FTFAOT0006
        $linea1 = $tipoRegistro.$fecha.$idArchivo.$codigoParticipante;      
        
//        $cuentaEmisora = 5859;
        
//        $lineas = '';
        
        foreach($resultados as $resultado){
            
//            $datosComitente = R::getRow("SELECT * FROM cuentacomitente WHERE numComitente = {$cheque['NumComitente']}");
//            $datosEncabezado = R::getRow('select * from encabezado');
               
//            $monto = abs($cheque['MontoNominal']);
//            $monto = number_format($monto, 2, '.', '');
//            $monto = (str_pad((string) $monto,15,"0",STR_PAD_LEFT));
//            
//            $codigoCVSA = (str_pad((string) $cheque['Abreviatura'],13,"0",STR_PAD_LEFT));
//            
//            $numComitente = (str_pad((string) $cheque['NumComitente'],9,"0",STR_PAD_LEFT));
//            
//            $cuentaReceptora = (str_pad((string) $datosComitente['depositante'],4,"0",STR_PAD_LEFT));
//            $subCuentaReceptora = (str_pad((string) $datosComitente['comitenteReceptor'],9,"0",STR_PAD_LEFT));
            
            $tipoRegistroDato = 1;
            $tipoTratamiento = 'D';
            $tipoInstruccion = 'E';
            $numeroCuenta = '0006';
            $numComitente = $resultado['numcomitente'];
            $numeroSubCuenta = (str_pad((string) $numComitente,9,"0",STR_PAD_LEFT));
            $codigoEspecie = $resultado['plazo'];
            $cant = number_format($resultado['cantidad'], 7, '.', '');
            $cantidad = (str_pad((string) $cant,19,"0",STR_PAD_LEFT));
            $numeroCuentaContraparte = 4005;
            $numeroTipo = $resultado['tipo'];
            $numeroSubCuentaContraparte = (str_pad((string) $numeroTipo,9,"0",STR_PAD_LEFT));
            $solicitudReservaSaldo = 'N';
            $nivelPrioridad = '00';
            $limiteValidez = $fechaLimite;
            $fechaEjecución = $fechaLimite;
            $solicitudMatching = 'N';
            
            $lineas .= $tipoRegistroDato."'".$tipoTratamiento."'".$tipoInstruccion."'".$numeroCuenta."'".$numeroSubCuenta."'".$codigoEspecie."'".$cantidad."'".$numeroCuentaContraparte."'".$numeroSubCuentaContraparte."'".$solicitudReservaSaldo."'".$nivelPrioridad."'".$limiteValidez."'".$fechaEjecución."'".$solicitudMatching.chr(13).chr(10);
        }
//        print_r($linea1); die;
        
        $log_line = join(array($linea0, chr(13).chr(10), $linea1, chr(13).chr(10), $lineas, $linea99 ) );
                
        fwrite($log, $log_line);
        fclose($log);
        $contenido = file_get_contents("/var/www/ordenes/application/downloads/" . $log_name);

//        echo json_encode($log_name);
        
        return $log_name;
        
        
        
       /* 
        
        
        
        
        
        
//        $contenido = array();
        $colocacionAnterior = 0;
        $contenidoInd = 0;
        foreach ($resultado as $indice=>$fila){

            
            $plazo = R::findOne('plazocanjelocal', 'cierrecanjelocal_id = ? and id = ?', array($fila['cierrecanjelocal_id'], $fila['plazo']));    
//            $plazo = R::getRow('SELECT * FROM plazocanjelocal WHERE cierrecanjelocal_id = '{$fila['cierrecanjelocal_id']}' AND , $fila['plazo']));    
////            $plazo = R::findOne('plazo', 'cierre_id = ? and moneda = ? and plazo = ?', array($fila['cierre_id'], $fila['moneda'], $fila['plazo']));


            
            $titulo = utf8_decode("CANASTA DE LETRAS Y BONOS");

            
            switch ($fila['plazo']){
                
                case '18':
                    $titulo = utf8_decode("CANASTA DE LETRAS Y BONOS");
                    break;                
                
//                case '5266':
//                    $titulo = utf8_decode("BONO DEL TESORO NACIONAL EN PESOS AJUSTADO POR CER 1% vto. 5 agosto 2021 BONCER 2021");
////                    switch($fila['bono']){
////                    
////                        case 'BONCER 2021 (T)':
////                            $titulo = utf8_decode("BONO DEL TESORO NACIONAL EN PESOS AJUSTADO POR CER 1% vto. 5 agosto 2021 BONCER 2021");
////                        break;
////                        case 'BONCER 2022 (U)':
////                            $titulo = utf8_decode("BONO DEL TESORO NACIONAL EN PESOS AJUSTADO POR CER 1,2% vto. 18 de marzo de 2022 BONCER 2022");
////                        break;
////                        case 'BONCER 2023 (X)':
////                            $titulo = utf8_decode("BONO DEL TESORO NACIONAL EN PESOS AJUSTADO POR CER 1,4% vto. 25 de marzo de 2023 BONCER 2023");
////                        break;
////                        case 'BONCER 2024 (Y)':
////                            $titulo = utf8_decode("BONO DEL TESORO NACIONAL EN PESOS AJUSTADO POR CER 1,5% vto. 25 de marzo de 2024 BONCER 2024");
////                        break;
////                    
////                    }
//                    break;
                
            }

            
            if ($fila['tipopersona'] == 'FISICA'){
                $tipoPersona = 'Persona Fisica';
            } else {
                $tipoPersona = 'Persona Juridica';
            }

            $colocacion = $plazo->colocacion;
            if ($indice == 0){
                $colocacionAnterior = $colocacion;
            }
            
            if ($colocacion <> $colocacionAnterior){
                $contenidoInd++;
                $colocacionAnterior = $colocacion;
            }
            
            
            $contenido[$contenidoInd]['colocacion'] = $colocacion;
            $contenido[$contenidoInd]['datos'] .= $colocacion . "\t" . $titulo . "\t\t" . $fila['cantidad'] . "\t\t" . $fila['numcomitente'] . "\t\t" . $fila['cuit']  . "\t\t\t\t" . $tipoPersona .  "\t\t\tCUIT\r\n";
        }
        
        $this->load->library('zip');

        foreach ($contenido as $data){
            
            $archivo = date('Y-m-d-H-i-s') . '-' . $data['colocacion'] . '-canjelocal.txt';
            $this->zip->add_data($archivo, $data['datos']);
            //file_put_contents($archivo, $data['datos']);
            //array_push($uris, base_url() . 'generadas/' . basename($archivo));
        }
        $nombreZip = FCPATH . 'generadas/' . date('Y-m-d-H-i-s') . '-canjelocal.zip';
        $this->zip->archive($nombreZip);
        
        return array('uris'=>array(base_url() . 'generadas/' . basename($nombreZip)));
        
        */
        
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
                            from   canjelocal
                            where  cierrecanjelocal_id = ?
                            and    estado_id = 1
                            order by usuario_id)";
                $usuarios = R::getAll($sql, array($cierre['id']));
                foreach ($usuarios as $usuario) {
                    $sql = "select  id,
                                    numcomitente,
                                    cantidad,
                                    arancel,
                                    comitente,
                                    tipopersona,
                                    oficial,
                                    cuit,
                                    posicion
                            from    canjelocal
                            where   usuario_id = ?
                            and     cierrecanjelocal_id = ?
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
                                        <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Nro Comitente</font></th>
                                        <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Cantidad</font></th>
                                        <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Comitente</font></th>
                                        <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Tipo Persona</font></th>
                                        <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Oficial</font></th>
                                        <th bgcolor='#5D7B9D'><font color='#FFFFFF'>CUIT</font></th>
                                    </tr>";
                    foreach ($ordenes as $orden){
                        $html .= "
                                    <tr>
                                        <td align='right'>{$orden['id']}</td>
                                        <td align='right'>{$orden['numcomitente']}</td>    
                                        <td align='right'>{$orden['cantidad']}</td>
                                        <td align='right'>{$orden['arancel']}</td>  
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
                    $this->email->bcc(array('flagama@allariaycia.com','favio.fernandez@allariaycia.com','alejandro.oliveira@allariaycia.com'));

                    $this->email->subject('Tiene ordenes sin enviar a backoffice');
                    $this->email->message($html);	

                    $this->email->send();

                    echo $this->email->print_debugger();
                }
            }
        }
        
    }
    
    private function nombreArchivo($nombrePlanilla){
        $resultado = "Canjelocal ";
        $inicio  = strpos($nombrePlanilla, '-');
        $fin    = strpos($nombrePlanilla, '-', $inicio + 1);
        $largo = $fin - $inicio;
        //$plazo = substr($nombrePlanilla, $inicio + 1, $largo - 1);
        //$resultado .= $plazo;
        $resultado .= ' DIAS ';
        if (substr($nombrePlanilla, 1, 1) <> 'P'){
            $resultado .= 'USD ' . substr($nombrePlanilla, 1, 1) . ' ';
        }
        if (substr($nombrePlanilla, 0, 1) == 'C'){
            $resultado .= 'TC ';
        } else {
            $resultado .= 'TNC ';
        }
        if (substr($nombrePlanilla, 2, 1) == 'J'){
            $resultado .= 'PJ-';
        } else {
            $resultado .= 'PF-';
        }
        $numerador = substr($nombrePlanilla, $fin + 1);
        $resultado .= $numerador;
        
        return $resultado;
    }
    
    
    public function getPlazocanjelocalbonoPorDescripcion(){
        $sql = "select id, plazocanjelocal_id, plazodescripcion, descripcion, tipo, cierrecanjelocal_id "
                . "from plazocanjelocalbono "
                . "where descripcion = ? AND cierrecanjelocal_id = ? AND plazocanjelocal_id = ?";
        $plazos = R::getRow($sql, array($this->bonoNombre, $this->cierre, $this->plazo)); 

        return $plazos;
    }
    
    public function getPlazoPorNombre(){
        $sql = "select id, moneda, plazo, especie, colocacion, cierrecanjelocal_id "
                . "from plazocanjelocal "
                . "where plazo = ? AND cierrecanjelocal_id = ?";
        $plazos = R::getRow($sql, array($this->plazoNombre, $this->cierre)); 

        return $plazos;
    }
    
    
public function procesarExcel(){
                  
        $usuarioParam = $this->session->userdata('usuario');

        $cierre = R::load('cierrecanjelocal', $this->cierre);
        $usuario = R::load('usuario', $usuarioParam['id']);
        $estadoorden = R::load('estadoorden', 1);        
               
        $this->load->helper('file');
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/tmp/';
       
        try {
            $inputFileName = $uploadDir . $this->archivo;
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);            
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $worksheetList = $objReader->listWorksheetNames($inputFileName);
            $objPHPExcel = $objReader->load($inputFileName);
        } catch(Exception $e) {
            die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }

//        $sheetname = 'Hoja1';
        $sheetname = $worksheetList[0];        
        $sheet = $objPHPExcel->getSheetByName($sheetname);
                
        if($sheet){
            $highestRow = $sheet->getHighestDataRow();
                        
            $valido = true;
            $error = '';
            
//            R::freeze(true);
//            R::begin();
            
            $ordenes = array();
            
            for ($row = 1; $row <= $highestRow; $row++){
                                
                $orden = $sheet->getCellByColumnAndRow(0,$row)->getFormattedValue();                
//                      echo "<pre>";
//                    print_r($orden);
//                    echo "<pre>";
//                    die;
                 array_push($ordenes, $orden);
                
            }      
            
//            $lasOrdenes = implode(",", $ordenes);
            
            if($ordenes){
                $resultado = array('resultado'=>'OK', 'ordenes'=>$ordenes);
                return $resultado;

            }

        } else {
            $error = 'Títulos inválidos.';
            $resultado = array('resultado'=>'Error', 'mensaje'=>$error);
            return $resultado;
        }
    }        
    
    
    
    
    public function grabarExcel(){
                  
        $usuarioParam = $this->session->userdata('usuario');

        $orden = R::load('canjelocal', $this->id);
        $cierre = R::load('cierrecanjelocal', $this->cierre);
        $usuario = R::load('usuario', $usuarioParam['id']);
        $estadoorden = R::load('estadoorden', 1);        
        $this->moneda = '$';

        $plazos = $this->Canjelocal_model->getPlazos();
               
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
                        
            if($nombreHojas[0] == 'comitente' && $nombreHojas[1] == 'cantidad'){
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
                    
                    $orden = R::dispense('canjelocal');
                    $orden->numcomitente = $numeroComitente;
                    
                    // Comitente
                    $this->load->model('Esco_model');
                    $this->Esco_model->numComitente = $numeroComitente;
                    $resultado = $this->Esco_model->getComitente();
                    
                    
                    if($resultado){
                        $orden->comitente = $resultado['comitente']; // Lo levanto del Esco
                        if ($resultado['esFisico'] == -1){
                            $orden->tipopersona = 'FISICA';
                        } else {
                            $orden->tipopersona = 'JURIDICA'; 
                        }
                        $orden->oficial = $resultado['oficial'];
                        $orden->cuit = $resultado['cuit'];    
                    } else {
                        $error.="Número de comitente inválido en fila {$row} <br>";
                        $valido = false;
                    }
 
                    // Cantidad
                    $cantidad = $sheet->getCellByColumnAndRow(1,$row)->getOldCalculatedValue();     
                    if($cantidad == 0){
                      $cantidad = $sheet->getCellByColumnAndRow(1,$row)->getCalculatedValue();
                    }
                    $cantidad = (int)$cantidad;
                    
                    if(!is_int($cantidad)){
                        $error.="Cantidad inválida en fila {$row} <br>";
                        $valido = false;
                    }
                    
//                    $numero=1000;
//                    if(!($cantidad%$numero==0)){
//                        $error.="Cantidad no es múltiplo de 1000 en fila {$row} <br>";
//                        $valido = false;
//                    }
                    
                    // Arancel
                    $arancel = $sheet->getCellByColumnAndRow(2,$row)->getOldCalculatedValue();     
                    if($arancel == 0){
                      $arancel = $sheet->getCellByColumnAndRow(2,$row)->getCalculatedValue();
                    }
                    $arancel = (int)$arancel;
                                        
                    if(!is_numeric($arancel)){
                        $error.="Arancel inválida en fila {$row} <br>";
                        $valido = false;
                    }
                    
                    //Plazo
                    $plazoNombre = $sheet->getCellByColumnAndRow(3,$row)->getCalculatedValue();
                   
                    $this->Canjelocal_model->plazoNombre = $plazoNombre;
                    $resultadoPlazo = $this->Canjelocal_model->getPlazoPorNombre();
                    
                    $plazo = 0;
                    
                    if($resultadoPlazo){
                        $plazo = $resultadoPlazo['id'];
                    }else{
                        $error.="Plazo invalido en fila {$row} <br>";
                        $valido = false;                    
                    }
                    
                    
//                    if (!in_array($plazo, $plazos)){
//                        $error.="Plazo invalido en fila {$row} <br>";
//                        $valido = false;
//                    }        
                    
                    // Opcion                   
                    $bonoNombre = $sheet->getCellByColumnAndRow(4,$row)->getFormattedValue();

                    $this->Canjelocal_model->plazo = $plazo;
                    $this->Canjelocal_model->bonoNombre = $bonoNombre;
                    $resultado = $this->Canjelocal_model->getPlazocanjelocalbonopordescripcion();

                    
                    $bono = 0;
                    $tipo = 0;
                    
                    if($resultado){
                        $bono = $resultado['id'];
                        $tipo = $resultado['tipo'];
                    }else{
                        $error.="Plazo y bono invalido en fila {$row} <br>";
                        $valido = false;                    
                    }
                    

                    
                    
                    //Posicion
                    $posicion = $sheet->getCellByColumnAndRow(5,$row)->getOldCalculatedValue();     
                    if($posicion == 0){
                      $posicion = $sheet->getCellByColumnAndRow(5,$row)->getCalculatedValue();
                    }
                    
                    $estaConfirmado = $sheet->getCellByColumnAndRow(6,$row)->getValue();     
                    
                    
                    $orden->cantidad = $cantidad;
                    $orden->arancel = $arancel;
                    $orden->bono = $bono;
                    $orden->bonoNombre = $bonoNombre;
                    $orden->tipo = $tipo;

                    $orden->posicion = $posicion;
                    $orden->estaConfirmado = $estaConfirmado;

                    $orden->plazo = $plazo;
                    $orden->estado = $estadoorden;
                    $orden->fhmodificacion = R::isoDateTime();
                    $orden->usuario = $usuario;
                    $orden->cierrecanjelocal = $cierre;

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

class Model_Canjelocal extends RedBean_SimpleModel {
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
            $auditoria->table = 'canjelocal';
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
        $auditoria->table = 'canjelocal';
        $auditoria->tableId = $this->prev['id'];
        $auditoria->anterior = json_encode($this->prev);
        $auditoria->actual = json_encode(array('operacion'=>'Registro Borrado'));
        R::store($auditoria);        
    }    
}

class Model_Cierrecanjelocal extends RedBean_SimpleModel {
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
            $auditoria->table = 'cierrecanjelocal';
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
        $auditoria->table = 'cierrecanjelocal';
        $auditoria->tableId = $this->prev['id'];
        $auditoria->anterior = json_encode($this->prev);
        $auditoria->actual = json_encode(array('operacion'=>'Registro Borrado'));
        R::store($auditoria);        
    }
}