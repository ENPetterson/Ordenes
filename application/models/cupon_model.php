<?php

class Cupon_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public $id;
    public $numComitente;
    public $comision;
    public $cantidad;
    public $cantidadACrecer;
    public $precio;
    public $segundaParte;
    public $cantidadAcrecerSegunda;
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
            if ($cierreActual['cerrado']){
                return array('id'=>0);
            } else {
                $cierre = R::load('cierrecupon', $cierreActual['id']);
                $orden->cierrecupon = $cierre;
            }
            $orden->usuario = $usuario;
            $orden->estado = $estadoorden;
        }
        $orden->numcomitente = $this->numComitente;
        $orden->comision = $this->comision;
        $orden->cantidad = $this->cantidad;
        $orden->cantidadACrecer = $this->cantidadACrecer;
        $orden->precio = $this->precio;
        $orden->segundaParte = $this->segundaParte;
        $orden->cantidadAcrecerSegunda = $this->cantidadAcrecerSegunda;
        $orden->comitente = $this->comitente;
        $orden->tipopersona = $this->tipoPersona;
        $orden->oficial = $this->oficial;
        $orden->cuit = (double) $this->cuit;
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
    
    
    public function grilla(){
        $sql = "select p.id,
                p.numComitente,
                p.comision,
                p.cantidad,
                p.cantidadACrecer,
                p.precio,
                p.segundaParte,
                p.cantidadAcrecerSegunda,
                p.comitente,
                p.tipoPersona,
                p.oficial,
                p.cuit,
                eo.estado,
                p.estado_id,
                p.fhmodificacion,
                c.fechahora as cierre
         from   cupon p
         join   estadoorden eo 
         on     p.estado_id = eo.id
         join   cierrecupon c
         on     p.cierrecupon_id = c.id
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
        return $cierre;
    }
    
    public function saveCierre(){
        $cierre = R::load('cierrecupon', $this->cierrecupon_id);
        $cierre->fechahora = $this->fechahora;
        R::store($cierre);
        
        return $cierre->export();
    }
    
    public function delCierre(){
        $cierre = R::load('cierrecupon', $this->cierrecupon_id);
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
        $sql = "(select p.id,
                p.numComitente,
                p.comision,
                p.cantidad,
                p.cantidadACrecer,
                p.precio,
                p.segundaParte,
                p.cantidadAcrecerSegunda,
                p.comitente,
                p.tipoPersona,
                p.oficial,
                concat(u.apellido, ' ', u.nombre) as usuario,
                p.cuit,
                eo.estado,
                p.estado_id,
                p.fhmodificacion,
                p.envio,
                p.fhenvio
         from   cupon p
         join   estadoorden eo 
         on     p.estado_id = eo.id
         join   cierrecupon c
         on     p.cierrecupon_id = c.id
         join   usuario u
         on     p.usuario_id = u.id
         where  p.estado_id <> 1
         and    p.cierrecupon_id = ?
         order by p.fhmodificacion desc)"; 
        
        $resultado = R::getAll($sql, array($this->cierrecupon_id));
        
        return $resultado;
    }
    
    public function previewSantander(){
        $ordenes_in = implode(',', $this->ordenes);
        $sql = "SELECT  p.cierre_id,
                        p.precio
                        sum(p.cantidad) sumaCantidad
                        FROM    cupon p                        
                WHERE   p.id in ($ordenes_in)
                GROUP BY p.cierre_id, p.precio";
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
    
    
    public function previewMercado(){
        $ordenes_in = implode(',', $this->ordenes);
        $sql = "SELECT  cierrecupon_id,
                        tipopersona,
                        numcomitente,
                        cantidad,
                        precio,
                        cuit,
                        comision
                        FROM    cupon
                WHERE   id in ({$ordenes_in})
                ORDER BY cierrecupon_id ";
        $resultado = R::getAll($sql);
        
        $contenido = "";
        $colocacionAnterior = 0;
        $contenidoInd = 0;
        $colocacion = 'colocacion';
        foreach ($resultado as $indice=>$fila){
            
            
            $contenido[$contenidoInd]['colocacion'] = $colocacion;
            $contenido[$contenidoInd]['datos'] .= $colocacion . "\t" . $fila['precio'] . "\t" . $fila['cantidad'] . "\t\t" . $fila['numcomitente'] . "\t\t" . $fila['cuit']  . "\t\t\t\t" . $fila['tipopersona'] . "\t\r\n";
            
        }
        $this->load->library('zip');
        foreach ($contenido as $data){
            //$archivo = FCPATH . 'generadas/' . date('Y-m-d-H-i-s') . '-' . $data['colocacion'] . '-lebacs.dat';
            $archivo = date('Y-m-d-H-i-s') . '-' . $data['colocacion'] . '-bonos.txt';
            $this->zip->add_data($archivo, $data['datos']);
            //file_put_contents($archivo, $data['datos']);
            //array_push($uris, base_url() . 'generadas/' . basename($archivo));
        }
        $nombreZip = FCPATH . 'generadas/' . date('Y-m-d-H-i-s') . '-cupones.zip';
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
                                    comision,
                                    cantidad,
                                    precio,
                                    comitente,
                                    tipopersona,
                                    oficial,
                                    cuit
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
                                        <td align='right'>{$orden['numcomitente']}</td>    
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