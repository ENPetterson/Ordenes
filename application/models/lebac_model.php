<?php

require_once APPPATH."/third_party/PHPExcel.php";

class Lebac_model extends CI_Model{
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
    
    public function saveOrden(){
        $usuarioParam = $this->session->userdata('usuario');
        $usuario = R::load('usuario', $usuarioParam['id']);

        $orden = R::load('lebac', $this->id);
        $ordenAnterior = $orden;
        if ($orden->id == 0 ){
            $estadoorden = R::load('estadoorden', 1);
            $cierreActual = $this->getCierreActual();
            if ($cierreActual['cerrado']){
                return array('id'=>0);
            } else {
                $cierre = R::load('cierre', $cierreActual['id']);
                $orden->cierre = $cierre;
            }
            $orden->usuario = $usuario;
            $orden->estado = $estadoorden;
        }
        $orden->tramo = $this->tramo;
        $orden->numcomitente = $this->numComitente;
        $orden->moneda = $this->moneda;
        $orden->plazo = $this->plazo;
        $orden->comision = $this->comision;
        $orden->cantidad = $this->cantidad;
        $orden->precio = $this->precio;
        $orden->comitente = $this->comitente;
        $orden->tipopersona = $this->tipoPersona;
        $orden->oficial = $this->oficial;
        $orden->cuit = (double) $this->cuit;
        $orden->fhmodificacion =  R::isoDateTime();
        $this->id = R::store($orden);
        
        return $orden->export();
    }
    
    public function getOrden(){
        $orden = R::load('lebac', $this->id);
        return $orden->export();
    }
    
    public function delOrden(){
        foreach ($this->ordenes as $id){
            $lebac = R::load('lebac', $id);
            R::trash($lebac);
        }
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
                eo.estado,
                l.estado_id,
                l.fhmodificacion,
                c.fechahora as cierre
         from   lebac l
         join   estadoorden eo 
         on     l.estado_id = eo.id
         join   cierre c
         on     l.cierre_id = c.id
         where  l.usuario_id = ?
         and    l.cierre_id = ?
         order by l.fhmodificacion desc"; 
        
        $resultado = R::getAll($sql, array($this->usuario_id, $this->cierre_id));
        
        return $resultado;

    }
    
    public function enviarOrdenes(){        
        $estado = R::load('estadoorden', 2);
        $resultado = array('exito'=>1, 'resultado'=>'Ordenes Enviadas Correctamente');
        $ahora = new DateTime();
        foreach ($this->ordenes as $id) {
            $orden = R::load('lebac', $id);
            $fechaCierre = new DateTime($orden->cierre->fechahora);
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
            $orden = R::load('lebac', $id);
            $orden->estado = $estado;
            R::store($orden);
        }
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
    
    public function saveCierre(){
        $cierre = R::load('cierre', $this->cierre_id);
        $cierre->fechahora = $this->fechahora;
        $cierre->instrumento = $this->instrumento;
        R::store($cierre);
        
        foreach ((array) $this->plazos as $plazoItem) {
            $plazo = R::load('plazo', $plazoItem['id']);
            $plazo->moneda = $plazoItem['moneda'];
            $plazo->plazo = $plazoItem['plazo'];
            $plazo->especie = $plazoItem['especie'];
            $plazo->colocacion = $plazoItem['colocacion'];
            $plazo->tituloC = $plazoItem['tituloC'];
            $plazo->tituloNCJ = $plazoItem['tituloNCJ'];
            $plazo->tituloNCF = $plazoItem['tituloNCF'];
            if ($plazoItem['segmento'] == 'I' || $plazoItem['segmento'] == 'E'){
                $plazo->segmento = $plazoItem['segmento'];
            }
            $plazo->cierre = $cierre;
            R::store($plazo);
        }
        
        foreach ((array) $this->plazosBorrar as $plazo_id){
            $plazo = R::load('plazo', $plazo_id);
            R::trash($plazo);
        }
        
        
        R::exec('delete from tenencialebac where cierre_id = ?', array($cierre->id));
        
        $this->load->model('Esco_model');
        $this->Esco_model->instrumento = $this->instrumento;
        $posicionesLebac = $this->Esco_model->getPosicionLebacs();
        foreach ($posicionesLebac as $posicion) {
            $tenenciaLebac = R::dispense('tenencialebac');
            $tenenciaLebac->numComitente = $posicion['NumComitente'];
            $tenenciaLebac->cantidad = $posicion['Cantidad'];
            $tenenciaLebac->cierre = $cierre;
            R::store($tenenciaLebac);
        }
        
        return $cierre->export();
    }
    
    public function actualizarPosicionMonetaria(){
        R::exec('truncate table posicionmonetaria');
        $this->load->model('Esco_model');
        $posiciones = $this->Esco_model->getPosicionMonetaria();
        foreach ($posiciones as $posicion){
            $posicionMonetaria = R::dispense('posicionmonetaria');
            $posicionMonetaria->numComitente = $posicion['NumComitente'];
            $posicionMonetaria->saldo = $posicion['SaldoDisponible'];
            R::store($posicionMonetaria);
        }
    }
    
    
    
    public function delCierre(){
        $plazos = R::find('plazo', array($this->cierre_id));
        R::trashAll($plazos);
        $cierre = R::load('cierre', $this->cierre_id);
        R::trash($cierre);
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
    
    public function getPlazos(){
        if ($this->cierre_id > 0){
            $sql = "select plazo from plazo where cierre_id = ? and moneda = ? order by plazo";
            $plazos = R::getCol($sql, array($this->cierre_id, $this->moneda));
        } else {
            $sql = "select plazo from plazo where moneda = ? and cierre_id = (SELECT id FROM cierre where fechahora > NOW() order by fechahora limit 0,1) order by plazo";
            $plazos = R::getCol($sql, array($this->moneda));
        }
        return $plazos;
    }
    
    public function getMonedas(){
        if ($this->cierre_id > 0){
            $sql = "select distinct moneda from plazo where cierre_id = ? ";
            $monedas = R::getCol($sql, array($this->cierre_id));
        } else {
            $sql = "select distinct moneda from plazo where cierre_id = (SELECT id FROM cierre where fechahora > NOW() order by fechahora limit 0,1)";
            $monedas = R::getCol($sql);
        }
        $sql = "select * from moneda where simbolo in (" . R::genSlots($monedas) . ")";
        $resultado = R::getAll($sql, $monedas);
        return $resultado;
    }
    
    public function getCierres(){
        $cierres = R::getAll('select * from cierre order by fechahora desc');
        return $cierres;
    }
 
    public function grillaResumen(){        
        $ordenes_in = implode(',', $this->ordenes);
        $sql = "SELECT  plazo, 
                        moneda, 
                        count(*)      cantidadOrdenes, 
                        sum(cantidad) sumaCantidad
                        FROM    lebac
                WHERE   id in ({$ordenes_in})
                GROUP BY plazo, moneda";
        $resultado = R::getAll($sql);
        return $resultado;
    }
    
    public function procesarGrilla(){
        $sql = "(select l.id,
                l.tramo,
                l.numComitente,
                l.moneda,
                l.plazo,
                l.comision,
                l.cantidad,
                ifnull(tl.cantidad, 0) as tenencia,
                ifnull(pm.saldo,0) as posicion,                
                l.precio,
                l.comitente,
                l.tipoPersona,
                l.oficial,
                concat(u.apellido, ' ', u.nombre) as usuario,
                l.cuit,
                eo.estado,
                l.estado_id,
                l.fhmodificacion,
                l.envio,
                l.fhenvio
         from   lebac l
         join   estadoorden eo 
         on     l.estado_id = eo.id
         join   cierre c
         on     l.cierre_id = c.id
         join   usuario u
         on     l.usuario_id = u.id
         left outer join posicionmonetaria pm
         on     l.numComitente = pm.numComitente
         left outer join tenencialebac tl
         on     l.numComitente = tl.numComitente
         and    tl.cierre_id = l.cierre_id
         where  l.estado_id <> 1
         and    l.cierre_id = ?
         order by l.fhmodificacion desc)"; 
        
        $resultado = R::getAll($sql, array($this->cierre_id));
        
        return $resultado;
    }
    
    public function getOrdenes(){
        
        if ($this->cierre_id == 0){
            $this->getCierreActual();
        }
        
        $sql = "(select l.id,
                l.moneda,
                l.plazo,
                l.cantidad,
                l.precio
         from   lebac l
         join   estadoorden eo 
         on     l.estado_id = eo.id
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
    
    public function previewSantander(){
        $ordenes_in = implode(',', $this->ordenes);
        $sql = "SELECT  l.cierre_id,
                        l.plazo, 
                        l.moneda, 
                        l.precio,
                        p.especie, 
                        sum(l.cantidad) sumaCantidad
                        FROM    lebac l
                        left outer join    plazo p 
                        ON (l.cierre_id = p.cierre_id AND l.plazo = p.plazo AND l.moneda = p.moneda)
                WHERE   l.id in ($ordenes_in)
                GROUP BY l.cierre_id, l.plazo, l.moneda, l.precio, p.especie";
        $resultado = R::getAll($sql);
        return $resultado;
    }
 
    public function enviarSantander(){        
        $estado = R::load('estadoorden', 3);
        $resultado = array('exito'=>1, 'resultado'=>'Ordenes Enviadas Correctamente');
        foreach ($this->ordenes as $id) {
            $orden = R::load('lebac', $id);
            $orden->estado = $estado;
            $orden->envio = 'S';
            $orden->fhenvio = R::isoDateTime();
            R::store($orden);
        }
        return $resultado;
    }
    
    
    public function previewMercado(){
        $ordenes_in = implode(',', $this->ordenes);
        $sql = "SELECT  cierre_id,
                        tramo,
                        moneda, 
                        tipopersona,
                        plazo, 
                        numcomitente,
                        cantidad,
                        precio,
                        cuit,
                        comision
                        FROM    lebac
                WHERE   id in ({$ordenes_in})
                ORDER BY cierre_id, moneda, plazo ";
        $resultado = R::getAll($sql);
        
        $contenido = "";
        $colocacionAnterior = 0;
        $contenidoInd = 0;
        foreach ($resultado as $indice=>$fila){
            $plazo = R::findOne('plazo', 'cierre_id = ? and moneda = ? and plazo = ?', array($fila['cierre_id'], $fila['moneda'], $fila['plazo']));
            if ($fila['tramo'] == 'Competitiva'){
                $titulo = $plazo->tituloC;
                $precio = $fila['precio'];
            } else {
                $precio = '';
                if ($fila['tipopersona'] == 'JURIDICA'){
                    $titulo = $plazo->tituloNCJ;
                } else {
                    $titulo = $plazo->tituloNCF;
                }
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
            $contenido[$contenidoInd]['datos'] .= $colocacion . "\t" . $titulo . "\t" . $precio . "\t" . $fila['cantidad'] . "\t\t" . $fila['numcomitente'] . "\t\t" . $fila['cuit']  .  "\r\n";
            
        }
        $this->load->library('zip');
        foreach ($contenido as $data){
            //$archivo = FCPATH . 'generadas/' . date('Y-m-d-H-i-s') . '-' . $data['colocacion'] . '-lebacs.dat';
            $archivo = date('Y-m-d-H-i-s') . '-' . $data['colocacion'] . '-lebacs.txt';
            $this->zip->add_data($archivo, $data['datos']);
            //file_put_contents($archivo, $data['datos']);
            //array_push($uris, base_url() . 'generadas/' . basename($archivo));
        }
        $nombreZip = FCPATH . 'generadas/' . date('Y-m-d-H-i-s') . '-lebacs.zip';
        $this->zip->archive($nombreZip);
        
        return array('uris'=>array(base_url() . 'generadas/' . basename($nombreZip)));
    }
    
    public function enviarMercado(){        
        $estado = R::load('estadoorden', 3);
        $resultado = array('exito'=>1, 'resultado'=>'Ordenes Enviadas Correctamente');
        foreach ($this->ordenes as $id) {
            $orden = R::load('lebac', $id);
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
                            from   lebac
                            where  cierre_id = ?
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
                            from    lebac
                            where   usuario_id = ?
                            and     cierre_id = ?
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
                                        <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Plazo</font></th>
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
                                        <td align='right'>{$orden['plazo']}</td>
                                        <td align='right'>{$orden['comision']}</td>
                                        <td align='right'>{$orden['cantidad']}</td>
                                        <td align='right'>{$orden['precio']}</td>
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
        $resultado = "LEBAC ";
        $inicio  = strpos($nombrePlanilla, '-');
        $fin    = strpos($nombrePlanilla, '-', $inicio + 1);
        $largo = $fin - $inicio;
        $plazo = substr($nombrePlanilla, $inicio + 1, $largo - 1);
        $resultado .= $plazo;
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
    
    
    public function grabarExcel(){
                  
        $usuarioParam = $this->session->userdata('usuario');
 
        $orden = R::load('lebac', $this->id);
        $cierre = R::load('cierre', $this->cierre);
        $usuario = R::load('usuario', $usuarioParam['id']);
        $estadoorden = R::load('estadoorden', 1);        
        $this->moneda = '$';

        $plazos = $this->Lebac_model->getPlazos();
               
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
                        
            if($nombreHojas[0] == 'numero' && $nombreHojas[6] == 'lebacs' && $nombreHojas[9] == 'comision' && $nombreHojas[10] == 'plazo'){
                $aprobado = 1;
            }
        }
        
        
        if($aprobado){
            $highestRow = $sheet->getHighestDataRow();
            
            $valido = true;
            $error = '';
            
            R::freeze(false);
            R::begin();
            
            for ($row = 2; $row < $highestRow; $row++){
                $numeroComitente = $sheet->getCellByColumnAndRow(0,$row)->getFormattedValue();
                $numeroComitente = str_replace(',', '', $numeroComitente);                
                if (strlen(trim($numeroComitente)) > 0) { 
                    
                    $cantidad = $sheet->getCellByColumnAndRow(6,$row)->getOldCalculatedValue();     
                    if($cantidad == 0){
                      $cantidad = $sheet->getCellByColumnAndRow(6,$row)->getCalculatedValue();
                    }
                    $cantidad = (int)$cantidad;

                    $comision = $sheet->getCellByColumnAndRow(9,$row)->getFormattedValue();
                    $plazo = $sheet->getCellByColumnAndRow(10,$row)->getFormattedValue();



                    if (!in_array($plazo, $plazos)){
                        $error.="Plazo invalido en fila {$row} <br>";
                        $valido = false;
                    }

                    $orden = R::dispense('lebac');
                    $orden->tramo = 'No Competitiva';
                    $orden->numcomitente = $numeroComitente;

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

                    if(!is_numeric($comision)){
                        $error.="Comisión inválida en fila {$row} <br>";
                        $valido = false;
                    }


                    if(!is_int($cantidad)){
                        $error.="Cantidad inválida en fila {$row} <br>";
                        $valido = false;
                    }



                    $orden->precio = 0;
                    $orden->moneda = '$';
                    $orden->plazo = $plazo;
                    $orden->comision = $comision;
                    $orden->cantidad = $cantidad;
                    $orden->estado = $estadoorden;
                    $orden->fhmodificacion = R::isoDateTime();
                    $orden->usuario = $usuario;
                    $orden->cierre = $cierre;

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

class Model_Lebac extends RedBean_SimpleModel {
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
            $auditoria->table = 'lebac';
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
        $auditoria->table = 'lebac';
        $auditoria->tableId = $this->prev['id'];
        $auditoria->anterior = json_encode($this->prev);
        $auditoria->actual = json_encode(array('operacion'=>'Registro Borrado'));
        R::store($auditoria);        
    } 
        
    
}

class Model_Cierre extends RedBean_SimpleModel {
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
            $auditoria->table = 'cierre';
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
        $auditoria->table = 'cierre';
        $auditoria->tableId = $this->prev['id'];
        $auditoria->anterior = json_encode($this->prev);
        $auditoria->actual = json_encode(array('operacion'=>'Registro Borrado'));
        R::store($auditoria);        
    }    
}