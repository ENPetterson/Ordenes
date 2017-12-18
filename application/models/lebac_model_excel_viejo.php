<?php

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
    public $plazospesos;
    public $especiespesos;
    public $minimospesos;
    public $plazosdolares;
    public $especiesdolares;
    public $minimosdolares;
    public $segmentosdolares;
    
    private $workbook;
    private $sheetIndex;
    
    
    
    
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
        $cierre = R::load('cierre', $this->cierre_id);
        return $cierre->export();
    }
    
    public function saveCierre(){
        $cierre = R::load('cierre', $this->cierre_id);
        $cierre->fechahora = $this->fechahora;
        $cierre->plazospesos = $this->plazospesos;
        $cierre->especiespesos = $this->especiespesos;
        $cierre->minimospesos = $this->minimospesos;
        $cierre->plazosdolares = $this->plazosdolares;
        $cierre->especiesdolares = $this->especiesdolares;
        $cierre->minimosdolares = $this->minimosdolares;
        $cierre->segmentosdolares = $this->segmentosdolares;
        R::store($cierre);
        return $cierre->export();
    }
    
    public function delCierre(){
        $cierre = R::load('cierre', $this->cierre_id);
        R::trash($cierre);
    }
    
    public function getCierreActual(){
        $cierreActual = R::findOne('cierre', 'fechahora > NOW() order by fechahora' );
        if (is_null($cierreActual)){
            return array('cerrado'=>true);
        } else {
            return $cierreActual->export();
        }
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
         where  l.estado_id <> 1
         and    l.cierre_id = ?
         order by l.fhmodificacion desc)"; 
        
        $resultado = R::getAll($sql, array($this->cierre_id));
        
        return $resultado;
    }
    
    public function previewSantander(){
        $ordenes_in = implode(',', $this->ordenes);
        $sql = "SELECT  cierre_id,
                        plazo, 
                        moneda, 
                        precio,
                        ''      especie, 
                        sum(cantidad) sumaCantidad
                        FROM    lebac
                WHERE   id in ({$ordenes_in})
                GROUP BY cierre_id, plazo, moneda, precio";
        $resultado = R::getAll($sql);
        foreach ($resultado as $indice=>$fila){
            if ($fila['moneda'] == '$'){
                $campos = 'plazospesos plazos, especiespesos especies';
            } else {
                $campos = 'plazosdolares plazos, especiesdolares especies';
            }
            $sql = "select {$campos} from cierre where id = {$fila['cierre_id']}";
            $cierre = R::getRow($sql);
            $plazos =  explode(',', $cierre['plazos']);
            $especies = explode(',', $cierre['especies']);
            foreach ($plazos as $indiceP=>$plazo){
                if ((int) $plazo == (int) $fila['plazo']){
                    $resultado[$indice]['especie'] = $especies[$indiceP];
                }
            }
        }
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
                ORDER BY cierre_id, tramo, moneda, tipopersona, plazo ";
        $resultado = R::getAll($sql);
        $planillaAnterior = "";
        $plazoAnterior = 0;
        $filaPlanilla = 0;
        $contadorPlanilla = 0;
        
        $planillaExcel = $this->crearLibro();
        
        $this->load->library('excel');
        $this->workbook = PHPExcel_IOFactory::load($planillaExcel);
        $this->sheetIndex = 12;
        
        
        $maxFila = 5;
        foreach ($resultado as $indice=>$fila){
            if ($fila['tramo'] == 'Competitiva'){
                $planillaActual = 'C';
                $maxFila = 5;
            } else {
                $planillaActual = 'N';
                $maxFila = 10;
            }
            if ($fila['moneda'] == '$'){
                $planillaActual .= 'P';
            } else {
                $maxFila = 5;
                $campos = 'plazosdolares plazos, segmentosdolares segmentos';
                $sql = "select {$campos} from cierre where id = {$fila['cierre_id']}";
                $cierre = R::getRow($sql);
                $plazos =  explode(',', $cierre['plazos']);
                $segmentos = explode(',', $cierre['segmentos']);
                foreach ($plazos as $indiceP=>$plazo){
                    if ((int) $plazo == (int) $fila['plazo']){
                        $segmento = $segmentos[$indiceP];
                    }
                }
                $planillaActual .= $segmento;
            }
            
            if ($fila['tipopersona'] == 'JURIDICA') {
                $planillaActual .= 'J';
            } else {
                $planillaActual .= 'F';
            }
            
            if ($planillaActual == $planillaAnterior && $fila['plazo'] == $plazoAnterior){
                ++$filaPlanilla;
                if ($filaPlanilla > $maxFila){
                    ++$contadorPlanilla;
                    $filaPlanilla = 1;
                    $this->crearHoja($planillaActual, $fila['plazo'], $contadorPlanilla);
                }
            } else {
                $planillaAnterior = $planillaActual;
                $plazoAnterior = $fila['plazo'];
                $filaPlanilla = 1;
                $contadorPlanilla = 1;
                $this->crearHoja($planillaActual, $fila['plazo'], $contadorPlanilla);
            }
            $this->escribirFila($planillaActual, $fila, $filaPlanilla);
        }
        for($i=1;$i<=13;$i++){
            $this->workbook->removeSheetByIndex(0);
        }

        $objWriter = new PHPExcel_Writer_Excel2007($this->workbook);
        $objWriter->save($planillaExcel);
        unset($objWriter);
        
        $archivos = Array();
        $sheetCount = $this->workbook->getSheetCount();
        for($i=0; $i<$sheetCount;$i++) {
            $this->workbook->setActiveSheetIndex($i);
            $planillaNueva = FCPATH . 'generadas/' . $this->nombreArchivo($this->workbook->getActiveSheet()->getTitle()) . '.xlsx';
            copy($planillaExcel, $planillaNueva);
            $wbNuevo = PHPExcel_IOFactory::load($planillaNueva);
            for ($h=0;$h<$i;$h++){
                $wbNuevo->removeSheetByIndex(0);
            }
            for ($h=$i+1;$h<$sheetCount;$h++){
                $wbNuevo->removeSheetByIndex(1);
            }
            
            $objWriter = new PHPExcel_Writer_Excel2007($wbNuevo);
            $objWriter->save($planillaNueva);
            array_push($archivos, base_url() . 'generadas/' . basename($planillaNueva));
            unset($planillaNueva, $wbNuevo, $objWriter);
        }
        
        
        return array('uris'=>$archivos);
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

    
    private function crearLibro(){
        $planillaTemplate =  FCPATH . 'template/mercado.xlsx';
        $planillaDestino = FCPATH . 'generadas/' . date('Y-m-d-H-i-s') . '.xlsx';
        copy($planillaTemplate, $planillaDestino);
        return $planillaDestino;
    }
    
    private function crearHoja($planilla, $plazo, $contador){
        $nueva = clone $this->workbook->getSheetByName($planilla);
        $nueva->setTitle($planilla . '-' . $plazo . '-' . $contador);
        $this->sheetIndex++;
        $this->workbook->addSheet($nueva,$this->sheetIndex);
        $this->workbook->setActiveSheetIndex($this->sheetIndex);
        
        $fecha = new DateTime();
        $this->workbook->getActiveSheet()->SetCellValue('F4', $fecha->format('d-M-Y'));
        $this->workbook->getActiveSheet()->SetCellValue('B11', $plazo);
    }
    
    private function escribirFila($planilla, $datos, $fila){
        $caracter1 = substr($planilla, 0, 1);
        $caracter2 = substr($planilla, 1, 1);
        
        $filaExcel = 13 + $fila;
        
        //$this->workbook->getActiveSheet()->setCellValue('B'.$filaExcel, $datos['numcomitente']);
        $this->workbook->getActiveSheet()->setCellValue('B'.$filaExcel, $datos['cantidad']);
        if ($caracter1 == 'C'){
            $this->workbook->getActiveSheet()->setCellValue('C'.$filaExcel, $datos['precio']);
        }
        $this->workbook->getActiveSheet()->setCellValue('D'.$filaExcel, $this->formatearCuit($datos['cuit']));
        //$this->workbook->getActiveSheet()->setCellValue('G'.$filaExcel, $datos['comision']);
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