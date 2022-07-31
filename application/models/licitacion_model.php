<?php

require_once APPPATH."/third_party/PHPExcel.php";

class Licitacion_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public $id;
    public $tramo;
    public $numComitente;
    public $tipoInversor;
    public $moneda;
    public $cable;
    /*
    public $plazo;
    */
    public $comision;
    public $cantidad;
    public $precio;
    public $comitente;
//    public $tipoPersona;
    public $oficial;
    public $cuit;
    public $ordenes;
    
    public $usuario_id;
    
    public $cierrelicitacion_id;
    public $fechahora;
    public $plazos;
    public $plazosBorrar;
    
    public function saveOrden(){
                
        $usuarioParam = $this->session->userdata('usuario');
        $usuario = R::load('usuario', $usuarioParam['id']);

        $orden = R::load('licitacion', $this->id);
        $ordenAnterior = $orden;
        if ($orden->id == 0 ){
            $estadoorden = R::load('estadoorden', 1);
            $cierreActual = $this->getCierreActual();
            if (isset($cierreActual['cerrado'])){
                return array('id'=>0);
            } else {
                $cierre = R::load('cierrelicitacion', $cierreActual['id']);
                $orden->cierrelicitacion = $cierre;
            }
            $orden->usuario = $usuario;
            $orden->estado = $estadoorden;
        }
        $orden->tramo = $this->tramo;
        $orden->numcomitente = $this->numComitente;
        $orden->tipoInversor = $this->tipoInversor;
        $orden->moneda = $this->moneda;
        $orden->cable = $this->cable;
        
        $orden->plazo = $this->plazo;
        $orden->especie = $this->especie;
        
        $orden->comision = (float)$this->comision;
        $orden->cantidad = (int)$this->cantidad;
        $orden->precio = (float)$this->precio;
        $orden->comitente = $this->comitente;
        $orden->tipopersona = $this->tipoPersona;
        $orden->oficial = $this->oficial;
        $orden->cuit = (int)$this->cuit;
        $orden->fhmodificacion =  R::isoDateTime();
        $this->id = R::store($orden);
        
        return $orden->export();
    }
    
    public function getOrden(){
        $orden = R::load('licitacion', $this->id);
        return $orden->export();
    }
    
    public function delOrden(){
        foreach ($this->ordenes as $id){
            $orden = R::load('licitacion', $id);
            R::trash($orden);
        }
    }
    
    
    public function grilla(){
        $sql = "select b.id,
                b.tramo,
                b.numComitente,
                b.moneda,
                b.cable,
                b.plazo,
                b.especie,
                pl.moneda as plazomoneda, 
                pl.plazo as plazoplazo, 
                pl.especie as plazoespecie,
                b.comision,
                b.cantidad,
                b.precio,
                b.comitente,
                
                b.oficial,
                b.cuit,
                eo.estado,
                b.estado_id,
                b.fhmodificacion,
                c.fechahora as cierre,
                b.usuario_id
         from   licitacion b
         join   estadoorden eo 
         on     b.estado_id = eo.id
         join   cierrelicitacion c
         on     b.cierrelicitacion_id = c.id

         LEFT JOIN plazolicitacion pl
         ON b.cierrelicitacion_id = pl.cierrelicitacion_id     
         AND b.plazo = pl.id    

         where  b.usuario_id = ?
         and    b.cierrelicitacion_id = ?
         order by b.fhmodificacion desc"; 
        
        $resultado = R::getAll($sql, array($this->usuario_id, $this->cierrelicitacion_id));
        
        return $resultado;

    }
    
    public function enviarOrdenes(){        
        $estado = R::load('estadoorden', 2);
        $resultado = array('exito'=>1, 'resultado'=>'Ordenes Enviadas Correctamente');
        $ahora = new DateTime();
        foreach ($this->ordenes as $id) {
            $orden = R::load('licitacion', $id);
            $fechaCierre = new DateTime($orden->cierrelicitacion->fechahora);
            if ($fechaCierre < $ahora){
                $resultado = array('exito'=>0, 'resultado'=>'Algunas ordenes no se pudieron enviar porque ya estaban cerradas');
            } else {
                $orden->estado = $estado;
                R::store($orden);
            }
        }
        return $resultado;
    }
    
    public function retirarOrdenes(){        
        $estado = R::load('estadoorden', 5);
        $resultado = array('exito'=>1, 'resultado'=>'Ordenes Retiradas Correctamente');
        foreach ($this->ordenes as $id) {
            $orden = R::load('licitacion', $id);
            $orden->fechaRetiro = R::isoDateTime();
            $orden->estado = $estado;
            R::store($orden);
        }
        return $resultado;
    }
    
    public function anularOrdenes(){        
        $estado = R::load('estadoorden', 4);
        $resultado = array('exito'=>1, 'resultado'=>'Ordenes Anuladas Correctamente');
        foreach ($this->ordenes as $id) {
            $orden = R::load('licitacion', $id);
            $orden->estado = $estado;
            R::store($orden);
        }
        return $resultado;
    }
    
    
    public function getCierre(){
        $cierreBean = R::load('cierrelicitacion', $this->cierrelicitacion_id);
        $cierre = $cierreBean->export();
        $sql = "select * from plazolicitacion where cierrelicitacion_id = ? order by moneda, plazo";
        $plazos = R::getAll($sql, array($cierreBean->id));
        $cierre['plazos'] = $plazos;
        return $cierre;
    }
    
    public function saveCierre(){
        $cierre = R::load('cierrelicitacion', $this->cierrelicitacion_id);
        $cierre->fechahora = $this->fechahora;
        R::store($cierre);
        
        foreach ((array) $this->plazos as $plazoItem) {
            $plazo = R::load('plazolicitacion', $plazoItem['id']);
            $plazo->moneda = $plazoItem['moneda'];
            $plazo->plazo = $plazoItem['plazo'];
            $plazo->especie = $plazoItem['especie'];
            $plazo->colocacionC = $plazoItem['colocacionC'];
            $plazo->colocacionNC = $plazoItem['colocacionNC'];
            $plazo->tituloC = $plazoItem['tituloC'];
            $plazo->tituloNC = $plazoItem['tituloNC'];
            $plazo->cierrelicitacion = $cierre;
            R::store($plazo);
        }
        
        foreach ((array) $this->plazosBorrar as $plazo_id){
            $plazo = R::load('plazolicitacion', $plazo_id);
            R::trash($plazo);
        }
        
        return $cierre->export();
    }
    
    public function delCierre(){
        $plazos = R::find('plazo', array($this->cierrelicitacion_id));
        R::trashAll($plazos);
        $cierre = R::load('cierrelicitacion', $this->cierrelicitacion_id);
        R::trash($cierre);
    }
    
    public function getCierreActual(){
        $cierreActual = R::findOne('cierrelicitacion', 'fechahora > NOW() order by fechahora' );
                
        if (is_null($cierreActual)){
            return array('cerrado'=>true);
        } else {
            $this->cierrelicitacion_id = $cierreActual->id;
            $cierre = $this->getCierre();
            return $cierre;
        }
    }
    
    public function getPlazos(){
        if ($this->cierrelicitacion_id > 0){
            $sql = "select plazo from plazolicitacion where cierrelicitacion_id = ? and moneda = ? order by plazo";
            $plazos = R::getCol($sql, array($this->cierrelicitacion_id, $this->moneda));
        } else {
            $sql = "select plazo from plazolicitacion where moneda = ? and cierrelicitacion_id = (SELECT id FROM cierrelicitacion where fechahora > NOW() order by fechahora limit 0,1)";
            $plazos = R::getCol($sql, array($this->moneda));
        }
        return $plazos;
    }
    
    public function getPlazosEspecies(){
        if ($this->cierrelicitacion_id > 0){
            $sql = 'select id, plazo, CONCAT(plazo, " ", especie) as especie from plazolicitacion where cierrelicitacion_id = ? order by plazo';
            $plazos = R::getAll($sql, array($this->cierrelicitacion_id));
        } else {
            $sql = 'select id, plazo, CONCAT(plazo, " ", especie) as especie from plazolicitacion where cierrelicitacion_id = (SELECT id FROM cierrelicitacion where fechahora > NOW() order by fechahora limit 0,1) order by plazo';
            $plazos = R::getAll($sql);
        }
        return $plazos;
    }
    
    
    public function getMonedas(){
        if ($this->cierrelicitacion_id > 0){
            $sql = "select distinct moneda from plazolicitacion where cierrelicitacion_id = ? ";
            $monedas = R::getCol($sql, array($this->cierre_id));
        } else {
            $sql = "select distinct moneda from plazolicitacion where cierrelicitacion_id = (SELECT id FROM cierrelicitacion where fechahora > NOW() order by fechahora limit 0,1)";
            $monedas = R::getCol($sql);
        }
        $sql = "select * from moneda where simbolo in (" . R::genSlots($monedas) . ")";
        $resultado = R::getAll($sql, $monedas);
        return $resultado;
    }
    
    public function getCierres(){
        $cierres = R::getAll('select * from cierrelicitacion order by fechahora desc');
        return $cierres;
    }
 
    public function grillaResumen(){        
        $ordenes_in = implode(',', $this->ordenes);
        $sql = "SELECT  plazo, 
                        moneda, 
                        count(*)      cantidadOrdenes, 
                        sum(cantidad) sumaCantidad
                        FROM    licitacion
                WHERE   id in ({$ordenes_in})
                GROUP BY plazo, moneda";
        $resultado = R::getAll($sql);
        return $resultado;
    }
    
    public function procesarGrilla(){
        $sql = "(select b.id,
                b.tramo,
                b.numComitente,
                b.moneda,
                b.cable,
                b.plazo,
                b.especie,
                pl.moneda as plazomoneda, 
                pl.plazo as plazoplazo, 
                pl.especie as plazoespecie,
                b.comision,
                b.cantidad,
                b.precio,
                b.comitente,
                
                b.oficial,
                concat(u.apellido, ' ', u.nombre) as usuario,
                b.cuit,
                eo.estado,
                b.estado_id,
                b.fhmodificacion,
                b.envio,
                b.fhenvio
         from   licitacion b
         join   estadoorden eo 
         on     b.estado_id = eo.id
         join   cierrelicitacion c
         on     b.cierrelicitacion_id = c.id

         LEFT JOIN plazolicitacion pl
         ON b.cierrelicitacion_id = pl.cierrelicitacion_id     
         AND b.plazo = pl.id    

         join   usuario u
         on     b.usuario_id = u.id
         where  b.estado_id <> 1
         and    b.cierrelicitacion_id = ?
         order by b.fhmodificacion desc)"; 
        
        $resultado = R::getAll($sql, array($this->cierrelicitacion_id));
        
        return $resultado;
    }
    
    public function previewSantander(){
        $ordenes_in = implode(',', $this->ordenes);
        $sql = "SELECT  b.cierre_id,
                        b.plazo, 
                        b.moneda, 
                        b.precio,
                        p.especie, 
                        sum(b.cantidad) sumaCantidad
                        FROM    licitacion b
                        left outer join    plazolicitacion p 
                        ON (b.cierrelicitacion_id = p.cierrelicitacion_id AND b.plazo = p.plazo AND b.moneda = p.moneda)
                WHERE   b.id in ($ordenes_in)
                GROUP BY b.cierre_id, b.plazo, b.moneda, b.precio, p.especie";
        $resultado = R::getAll($sql);
        return $resultado;
    }
 
    public function enviarSantander(){        
        $estado = R::load('estadoorden', 3);
        $resultado = array('exito'=>1, 'resultado'=>'Ordenes Enviadas Correctamente');
        foreach ($this->ordenes as $id) {
            $orden = R::load('licitacion', $id);
            $orden->estado = $estado;
            $orden->envio = 'S';
            $orden->fhenvio = R::isoDateTime();
            R::store($orden);
        }
        return $resultado;
    }
    
    
    public function previewMercado(){
        $ordenes_in = implode(',', $this->ordenes);
        $sql = "SELECT  cierrelicitacion_id,
                        tramo,
                        moneda, 
                        cable,
                        tipopersona,
                        plazo, 
                        numcomitente,
                        comitente,
                        tipoInversor,
                        cantidad,
                        precio,
                        cuit,
                        comision
                        FROM    licitacion
                WHERE   id in ({$ordenes_in})
                ORDER BY cierrelicitacion_id, moneda, plazo, tramo ";
        $resultado = R::getAll($sql);
        
        
        
        $contenido = array();
        $colocacionAnterior = 0;
        $contenidoInd = 0;
        foreach ($resultado as $indice=>$fila){
            $plazo = R::findOne('plazolicitacion', 'cierrelicitacion_id = ? and moneda = ?', array($fila['cierrelicitacion_id'], $fila['moneda']));
        
            

            
            //Esto se cambia.
            
            if ($fila['tramo'] == 'Competitiva'){
                $titulo = $plazo->tituloC;
                $precio = $fila['precio'];
                $colocacion = $plazo->colocacionC;
            } else {
                $precio = '';
                $titulo = $plazo->tituloNC;
                $colocacion = $plazo->colocacionNC;
            }
            
            switch ($fila['plazo']){
		case '30':
		    switch ($fila['moneda']){
		        case '$':
		            if ($fila['tramo'] == 'Competitiva'){
		                $titulo = utf8_decode('Letras 140 dias Vto. 17/01/2020 Tramo Competitivo  - Integracion Pesos');
		                $precio = $fila['precio'];
		            } else {
		                $titulo = utf8_decode('Letras 140 dias Vto. 17/01/2020 Tramo NO Competitivo  - Integracion Pesos');
		                $precio = '';
		            }
		            break;

		        case 'u$s':
		            if ($fila['tramo'] == 'Competitiva'){
		                $titulo = utf8_decode('Boncer en $ Vto. 2021 Tramo Competitivo - Int Dolares');
		                $precio = $fila['precio'];
		            } else {
		                $titulo = utf8_decode('Boncer en $ Vto. 2021 Tramo NO Competitivo - Int Dolares');
		                $precio = '';
		            }
		            break;
	    	    }
                break;
            }            
            
            if ($indice == 0){
                $colocacionAnterior = $colocacion;
            }
            
            if ($colocacion <> $colocacionAnterior){
                $contenidoInd++;
                $colocacionAnterior = $colocacion;
            }
            
                        
            if ($fila['tipopersona'] == 'JURIDICA'){
                $tipoPersona = 'Persona Juridica';
                $tipoDocumento = 'CUIT';
            } else {
                $tipoPersona = 'Persona Fisica';
                $tipoDocumento = 'CUIL';
            }
            
            
            
            
            if ($fila['moneda'] == '$'){
                $moneda = 'Pesos';
            } else {
                if ($fila['cable'] == 1) {
                    $moneda = 'Cable';
                } else {
                    $moneda = 'MEP';
                }
            }
            
            
            
            switch ($fila['tipoInversor']){
                
                case 'I': 
                    $tipoInversor = 'Institucional';
                    break;
                case 'M':
                    $tipoInversor = 'Inversor Minorista';
                    break;
                case 'P':
                    $tipoInversor = 'Cartera Propia';
                    break;
                case 'R':
                    $tipoInversor = 'No Residente';
                    break;
                case 'C':
                    $tipoInversor = 'Corporativo';
                    break;
                default :
                    $tipoInversor = '';
                    break;
            }
            
//            print_r("fila['numcomitente']");
//        
//            echo "<pre>";
//            print_r($fila['numcomitente']);
//            echo "<pre>";
//            
//            print_r("moneda");
//        
//            echo "<pre>";
//            print_r($moneda);
//            echo "<pre>";
//            
//            print_r("fila['cuit']");
//        
//            echo "<pre>";
//            print_r($fila['cuit']);
//            echo "<pre>";
//            

            
            
            
            $contenido[$contenidoInd]['colocacion'] = $colocacion;
//            $contenido[$contenidoInd]['datos'] .= $colocacion . "\t" . $titulo . "\r\n";
//            $contenido[$contenidoInd]['datos'] .= $colocacion . "\t" . $titulo . "\t" . $precio . "\t" . $fila['cantidad'] . "\t\t" . $this->formatearCuit($fila['cuit']) . "\t" . $fila['numcomitente'] . "\r\n"; 
//            $contenido[$contenidoInd]['datos'] .= $colocacion . "\t" . $titulo . "\t" . $precio . "\t" . $fila['cantidad'] . "\t\t" . $fila['numcomitente'] . "\t" . $moneda . "\t" . $tipoPersona . "\t" . $fila['cuit']  . "\t" . $fila['comitente'] . "\t\t". $tipoInversor . "\t\r\n";
//            $contenido[$contenidoInd]['datos'] .= $colocacion . "\t" . $titulo . "\t" . $precio . "\t" . $fila['cantidad'] . "\t" . $fila['numcomitente'] . "\t" . $fila['cuit'] . "\t" . $tipoPersona . "\t" . $tipoDocumento . "\t\r\n";
            $contenido[$contenidoInd]['datos'] .= $colocacion . "\t" . $titulo . "\t" . $precio . "\t" . $fila['cantidad'] . "\t\t" . $fila['numcomitente'] . "\t\t" . $fila['cuit']  . "\t\t\t\t" . $tipoPersona .  "\t\t\t".$tipoDocumento."\r\n";
            
            
            
        }
        $this->load->library('zip');
        foreach ($contenido as $data){
            $archivo = date('Y-m-d-H-i-s') . '-' . $data['colocacion'] . '-byma.txt';
            //$archivo = date('Y-m-d-H-i-s') . '-' . $data['colocacion'] . '-licitacions.txt';
            $this->zip->add_data($archivo, $data['datos']);
            //file_put_contents($archivo, $data['datos']);
            //array_push($uris, base_url() . 'generadas/' . basename($archivo));
        }
        $nombreZip = FCPATH . 'generadas/' . date('Y-m-d-H-i-s') . '-licitacions.zip';
        $this->zip->archive($nombreZip);
        
        return array('uris'=>array(base_url() . 'generadas/' . basename($nombreZip)));
    }
    
    public function enviarMercado(){
        $estado = R::load('estadoorden', 3);
        $resultado = array('exito'=>1, 'resultado'=>'Ordenes Enviadas Correctamente');
        foreach ($this->ordenes as $id) {
            $orden = R::load('licitacion', $id);
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
                            from   licitacion
                            where  cierrelicitacion_id = ?
                            and    estado_id = 1
                            order by usuario_id)";
                $usuarios = R::getAll($sql, array($cierre['id']));
                foreach ($usuarios as $usuario) {
                    $sql = "select  id,
                                    tramo,
                                    numcomitente,
                                    moneda,
                                    cable,
                                    plazo,
                                    comision,
                                    cantidad,
                                    precio,
                                    comitente,
                                    oficial,
                                    cuit
                            from    licitacion
                            where   usuario_id = ?
                            and     cierrelicitacion_id = ?
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
                                        <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Tramo</font></th>
                                        <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Nro Comitente</font></th>
                                        <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Moneda</font></th>
                                        <!--
                                        <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Cable</font></th>
                                        <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Plazo</font></th>
                                        -->
                                        <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Comision</font></th>
                                        <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Cantidad</font></th>
                                        <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Precio</font></th>
                                        <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Comitente</font></th>
                                        <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Tipo Persona</font></th>
                                        <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Oficial</font></th>
                                        <th bgcolor='#5D7B9D'><font color='#FFFFFF'>CUIT</font></th>
                                    </tr>";
                    foreach ($ordenes as $orden){
                        $html .= "
                                    <tr>
                                        <td align='right'>{$orden['id']}</td>
                                        <td>{$orden['tramo']}</td>
                                        <td align='right'>{$orden['numcomitente']}</td>    
                                        <td align='center'>{$orden['moneda']}</td>
                                        <!--
                                        <td align='center'>{$orden['cable']}</td>
                                        <td align='right'>{$orden['plazo']}</td>
                                        -->
                                        <td align='right'>{$orden['comision']}</td>
                                        <td align='right'>{$orden['cantidad']}</td>
                                        <td align='right'>{$orden['precio']}</td>
                                        <td>{$orden['comitente']}</td>
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
        $resultado = "BONO ";
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
    
    
    public function getPlazoPorNombre(){
        $sql = "select id, moneda, plazo, especie, colocacion, cierrelicitacion_id "
                . "from plazolicitacion "
                . "where plazo = ? AND cierrelicitacion_id = ?";
        $plazos = R::getRow($sql, array($this->plazoNombre, $this->cierre)); 

        return $plazos;
    }
    
    public function grabarExcel(){
                  
        $usuarioParam = $this->session->userdata('usuario');

        $orden = R::load('licitacion', $this->id);
        $cierre = R::load('cierrelicitacion', $this->cierre);
        $usuario = R::load('usuario', $usuarioParam['id']);
        $estadoorden = R::load('estadoorden', 1);        
//        $this->moneda = '$';

        $plazos = $this->Licitacion_model->getPlazos();
               
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
                        
            if($nombreHojas[0] == 'tramo' && $nombreHojas[1] == 'comitente'){
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
                
                $tramo = $sheet->getCellByColumnAndRow(0,$row)->getFormattedValue();
                
                $numeroComitente = $sheet->getCellByColumnAndRow(1,$row)->getFormattedValue();                
                
                $numeroComitente = str_replace(',', '', $numeroComitente);
                $numeroComitente = str_replace('.', '', $numeroComitente);
                if (strlen(trim($numeroComitente)) > 0) {
                    
                    $orden = R::dispense('licitacion');
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
                    
                    
                    $moneda = $sheet->getCellByColumnAndRow(2,$row)->getFormattedValue();

                    //Plazo
                    $plazoNombre = $sheet->getCellByColumnAndRow(3,$row)->getCalculatedValue();
                   
                    $this->Licitacion_model->plazoNombre = $plazoNombre;
                    $resultadoPlazo = $this->Licitacion_model->getPlazoPorNombre();
                    
                    $plazo = 0;
                    
                    if($resultadoPlazo){
                        $plazo = $resultadoPlazo['id'];
                    }else{
                        $error.="Plazo invalido en fila {$row} <br>";
                        $valido = false;                    
                    }
                    
                    $especie = $sheet->getCellByColumnAndRow(4,$row)->getValue();     
                    
                    // Comision
                    $comision = $sheet->getCellByColumnAndRow(5,$row)->getOldCalculatedValue();     
                    if($comision == 0){
                      $comision = $sheet->getCellByColumnAndRow(5,$row)->getCalculatedValue();
                    }
                    $comision = (float)$comision;
                    
                    
                    // Cantidad
                    $cantidad = $sheet->getCellByColumnAndRow(6,$row)->getOldCalculatedValue();     
                    if($cantidad == 0){
                      $cantidad = $sheet->getCellByColumnAndRow(6,$row)->getCalculatedValue();
                    }
                    $cantidad = (int)$cantidad;
                    
                    
                    // Precio
                    $precio = $sheet->getCellByColumnAndRow(7,$row)->getOldCalculatedValue();     
                    if($precio == 0){
                      $precio = $sheet->getCellByColumnAndRow(7,$row)->getCalculatedValue();
                    }
                    $precio = (float)$precio;


                    $orden->tramo = $tramo;
                    $orden->moneda = $moneda;

                    $orden->plazo = $plazo;
                    $orden->especie = $especie;
                    $orden->comision = $comision;

                    $orden->cantidad = $cantidad;
                    $orden->precio = $precio;
                    
                    $orden->fhmodificacion = R::isoDateTime();
                    $orden->usuario = $usuario;
                    $orden->cierrelicitacion = $cierre;
                    $orden->estado_id = 1;

                    
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

class Model_Licitacion extends RedBean_SimpleModel {
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
            $auditoria->table = 'licitacion';
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
        $auditoria->table = 'licitacion';
        $auditoria->tableId = $this->prev['id'];
        $auditoria->anterior = json_encode($this->prev);
        $auditoria->actual = json_encode(array('operacion'=>'Registro Borrado'));
        R::store($auditoria);        
    }    
}

class Model_Cierrelicitacion extends RedBean_SimpleModel {
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
            $auditoria->table = 'cierrelicitacion';
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
        $auditoria->table = 'cierrelicitacion';
        $auditoria->tableId = $this->prev['id'];
        $auditoria->anterior = json_encode($this->prev);
        $auditoria->actual = json_encode(array('operacion'=>'Registro Borrado'));
        R::store($auditoria);        
    }
}