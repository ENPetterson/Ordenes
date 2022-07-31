<?php

class LetesPesos_model extends CI_Model{
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
    
    public $cierreletespesos_id;
    public $fechahora;
    public $plazos;
    public $plazosBorrar;
    
    public $instrumento;
    
    public function saveOrden(){
        $usuarioParam = $this->session->userdata('usuario');
        $usuario = R::load('usuario', $usuarioParam['id']);

        $orden = R::load('letespesos', $this->id);
        $ordenAnterior = $orden;
        if ($orden->id == 0 ){
            $estadoorden = R::load('estadoorden', 1);
            $cierreActual = $this->getCierreActual();
            if ($cierreActual['cerrado']){
                return array('id'=>0);
            } else {
                $cierre = R::load('cierre', $cierreActual['id']);
                $orden->cierreletespesos = $cierre;
            }
            $orden->usuario = $usuario;
            $orden->estado = $estadoorden;
        }
        $orden->tramo = $this->tramo;
        $orden->numcomitente = $this->numComitente;
        $orden->moneda = $this->moneda;
        $orden->cable = $this->cable;
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
        $orden = R::load('letespesos', $this->id);
        return $orden->export();
    }
    
    public function delOrden(){
        foreach ($this->ordenes as $id){
            $letesPesos = R::load('letespesos', $id);
            R::trash($letesPesos);
        }
    }
    
    
    public function grilla(){
        $sql = "select l.id,
                l.tramo,
                l.numComitente,
                l.moneda,
                l.cable,
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
         from   letespesos l
         join   estadoorden eo 
         on     l.estado_id = eo.id
         join   cierreletespesos c
         on     l.cierreletespesos_id = c.id
         where  l.usuario_id = ?
         and    l.cierreletespesos_id = ?
         order by l.fhmodificacion desc"; 
        
        $resultado = R::getAll($sql, array($this->usuario_id, $this->cierreletespesos_id));
        
        return $resultado;

    }
    
    public function enviarOrdenes(){        
        $estado = R::load('estadoorden', 2);
        $resultado = array('exito'=>1, 'resultado'=>'Ordenes Enviadas Correctamente');
        $ahora = new DateTime();
        foreach ($this->ordenes as $id) {
            $orden = R::load('letespesos', $id);
            $fechaCierre = new DateTime($orden->cierreletespesos->fechahora);
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
            $orden = R::load('letespesos', $id);
            $orden->estado = $estado;
            R::store($orden);
        }
        return $resultado;
    }
    
    
    public function getCierre(){
        $cierreBean = R::load('cierreletespesos', $this->cierreletespesos_id);
        $cierre = $cierreBean->export();
        $sql = "select * from plazoletespesos where cierreletespesos_id = ? order by moneda, plazo";
        $plazos = R::getAll($sql, array($cierreBean->id));
        $cierre['plazos'] = $plazos;
        return $cierre;
    }
    
    public function saveCierre(){
        $cierre = R::load('cierreletespesos', $this->cierreletespesos_id);
        $cierre->fechahora = $this->fechahora;
        $cierre->instrumento = $this->instrumento;
        R::store($cierre);
        
        foreach ((array) $this->plazos as $plazoItem) {
            $plazo = R::load('plazoletespesos', $plazoItem['id']);
            $plazo->moneda = $plazoItem['moneda'];
            $plazo->plazo = $plazoItem['plazo'];
            $plazo->especie = $plazoItem['especie'];
            $plazo->colocacion = $plazoItem['colocacion'];
            $plazo->tituloC = $plazoItem['tituloC'];
            $plazo->tituloNCJ = $plazoItem['tituloNCJ'];
            $plazo->tituloNCF = $plazoItem['tituloNCF'];
            $plazo->cierreletespesos = $cierre;
            R::store($plazo);
        }
        
        foreach ((array) $this->plazosBorrar as $plazoletespesos_id){
            $plazo = R::load('plazo', $plazoletespesos_id);
            R::trash($plazo);
        }
        
        return $cierre->export();
    }
    
    public function delCierre(){
        $cierre = R::load('cierreletespesos', $this->cierreletespesos_id);
        $cierre->ownPlazoletespesos = array();
        R::store($cierre);
        R::trash($cierre);
    }
    
    public function getCierreActual(){
        $cierreActual = R::findOne('cierreletespesos', 'fechahora > NOW() order by fechahora' );
        if (is_null($cierreActual)){
            return array('cerrado'=>true);
        } else {
            $this->cierreletespesos_id = $cierreActual->id;
            $cierre = $this->getCierre();
            return $cierre;
        }
    }
    
    public function getPlazos(){
        if ($this->cierreletespesos_id > 0){
            $sql = "select plazo from plazoletespesos where cierreletespesos_id = ? and moneda = ? order by plazo";
            $plazos = R::getCol($sql, array($this->cierreletespesos_id, $this->moneda));
        } else {
            $sql = "select plazo from plazoletespesos where moneda = ? and cierreletespesos_id = (SELECT id FROM cierreletespesos where fechahora > NOW() order by fechahora limit 0,1) order by plazo";
            $plazos = R::getCol($sql, array($this->moneda));
        }
        return $plazos;
    }
    
    public function getPlazosEspecie(){
        if ($this->cierreletespesos_id > 0){
            $sql = "select plazo, especie from plazoletespesos where cierreletespesos_id = ? and moneda = ? order by plazo";
            $plazos = R::getAll($sql, array($this->cierreletespesos_id, $this->moneda));
        } else {
            $sql = "select plazo, especie from plazoletespesos where moneda = ? and cierreletespesos_id = (SELECT id FROM cierreletespesos where fechahora > NOW() order by fechahora limit 0,1) order by plazo";
            $plazos = R::getAll($sql, array($this->moneda));
        }
        
        return $plazos;
    }
    
    
    public function getMonedas(){
        if ($this->cierreletespesos_id > 0){
            $sql = "select distinct moneda from plazoletespesos where cierreletespesos_id = ? ";
            $monedas = R::getCol($sql, array($this->cierreletespesos_id));
        } else {
            $sql = "select distinct moneda from plazoletespesos where cierreletespesos_id = (SELECT id FROM cierreletespesos where fechahora > NOW() order by fechahora limit 0,1)";
            $monedas = R::getCol($sql);
        }
        $sql = "select * from moneda where simbolo in (" . R::genSlots($monedas) . ")";
        $resultado = R::getAll($sql, $monedas);
        return $resultado;
    }
    
    public function getCierres(){
        $cierres = R::getAll('select * from cierreletespesos order by fechahora desc');
        return $cierres;
    }
 
    public function grillaResumen(){        
        $ordenes_in = implode(',', $this->ordenes);
        $sql = "SELECT  plazo, 
                        moneda, 
                        count(*)      cantidadOrdenes, 
                        sum(cantidad) sumaCantidad
                        FROM    letespesos
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
                l.cable,
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
         from   letespesos l
         join   estadoorden eo 
         on     l.estado_id = eo.id
         join   cierreletespesos c
         on     l.cierreletespesos_id = c.id
         join   usuario u
         on     l.usuario_id = u.id
         where  l.estado_id <> 1
         and    l.cierreletespesos_id = ?
         order by l.fhmodificacion desc)"; 
        
        $resultado = R::getAll($sql, array($this->cierreletespesos_id));
        
        return $resultado;
    }
    
    public function getOrdenes(){
        
        if ($this->cierreletespesos_id == 0){
            $this->getCierreActual();
        }
        
        $sql = "(select l.id,
                l.moneda,
                l.plazo,
                l.cantidad,
                l.precio
         from   letespesos l
         join   estadoorden eo 
         on     l.estado_id = eo.id
         join   cierreletespesos c
         on     l.cierreletespesos_id = c.id
         join   usuario u
         on     l.usuario_id = u.id
         where  l.estado_id <> 4
         and    l.cierreletespesos_id = ?
         and    l.numComitente = ?
         order by l.fhmodificacion desc)"; 
        
        $resultado = R::getAll($sql, array($this->cierreletespesos_id, $this->numComitente));
        
        return $resultado;
    }
    
    public function previewSantander(){
        $ordenes_in = implode(',', $this->ordenes);
        $sql = "SELECT  l.cierreletespesos_id,
                        l.plazo, 
                        l.moneda, 
                        l.precio,
                        p.especie, 
                        sum(l.cantidad) sumaCantidad
                        FROM    letespesos l
                        left outer join    plazoletespesos p 
                        ON (l.cierreletespesos_id = p.cierreletespesos_id AND l.plazo = p.plazo AND l.moneda = p.moneda)
                WHERE   l.id in ($ordenes_in)
                GROUP BY l.cierreletespesos_id, l.plazo, l.moneda, l.precio, p.especie";
        $resultado = R::getAll($sql);
        return $resultado;
    }
 
    public function enviarSantander(){        
        $estado = R::load('estadoorden', 3);
        $resultado = array('exito'=>1, 'resultado'=>'Ordenes Enviadas Correctamente');
        foreach ($this->ordenes as $id) {
            $orden = R::load('letespesos', $id);
            $orden->estado = $estado;
            $orden->envio = 'S';
            $orden->fhenvio = R::isoDateTime();
            R::store($orden);
        }
        return $resultado;
    }
    
    
    public function previewMercado(){
        $ordenes_in = implode(',', $this->ordenes);
        $sql = "SELECT  cierreletespesos_id,
                        tramo,
                        moneda, 
                        tipopersona,
                        plazo, 
                        numcomitente,
                        cantidad,
                        precio,
                        cuit,
                        comision
                        FROM    letespesos
                WHERE   id in ({$ordenes_in})
                ORDER BY cierreletespesos_id, moneda, plazo ";
        $resultado = R::getAll($sql);
        
        //$contenido = "";
        $colocacionAnterior = 0;
        $contenidoInd = 0;
        foreach ($resultado as $indice=>$fila){
            $plazo = R::findOne('plazoletespesos', 'cierreletespesos_id = ? and moneda = ? and plazo = ?', array($fila['cierreletespesos_id'], $fila['moneda'], $fila['plazo']));            
            
            switch ($fila['moneda']){
                case '$':
                    if ($fila['tramo'] == 'Competitiva'){
                        $titulo = $plazo->tituloC;
                        $precio = $fila['precio'];
                    } else {
                        if ($fila['tipopersona'] == 'JURIDICA'){
                            $titulo = $plazo->tituloNCJ;
                        } else {
                            $titulo = $plazo->tituloNCF;
                        }
                        $precio = '';
                    }
                    break;
                case 'u$s':   
                    //...
                break;
            }
            
            /*
            switch ($fila['moneda']){
                case '$':
                    if ($fila['tramo'] == 'Competitiva'){
                        $titulo = utf8_decode('LECAPs a 49 dias en Pesos Vto 28/06/2019  - Int Pesos - Tramo Competitivo');
                        $precio = $fila['precio'];
                    } else {
                        $titulo = utf8_decode('LECAPs a 49 dias en Pesos Vto 28/06/2019  - Int Pesos - Tramo NO Competitivo');
                        $precio = '';
                    }
                    break;

                case 'u$s':
                    if ($fila['tramo'] == 'Competitiva'){
                        $titulo = utf8_decode('LECAPs a 49 dias en Pesos Vto 28/06/2019  - Int Dolares - Tramo Competitivo');
                        $precio = $fila['precio'];
                    } else {
                        $titulo = utf8_decode('LECAPs a 49 dias en Pesos Vto 28/06/2019  - Int Dolares - Tramo NO Competitivo');
                        $precio = '';
                    }
                    break;
            }
            */
             
            
            /*
            switch ($fila['moneda']){
                case '$': //Todos tendrían que entrar por pesos.
                    if ($fila['tramo'] == 'Competitiva'){
                        switch ($fila['plazo']){ //Acá vá el competitivo
                            case '180 Pesos C.E.R':
                                $titulo = utf8_decode("TX21 - Bonos del Tesoro Nacional en Pesos con Ajuste por C.E.R. 1% vencimiento 2021");
                                break;
                            case '180 Pesos BADLAR':
                                $titulo = utf8_decode("TB21 - Bonos del Tesoro Nacional en Pesos BADLAR Privada + 100 pbs. vencimiento 2021");
                                break;
                            case '180 Vinculados Dolar':
                                $titulo = utf8_decode("TV21 - Bonos del Tesoro Nacional Vinculados al Dólar 4% vencimiento 2021");
                                break;
                        }
                        $precio = $fila['precio'];
                    } else {                      //Acá vá el NO competitivo
                        
                        switch ($fila['plazo']){
                            case '180 Pesos C.E.R':
                                $titulo = utf8_decode("TX21 - Bonos del Tesoro Nacional en Pesos con Ajuste por C.E.R. 1% vencimiento 2021");
                                break;
                            case '180 Pesos BADLAR':
                                $titulo = utf8_decode("TB21 - Bonos del Tesoro Nacional en Pesos BADLAR Privada + 100 pbs. vencimiento 2021");
                                break;
                            case '180 Vinculados Dolar':
                                $titulo = utf8_decode("TV21 - Bonos del Tesoro Nacional Vinculados al Dólar 4% vencimiento 2021");
                                break;
                        }
                        $precio = '';
 
                    }
                    break;

//                case 'u$s':
//                    if ($fila['tramo'] == 'Competitiva'){
//                        switch ($fila['plazo']){
//                            case 35:
//                                $titulo = utf8_decode("LECAPs a 35 dias en Pesos Vto 04/10/2019  - Int Dolares - Tramo Competitivo");
//                                break;
//                            case 315:
//                                $titulo = utf8_decode("LECAPs a 315 dias en Pesos Vto 29/05/2020 - Int Dolares - Tramo Competitivo");
//                                break;
//                        }
//                        $precio = $fila['precio'];
//                    } else {
//                        switch ($fila['plazo']){
//                            case 35:
//                                $titulo = utf8_decode("LECAPs a 35 dias en Pesos Vto 04/10/2019  - Int Dolares - Tramo NO Competitivo");
//                                break;
//                            case 315:
//                                $titulo = utf8_decode("LECAPs a 315 dias en Pesos Vto 29/05/2020  - Int Dolares - Tramo NO Competitivo");
//                                break;
//                        }
//                        $precio = '';
//                    }
//                    break;
            } // Fin switch
            
            */
            
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
            $contenido[$contenidoInd]['datos'] .= $colocacion . "\t" . $titulo . "\t" . $precio . "\t" . $fila['cantidad'] . "\t\t" . $fila['numcomitente'] . "\t\t" . $fila['cuit']  . "\t\t\t\t" . $tipoPersona .  "\t\t\tCUIT\r\n";
            
        }
        $this->load->library('zip');
        foreach ($contenido as $data){
            $archivo = date('Y-m-d-H-i-s') . '-' . $data['colocacion'] . '-letespesos.txt';
            $this->zip->add_data($archivo, $data['datos']);
            //file_put_contents($archivo, $data['datos']);
            //array_push($uris, base_url() . 'generadas/' . basename($archivo));
        }
        $nombreZip = FCPATH . 'generadas/' . date('Y-m-d-H-i-s') . '-letespesos.zip';
        $this->zip->archive($nombreZip);
        
        return array('uris'=>array(base_url() . 'generadas/' . basename($nombreZip)));
    }
    
    public function enviarMercado(){        
        $estado = R::load('estadoorden', 3);
        $resultado = array('exito'=>1, 'resultado'=>'Ordenes Enviadas Correctamente');
        foreach ($this->ordenes as $id) {
            $orden = R::load('letespesos', $id);
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
                            from   letespesos
                            where  cierreletespesos_id = ?
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
                            from    letespesos
                            where   usuario_id = ?
                            and     cierreletespesos_id = ?
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
        $resultado = "LETESPESOS ";
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

class Model_Letespesos extends RedBean_SimpleModel {
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
            $auditoria->table = 'letespesos';
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
        $auditoria->table = 'letespesos';
        $auditoria->tableId = $this->prev['id'];
        $auditoria->anterior = json_encode($this->prev);
        $auditoria->actual = json_encode(array('operacion'=>'Registro Borrado'));
        R::store($auditoria);        
    }    
}

class Model_Cierreletespesos extends RedBean_SimpleModel {
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
            $auditoria->table = 'cierreletespesos';
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
        $auditoria->table = 'cierreletespesos';
        $auditoria->tableId = $this->prev['id'];
        $auditoria->anterior = json_encode($this->prev);
        $auditoria->actual = json_encode(array('operacion'=>'Registro Borrado'));
        R::store($auditoria);        
    }
}
