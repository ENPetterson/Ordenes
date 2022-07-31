<?php

require_once APPPATH."/third_party/PHPExcel.php";

class Canje_model extends CI_Model{
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
    
    public $cierrecanje_id;
    public $fechahora;
    
    public function saveOrden(){
        
        
        $usuarioParam = $this->session->userdata('usuario');
        $usuario = R::load('usuario', $usuarioParam['id']);

        $orden = R::load('canje', $this->id);
        if ($orden->id == 0 ){
            $estadoorden = R::load('estadoorden', 1);
            $cierreActual = $this->getCierreActual();
            if (isset($cierreActual['cerrado'])){
                return array('id'=>0);
            } else {
                $cierre = R::load('cierrecanje', $cierreActual['id']);
                $orden->cierrecanje = $cierre;
            }
            $orden->usuario = $usuario;
            $orden->estado = $estadoorden;
        }
        $orden->numcomitente = $this->numComitente;
//        $orden->comision = $this->comision;
        $orden->cantidad = $this->cantidad;
        $orden->plazo = $this->plazo;
        $orden->arancel = $this->arancel;
//        $orden->bono = $this->bono;
        
//        $orden->cantidadACrecer = $this->cantidadACrecer;
//        $orden->precio = $this->precio;
//        $orden->segundaParte = $this->segundaParte;
//        $orden->cantidadAcrecerSegunda = $this->cantidadAcrecerSegunda;
        $orden->comitente = $this->comitente;
        $orden->tipopersona = $this->tipoPersona;
        $orden->oficial = $this->oficial;
        $orden->cuit = (double) $this->cuit;
        $orden->posicion = $this->posicion;
        $orden->fhmodificacion =  R::isoDateTime();
        $this->id = R::store($orden);
        
        return $orden->export();
    }
    
    public function getOrden(){
        $orden = R::load('canje', $this->id);
        return $orden->export();
    }
    
    public function delOrden(){
        foreach ($this->ordenes as $id){
            $orden = R::load('canje', $id);
            R::trash($orden);
        }
    }
    
    
    public function getPlazos(){
        if ($this->cierrecanje_id > 0){
            $sql = "select id, plazo, especie from plazocanje where cierrecanje_id = ? order by plazo";
            $plazos = R::getAll($sql, array($this->cierrecanje_id));
        } else {
            $sql = "select id, plazo, especie from plazocanje where cierrecanje_id = (SELECT id FROM cierrecanje where fechahora > NOW() order by fechahora limit 0,1) order by plazo";
            $plazos = R::getAll($sql);
        }
        return $plazos;
    }
    
    public function getPlazosEspecies(){
        if ($this->cierrecanje_id > 0){
            $sql = 'select id, plazo, CONCAT(plazo, " ", especie) as especie from plazocanje where cierrecanje_id = ? order by plazo';
            $plazos = R::getAll($sql, array($this->cierrecanje_id));
        } else {
            $sql = 'select id, plazo, CONCAT(plazo, " ", especie) as especie from plazocanje where cierrecanje_id = (SELECT id FROM cierrecanje where fechahora > NOW() order by fechahora limit 0,1) order by plazo';
            $plazos = R::getAll($sql);
        }
        return $plazos;
    }
    
    
    public function grilla(){
        $sql = "select p.id,
                p.numComitente,
                p.cantidad,
                p.arancel,
                p.comitente,
                p.tipoPersona,
                pla.especie,
                p.oficial,
                concat(u.apellido, ' ', u.nombre) as usuario,
                p.cuit,
                p.posicion,
                eo.estado,
                p.estado_id,
                p.fhmodificacion,
                c.fechahora as cierre
         from   canje p
         join   estadoorden eo 
         on     p.estado_id = eo.id
         join   cierrecanje c
         on     p.cierrecanje_id = c.id
         join   usuario u
         on     p.usuario_id = u.id
         
         LEFT JOIN plazocanje pla
         on     pla.id = p.plazo

         where  p.usuario_id = ?
         and    p.cierrecanje_id = ?
         order by p.fhmodificacion desc"; 
        
        $resultado = R::getAll($sql, array($this->usuario_id, $this->cierrecanje_id));
        
        return $resultado;

    }
    
    public function enviarOrdenes(){        
        $estado = R::load('estadoorden', 2);
        $resultado = array('exito'=>1, 'resultado'=>'Ordenes Enviadas Correctamente');
        $ahora = new DateTime();
        foreach ($this->ordenes as $id) {
            $orden = R::load('canje', $id);
            $fechaCierre = new DateTime($orden->cierrecanje->fechahora);
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
            $orden = R::load('canje', $id);
            $orden->estado = $estado;
            R::store($orden);
        }
        return $resultado;
    }
    
    
    public function getCierre(){
        
        $cierreBean = R::load('cierrecanje', $this->cierrecanje_id);
        $cierre = $cierreBean->export();
        $sql = "select * from plazocanje where cierrecanje_id = ? order by moneda, plazo";
        $plazos = R::getAll($sql, array($cierreBean->id));
        $cierre['plazos'] = $plazos;
        return $cierre;
    }
    
    public function saveCierre(){
        $cierre = R::load('cierrecanje', $this->cierrecanje_id);
        $cierre->fechahora = $this->fechahora;
//        $cierre->instrumento = $this->instrumento;
        R::store($cierre);
        
        foreach ((array) $this->plazos as $plazoItem) {
            $plazo = R::load('plazocanje', $plazoItem['id']);
            $plazo->moneda = $plazoItem['moneda'];
            $plazo->plazo = $plazoItem['plazo'];
            $plazo->especie = $plazoItem['especie'];
            $plazo->colocacion = $plazoItem['colocacion'];
            $plazo->tituloC = $plazoItem['tituloC'];
            $plazo->tituloNCJ = $plazoItem['tituloNCJ'];
            $plazo->tituloNCF = $plazoItem['tituloNCF'];
            $plazo->cierrecanje = $cierre;
            R::store($plazo);
        }
        
        foreach ((array) $this->plazosBorrar as $plazocanje_id){
            $plazo = R::load('plazo', $plazocanje_id);
            R::trash($plazo);
        }
        
        return $cierre->export();
    }
    
    public function delCierre(){
        $cierre = R::load('cierrecanje', $this->cierrecanje_id);
        $cierre->ownPlazocanje = array();
        R::store($cierre);
        R::trash($cierre);
    }
    
    public function getCierreActual(){
        $cierreActual = R::findOne('cierrecanje', 'fechahora > NOW() order by fechahora' );
        if (is_null($cierreActual)){
            return array('cerrado'=>true);
        } else {
            $this->cierrecanje_id = $cierreActual->id;
            $cierre = $this->getCierre();
            return $cierre;
        }
    }
    
    public function getCierres(){
        $cierres = R::getAll('select * from cierrecanje order by fechahora desc');
        return $cierres;
    }
 
    public function grillaResumen(){        
        $ordenes_in = implode(',', $this->ordenes);
        $sql = "SELECT  count(*)      cantidadOrdenes, 
                        sum(cantidad) sumaCantidad
                        FROM    canje
                WHERE   id in ({$ordenes_in})
                /* GROUP BY plazo, moneda */";
        $resultado = R::getAll($sql);
        return $resultado;
    }
    
    public function procesarGrilla(){
        $sql = "(select p.id,
                p.numComitente,
                p.cantidad,
                p.arancel,
                pla.plazo,
                pla.especie,

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
                p.fhenvio
         from   canje p
         join   estadoorden eo 
         on     p.estado_id = eo.id
         join   cierrecanje c
         on     p.cierrecanje_id = c.id
         join   usuario u
         on     p.usuario_id = u.id
         
         LEFT JOIN plazocanje pla
         on     pla.id = p.plazo

         where  p.estado_id <> 1
         and    p.cierrecanje_id = ?
         order by p.id asc)"; 
        
        $resultado = R::getAll($sql, array($this->cierrecanje_id));
        
        return $resultado;
    }
    
    public function previewSantander(){
        $ordenes_in = implode(',', $this->ordenes);
        $sql = "SELECT  p.cierre_id,
                        sum(p.cantidad) sumaCantidad
                        FROM    canje p                        
                WHERE   p.id in ($ordenes_in)
                GROUP BY p.cierre_id";
        $resultado = R::getAll($sql);
        return $resultado;
    }
 
    public function enviarSantander(){        
        $estado = R::load('estadoorden', 3);
        $resultado = array('exito'=>1, 'resultado'=>'Ordenes Enviadas Correctamente');
        foreach ($this->ordenes as $id) {
            $orden = R::load('canje', $id);
            $orden->estado = $estado;
            $orden->envio = 'S';
            $orden->fhenvio = R::isoDateTime();
            R::store($orden);
        }
        return $resultado;
    }
    
    
//    public function previewMercado(){
//        $ordenes_in = implode(',', $this->ordenes);
//        $sql = "SELECT  cierrecanje_id,
//                        tipopersona,
//                        numcomitente,
//                        cantidad,
//                        cuit,
//                        FROM    canje
//                WHERE   id in ({$ordenes_in})
//                ORDER BY cierrecanje_id ";
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
//        $nombreZip = FCPATH . 'generadas/' . date('Y-m-d-H-i-s') . '-canjees.zip';
//        $this->zip->archive($nombreZip);
//        
//        return array('uris'=>array(base_url() . 'generadas/' . basename($nombreZip)));
//    }
    
    
    public function previewMercado(){
        $ordenes_in = implode(',', $this->ordenes);
        
        $sql = "SELECT  cierrecanje_id,
                        tipopersona,
                        numcomitente,
                        tipopersona,
                        cantidad,
                        arancel,
                        plazo,
                        cuit,
                        posicion
                        FROM    canje
                WHERE   id in ({$ordenes_in})
                ORDER BY cierrecanje_id, plazo ";
        $resultado = R::getAll($sql);
        
//        $contenido = array();
        $colocacionAnterior = 0;
        $contenidoInd = 0;
        foreach ($resultado as $indice=>$fila){
            
//            print_r("fila");
//            print_r($fila);
            
            $plazo = R::findOne('plazocanje', 'cierrecanje_id = ? and id = ?', array($fila['cierrecanje_id'], $fila['plazo']));    
            
//            print_r("plazo");
//            echo "<pre>";
//            print_r($plazo);
//            echo "<pre>";
//            echo "<pre>";
//            print_r($fila['moneda']);
//            echo "<pre>";
//            
//            die;
//            
            $titulo = '';
            
            $titulo = $plazo['tituloC'];
            
            /*
            switch ($fila['moneda']){
                case '$': //Todos tendrían que entrar por pesos.
                    if ($fila['tramo'] == 'Competitiva'){
                        
//                        $sql = "select *
//                            from   plazo
//                            where  id = ?
//                            ";
//                        $result = R::getRow($sql, array($plazo['id']));
//                        $titulo = $result['tituloC'];
                        $titulo = $plazo['tituloC'];
                        $precio = $fila['precio'];
                    } else {                      //Acá vá el NO competitivo
//                        $sql = "select *
//                            from   plazo
//                            where  id = ?
//                            ";
//                        $result = R::getRow($sql, array($plazo['id']));
//                        $titulo = $result['tituloNCJ'];
                        $titulo = $plazo['tituloNCJ'];
                        $precio = '';

                    }
                    break;
            }
            */
            
            
            $titulo = utf8_decode($titulo);

            /*
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
            */
            
            if ($fila['tipopersona'] == 'FISICA'){
                $tipoPersona = 'Persona Fisica';
            } else {
                $tipoPersona = 'Persona Juridica';
            }

            $colocacion = $plazo->colocacion;
            
//            print_r(" colocacion ");
//            print_r($colocacion);
//            die;
            
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
            
            $archivo = date('Y-m-d-H-i-s') . '-' . $data['colocacion'] . '-canje.txt';
            $this->zip->add_data($archivo, $data['datos']);
            //file_put_contents($archivo, $data['datos']);
            //array_push($uris, base_url() . 'generadas/' . basename($archivo));
        }
        $nombreZip = FCPATH . 'generadas/' . date('Y-m-d-H-i-s') . '-canje.zip';
        $this->zip->archive($nombreZip);
        
        return array('uris'=>array(base_url() . 'generadas/' . basename($nombreZip)));
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    public function enviarMercado(){
        $estado = R::load('estadoorden', 3);
        $resultado = array('exito'=>1, 'resultado'=>'Ordenes Enviadas Correctamente');
        foreach ($this->ordenes as $id) {
            $orden = R::load('canje', $id);
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
                            from   canje
                            where  cierrecanje_id = ?
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
                            from    canje
                            where   usuario_id = ?
                            and     cierrecanje_id = ?
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
    
    public function getPlazoPorNombre(){
        $sql = "select id, moneda, plazo, especie, colocacion, cierrecanje_id "
                . "from plazocanje "
                . "where plazo = ? AND cierrecanje_id = ?";
        $plazos = R::getRow($sql, array($this->plazoNombre, $this->cierre)); 

        return $plazos;
    }
    
    public function grabarExcel(){
                  
        $usuarioParam = $this->session->userdata('usuario');

        $orden = R::load('canje', $this->id);
        $cierre = R::load('cierrecanje', $this->cierre);
        $usuario = R::load('usuario', $usuarioParam['id']);
        $estadoorden = R::load('estadoorden', 1);        
        $this->moneda = '$';

        $plazos = $this->Canje_model->getPlazos();
               
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
                    
                    $orden = R::dispense('canje');
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
                   
                    $this->Canje_model->plazoNombre = $plazoNombre;
                    $resultadoPlazo = $this->Canje_model->getPlazoPorNombre();
                    
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
                    
                    
                    /*
                    // Opcion                   
                    $bonoNombre = $sheet->getCellByColumnAndRow(4,$row)->getFormattedValue();

                    $this->Canje_model->plazo = $plazo;
                    $this->Canje_model->bonoNombre = $bonoNombre;
                    $resultado = $this->Canje_model->getPlazocanjebonopordescripcion();
                    
                    
                    
                    $bono = 0;
                    $tipo = 0;
                    
                    if($resultado){
                        $bono = $resultado['id'];
                        $tipo = $resultado['tipo'];
                    }else{
                        $error.="Plazo y bono invalido en fila {$row} <br>";
                        $valido = false;                    
                    }
                    
                     * 
                     */

                    
                    
                    //Posicion
                    $posicion = $sheet->getCellByColumnAndRow(5,$row)->getOldCalculatedValue();     
                    if($posicion == 0){
                      $posicion = $sheet->getCellByColumnAndRow(5,$row)->getCalculatedValue();
                    }
                    
                    $estaConfirmado = $sheet->getCellByColumnAndRow(6,$row)->getValue();     
                    
                    
                    $orden->cantidad = $cantidad;
                    $orden->arancel = $arancel;
//                    $orden->bono = $bono;
//                    $orden->bonoNombre = $bonoNombre;
//                    $orden->tipo = $tipo;

                    $orden->posicion = $posicion;
                    $orden->estaConfirmado = $estaConfirmado;

                    $orden->plazo = $plazo;
                    $orden->estado = $estadoorden;
                    $orden->fhmodificacion = R::isoDateTime();
                    $orden->usuario = $usuario;
                    $orden->cierrecanje = $cierre;

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

class Model_Canje extends RedBean_SimpleModel {
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
            $auditoria->table = 'canje';
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
        $auditoria->table = 'canje';
        $auditoria->tableId = $this->prev['id'];
        $auditoria->anterior = json_encode($this->prev);
        $auditoria->actual = json_encode(array('operacion'=>'Registro Borrado'));
        R::store($auditoria);        
    }    
}

class Model_Cierrecanje extends RedBean_SimpleModel {
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
            $auditoria->table = 'cierrecanje';
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
        $auditoria->table = 'cierrecanje';
        $auditoria->tableId = $this->prev['id'];
        $auditoria->anterior = json_encode($this->prev);
        $auditoria->actual = json_encode(array('operacion'=>'Registro Borrado'));
        R::store($auditoria);        
    }
}