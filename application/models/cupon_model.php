<?php

require_once APPPATH."/third_party/PHPExcel.php";

class Cupon_model extends CI_Model{
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
    
    public $cierrecupon_id;
    public $fechahora;
    
    public function saveOrden(){
        $usuarioParam = $this->session->userdata('usuario');
        $usuario = R::load('usuario', $usuarioParam['id']);

        $orden = R::load('cupon', $this->id);
        if ($orden->id == 0 ){
            $estadoorden = R::load('estadoorden', 1);
            $cierreActual = $this->getCierreActual();
            if (isset($cierreActual['cerrado'])){
                return array('id'=>0);
            } else {
                $cierre = R::load('cierrecupon', $cierreActual['id']);
                $orden->cierrecupon = $cierre;
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
        $orden = R::load('cupon', $this->id);
        return $orden->export();
    }
    
    public function delOrden(){
        foreach ($this->ordenes as $id){
            $orden = R::load('cupon', $id);
            R::trash($orden);
        }
    }
    
    
    public function getPlazos(){
        if ($this->cierrecupon_id > 0){
            $sql = "select id, plazo, especie from plazocupon where cierrecupon_id = ? order by plazo";
            $plazos = R::getAll($sql, array($this->cierrecupon_id));
        } else {
            $sql = "select id, plazo, especie from plazocupon where cierrecupon_id = (SELECT id FROM cierrecupon where fechahora > NOW() order by fechahora limit 0,1) order by plazo";
            $plazos = R::getAll($sql);
        }
        return $plazos;
    }
    
    
    public function getEspecie(){
                
        $sql = 'select * from plazocupon where id = ? order by plazo';
        $plazos = R::getRow($sql, array($this->plazo));
        
        return $plazos['especie'];
    }
    
    
    public function getPlazosEspecies(){
        if ($this->cierrecupon_id > 0){
            $sql = 'select id, plazo, CONCAT(plazo, " ", especie) as especie from plazocupon where cierrecupon_id = ? order by plazo';
            $plazos = R::getAll($sql, array($this->cierrecupon_id));
        } else {
            $sql = 'select id, plazo, CONCAT(plazo, " ", especie) as especie from plazocupon where cierrecupon_id = (SELECT id FROM cierrecupon where fechahora > NOW() order by fechahora limit 0,1) order by plazo';
            $plazos = R::getAll($sql);
        }
        return $plazos;
    }
    
    public function getPlazosBono(){
        if ($this->plazocupon_id > 0){
            $sql = 'select id, plazocupon_id, plazodescripcion, descripcion, tipo, cierrecupon_id from plazocuponbono where plazocupon_id = ? order by id';
            $plazos = R::getAll($sql, array($this->plazocupon_id));
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
         from   cupon p
         join   estadoorden eo 
         on     p.estado_id = eo.id
         join   cierrecupon c
         on     p.cierrecupon_id = c.id
         LEFT join   usuario u
         on     p.usuario_id = u.id
         
         LEFT JOIN plazocupon pla
         on     pla.id = p.plazo

         where  p.usuario_id = ?
         and    p.cierrecupon_id = ?
         order by p.fhmodificacion desc"; 
        
        $resultado = R::getAll($sql, array($this->usuario_id, $this->cierrecupon_id));
        
        return $resultado;
    }
    
    public function enviarOrdenes(){        
        $estado = R::load('estadoorden', 2);
        $resultado = array('exito'=>1, 'resultado'=>'Ordenes Enviadas Correctamente');
        $ahora = new DateTime();
        foreach ($this->ordenes as $id) {
            $orden = R::load('cupon', $id);
            $fechaCierre = new DateTime($orden->cierrecupon->fechahora);
            if ($fechaCierre < $ahora){
                $resultado = array('exito'=>0, 'resultado'=>'Algunas ordenes no se pudieron enviar porque ya estaban cerradas');
            } else {
                $orden->estado = $estado;
                R::store($orden);
            }
        }
        return $resultado;
    }
    
    public function anularOrdenes(){        
        $estado = R::load('estadoorden', 4);
        $resultado = array('exito'=>1, 'resultado'=>'Ordenes Anuladas Correctamente');
        foreach ($this->ordenes as $id) {
            $orden = R::load('cupon', $id);
            $orden->estado = $estado;
            R::store($orden);
        }
        return $resultado;
    }
    
    
    public function getCierre(){
        
        $cierreBean = R::load('cierrecupon', $this->cierrecupon_id);
        $cierre = $cierreBean->export();
        $sql = "select * from plazocupon where cierrecupon_id = ? order by moneda, plazo";
        $plazos = R::getAll($sql, array($cierreBean->id));
        $cierre['plazos'] = $plazos;
        return $cierre;
    }
    
    public function saveCierre(){
        $cierre = R::load('cierrecupon', $this->cierrecupon_id);
        $cierre->fechahora = $this->fechahora;
        $cierre->pausarCierre = $this->pausarCierre;        
//        $cierre->instrumento = $this->instrumento;
        R::store($cierre);
        
        foreach ((array) $this->plazos as $plazoItem) {
            $plazo = R::load('plazocupon', $plazoItem['id']);
            $plazo->moneda = $plazoItem['moneda'];
            $plazo->plazo = $plazoItem['plazo'];
            $plazo->especie = $plazoItem['especie'];
            $plazo->colocacion = $plazoItem['colocacion'];
//            $plazo->tituloC = $plazoItem['tituloC'];
//            $plazo->tituloNCJ = $plazoItem['tituloNCJ'];
//            $plazo->tituloNCF = $plazoItem['tituloNCF'];
            $plazo->cierrecupon = $cierre;
            R::store($plazo);
        }
        
        foreach ((array) $this->plazosBorrar as $plazocupon_id){
            $plazo = R::load('plazo', $plazocupon_id);
            R::trash($plazo);
        }
        
        return $cierre->export();
    }
    
    public function comprobarEstadoCierre(){
        
        $sql = "SELECT pausarCierre FROM cierrecupon where id = ?";
        $cierre = R::getRow($sql, array($this->cierre));
        $result = $cierre['pausarCierre'];
                
        return $result;
    }
    
    public function delCierre(){
        $cierre = R::load('cierrecupon', $this->cierrecupon_id);
        $cierre->ownPlazocupon = array();
        R::store($cierre);
        R::trash($cierre);
    }
    
    public function getCierreActual(){
        $cierreActual = R::findOne('cierrecupon', 'fechahora > NOW() order by fechahora' );
        if (is_null($cierreActual)){
            return array('cerrado'=>true);
        } else {
            $this->cierrecupon_id = $cierreActual->id;
            $cierre = $this->getCierre();
            return $cierre;
        }
    }
    
    public function getCierres(){
        $cierres = R::getAll('select * from cierrecupon order by fechahora desc');
        return $cierres;
    }
 
    public function grillaResumen(){        
        $ordenes_in = implode(',', $this->ordenes);
        $sql = "SELECT  count(*)      cantidadOrdenes, 
                        sum(cantidad) sumaCantidad
                        FROM    cupon
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
         from   cupon p
         join   estadoorden eo 
         on     p.estado_id = eo.id
         join   cierrecupon c
         on     p.cierrecupon_id = c.id
         join   usuario u
         on     p.usuario_id = u.id
         
         LEFT JOIN plazocupon pla
         on     pla.id = p.plazo

         where  p.estado_id <> 1
         and    p.cierrecupon_id = ?
         order by p.fhmodificacion desc)"; 
        
        $resultado = R::getAll($sql, array($this->cierrecupon_id));
        
        return $resultado;
    }
    
    public function previewSantander(){
        $ordenes_in = implode(',', $this->ordenes);
        $sql = "SELECT  p.cierre_id,
                        sum(p.cantidad) sumaCantidad
                        FROM    cupon p                        
                WHERE   p.id in ($ordenes_in)
                GROUP BY p.cierre_id";
        $resultado = R::getAll($sql);
        return $resultado;
    }
 
    public function enviarSantander(){        
        $estado = R::load('estadoorden', 3);
        $resultado = array('exito'=>1, 'resultado'=>'Ordenes Enviadas Correctamente');
        foreach ($this->ordenes as $id) {
            $orden = R::load('cupon', $id);
            $orden->estado = $estado;
            $orden->envio = 'S';
            $orden->fhenvio = R::isoDateTime();
            R::store($orden);
        }
        return $resultado;
    }
    
    
//    public function previewMercado(){
//        $ordenes_in = implode(',', $this->ordenes);
//        $sql = "SELECT  cierrecupon_id,
//                        tipopersona,
//                        numcomitente,
//                        cantidad,
//                        cuit,
//                        FROM    cupon
//                WHERE   id in ({$ordenes_in})
//                ORDER BY cierrecupon_id ";
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
//        $nombreZip = FCPATH . 'generadas/' . date('Y-m-d-H-i-s') . '-cupones.zip';
//        $this->zip->archive($nombreZip);
//        
//        return array('uris'=>array(base_url() . 'generadas/' . basename($nombreZip)));
//    }
    
    
    public function previewMercado(){
        $ordenes_in = implode(',', $this->ordenes);
        
        $sql = "SELECT  cierrecupon_id,
                        tipopersona,
                        numcomitente,
                        tipopersona,
                        cantidad,
                        arancel,
                        plazo,
                        cuit,
                        posicion
                        FROM    cupon
                WHERE   id in ({$ordenes_in})
                ORDER BY cierrecupon_id, plazo ";
        $resultado = R::getAll($sql);
        
//        $contenido = array();
        $colocacionAnterior = 0;
        $contenidoInd = 0;
        foreach ($resultado as $indice=>$fila){

            
            $plazo = R::findOne('plazocupon', 'cierrecupon_id = ? and id = ?', array($fila['cierrecupon_id'], $fila['plazo']));    
//            $plazo = R::getRow('SELECT * FROM plazocupon WHERE cierrecupon_id = '{$fila['cierrecupon_id']}' AND , $fila['plazo']));    
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
            
            $archivo = date('Y-m-d-H-i-s') . '-' . $data['colocacion'] . '-cupon.txt';
            $this->zip->add_data($archivo, $data['datos']);
            //file_put_contents($archivo, $data['datos']);
            //array_push($uris, base_url() . 'generadas/' . basename($archivo));
        }
        $nombreZip = FCPATH . 'generadas/' . date('Y-m-d-H-i-s') . '-cupon.zip';
        $this->zip->archive($nombreZip);
        
        return array('uris'=>array(base_url() . 'generadas/' . basename($nombreZip)));
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    public function enviarMercado(){        
        $estado = R::load('estadoorden', 3);
        $resultado = array('exito'=>1, 'resultado'=>'Ordenes Enviadas Correctamente');
        foreach ($this->ordenes as $id) {
            $orden = R::load('cupon', $id);
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
                            from   cupon
                            where  cierrecupon_id = ?
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
                            from    cupon
                            where   usuario_id = ?
                            and     cierrecupon_id = ?
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
        $resultado = "CUPON ";
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
    
    
    public function getPlazocuponbonoPorDescripcion(){
        $sql = "select id, plazocupon_id, plazodescripcion, descripcion, tipo, cierrecupon_id "
                . "from plazocuponbono "
                . "where descripcion = ? AND cierrecupon_id = ? AND plazocupon_id = ?";
        $plazos = R::getRow($sql, array($this->bonoNombre, $this->cierre, $this->plazo)); 

        return $plazos;
    }
    
    public function getPlazoPorNombre(){
        $sql = "select id, moneda, plazo, especie, colocacion, cierrecupon_id "
                . "from plazocupon "
                . "where plazo = ? AND cierrecupon_id = ?";
        $plazos = R::getRow($sql, array($this->plazoNombre, $this->cierre)); 

        return $plazos;
    }
    
    public function grabarExcel(){
                  
        $usuarioParam = $this->session->userdata('usuario');

        $orden = R::load('cupon', $this->id);
        $cierre = R::load('cierrecupon', $this->cierre);
        $usuario = R::load('usuario', $usuarioParam['id']);
        $estadoorden = R::load('estadoorden', 1);        
        $this->moneda = '$';

        $plazos = $this->Cupon_model->getPlazos();
               
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
                    
                    $orden = R::dispense('cupon');
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
                   
                    $this->Cupon_model->plazoNombre = $plazoNombre;
                    $resultadoPlazo = $this->Cupon_model->getPlazoPorNombre();
                    
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

                    $this->Cupon_model->plazo = $plazo;
                    $this->Cupon_model->bonoNombre = $bonoNombre;
                    $resultado = $this->Cupon_model->getPlazocuponbonopordescripcion();

                    
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
                    $orden->cierrecupon = $cierre;

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

class Model_Cupon extends RedBean_SimpleModel {
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
            $auditoria->table = 'cupon';
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
        $auditoria->table = 'cupon';
        $auditoria->tableId = $this->prev['id'];
        $auditoria->anterior = json_encode($this->prev);
        $auditoria->actual = json_encode(array('operacion'=>'Registro Borrado'));
        R::store($auditoria);        
    }    
}

class Model_Cierrecupon extends RedBean_SimpleModel {
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
            $auditoria->table = 'cierrecupon';
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
        $auditoria->table = 'cierrecupon';
        $auditoria->tableId = $this->prev['id'];
        $auditoria->anterior = json_encode($this->prev);
        $auditoria->actual = json_encode(array('operacion'=>'Registro Borrado'));
        R::store($auditoria);        
    }
}