<?php

class Letes_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public $id;
    //public $tramo;
    public $numComitente;
    public $moneda;
    public $cable;
    public $plazo;
    public $comision;
    public $cantidad;
    //public $precio;
    public $comitente;
    public $tipoPersona;
    public $oficial;
    public $cuit;
    public $ordenes;
    
    public $usuario_id;
    
    public $cierreletes_id;
    public $fechahora;
    public $plazos;
    public $minimos;
    public $colocacionPesos;
    public $colocacionDolares;
    
    private $workbook;
    private $sheetIndex;
    
    
    
    
    public function saveOrden(){
        
        $usuarioParam = $this->session->userdata('usuario');
        $usuario = R::load('usuario', $usuarioParam['id']);

        $orden = R::load('letes', $this->id);
        $ordenAnterior = $orden;
        if ($orden->id == 0 ){
            $estadoorden = R::load('estadoorden', 1);
            $cierreActual = $this->getCierreActual();
            if ($cierreActual['cerrado']){
                return array('id'=>0);
            } else {
                $cierre = R::load('cierreletes', $cierreActual['id']);
                $orden->cierreletes = $cierre;
            }
            $orden->usuario = $usuario;
            $orden->estado = $estadoorden;
        }
        //$orden->tramo = $this->tramo;
        $orden->numcomitente = $this->numComitente;
        $orden->moneda = $this->moneda;
        $orden->cable = $this->cable;
        $orden->plazo = $this->plazo;
        $orden->comision = $this->comision;
        $orden->cantidad = $this->cantidad;
        //$orden->precio = $this->precio;
        $orden->comitente = $this->comitente;
        $orden->tipopersona = $this->tipoPersona;
        $orden->oficial = $this->oficial;
        $orden->cuit = (double) $this->cuit;
        $orden->fhmodificacion =  R::isoDateTime();
        $this->id = R::store($orden);
        
        return $orden->export();
    }
    
    public function getOrden(){
        $orden = R::load('letes', $this->id);
        return $orden->export();
    }
    
    public function delOrden(){
        foreach ($this->ordenes as $id){
            $letes = R::load('letes', $id);
            R::trash($letes);
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
         from   letes l
         join   estadoorden eo 
         on     l.estado_id = eo.id
         join   cierreletes c
         on     l.cierreletes_id = c.id
         where  l.usuario_id = ?
         and    l.cierreletes_id = ?
         order by l.fhmodificacion desc"; 
        
        $resultado = R::getAll($sql, array($this->usuario_id, $this->cierreletes_id));
        
        return $resultado;

    }
    
    public function enviarOrdenes(){        
        $estado = R::load('estadoorden', 2);
        $resultado = array('exito'=>1, 'resultado'=>'Ordenes Enviadas Correctamente');
        $ahora = new DateTime();
        foreach ($this->ordenes as $id) {
            $orden = R::load('letes', $id);
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
            $orden = R::load('letes', $id);
            $orden->estado = $estado;
            R::store($orden);
        }
        return $resultado;
    }
    
    
    public function getCierre(){
        $cierre = R::load('cierreletes', $this->cierreletes_id);
        return $cierre->export();
    }
    
    public function saveCierre(){
        $cierre = R::load('cierreletes', $this->cierreletes_id);
        $cierre->fechahora = $this->fechahora;
        $cierre->plazos = $this->plazos;
        $cierre->minimos = $this->minimos;
        $cierre->colocacionPesos = $this->colocacionPesos;
        $cierre->colocacionDolares = $this->colocacionDolares;
        R::store($cierre);
        return $cierre->export();
    }
    
    public function delCierre(){
        $cierre = R::load('cierreletes', $this->cierreletes_id);
        R::trash($cierre);
    }
    
    public function getCierreActual(){
        $cierreActual = R::findOne('cierreletes', 'fechahora > NOW() order by fechahora' );
        if (is_null($cierreActual)){
            return array('cerrado'=>true);
        } else {
            return $cierreActual->export();
        }
    }
    
    public function getCierres(){
        $cierres = R::getAll('select * from cierreletes order by fechahora desc');
        return $cierres;
    }
 
    public function grillaResumen(){        
        $ordenes_in = implode(',', $this->ordenes);
        $sql = "SELECT  plazo, 
                        moneda, 
                        count(*)      cantidadOrdenes, 
                        sum(cantidad) sumaCantidad
                        FROM    letes
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
         from   letes l
         join   estadoorden eo 
         on     l.estado_id = eo.id
         join   cierreletes c
         on     l.cierreletes_id = c.id
         join   usuario u
         on     l.usuario_id = u.id
         where  l.estado_id <> 1
         and    l.cierreletes_id = ?
         order by l.fhmodificacion desc)"; 
        
        $resultado = R::getAll($sql, array($this->cierreletes_id));
        
        return $resultado;
    }
    
    public function previewMercado(){
        $ordenes_in = implode(',', $this->ordenes);
        $sql = "SELECT  cierreletes_id,
                        tramo,
                        moneda, 
                        tipopersona,
                        plazo, 
                        numcomitente,
                        cantidad,
                        precio,
                        cuit,
                        comision
                        FROM    letes
                WHERE   id in ({$ordenes_in})
                ORDER BY cierreletes_id, moneda ";
        $resultado = R::getAll($sql);
        $colocacionAnterior = 0;
        $contenidoInd = 0;

        foreach ($resultado as $indice=>$fila){
            
            $cierre = R::load('cierreletes', $fila['cierreletes_id']);
            
            $tramo = 'Tramo General';
            if ($fila['tipopersona'] == 'FISICA'){
                $tipoPersona = "Persona Fisica";
                if ($fila['cantidad'] <= 50000){
                    $tramo = 'Tramo Minorista';
                }
            } else {
                $tipoPersona = "Persona Juridica";
            }
            
            if ($fila['moneda'] == '$'){
                $colocacion = $cierre->colocacionPesos;
                $integracion = ' - Integracion Pesos';
            } else {
                $colocacion = $cierre->colocacionDolares;
                $integracion = ' - Integracion Dolares';
            }
            
            
            
            $titulo = $tramo . ' U$S ' . $fila['plazo'] . ' dias' . $integracion;
            
            if ($indice == 0){
                $colocacionAnterior = $colocacion;
            }
            
            if ($colocacion <> $colocacionAnterior){
                $contenidoInd++;
                $colocacionAnterior = $colocacion;
            }
            
            $contenido[$contenidoInd]['colocacion'] = $colocacion;
            //$contenido[$contenidoInd]['datos'] .= $colocacion . "\t" . $titulo . "\t\t" . $fila['cantidad'] . "\t\t" . $this->formatearCuit($fila['cuit']) . "\t" . $fila['numcomitente'] . "\r\n";
            //Oferta;Colocación;Colocador;Título;Fecha Liq.;Agente;De Terceros;Tipo;Valor;%/$;Cantidad;Moneda;Porc. Inv.;Estado;Fecha Ing.;Observaciones;Cliente/Comitente;CUIT;Nombre;Nacionalidad;Categoria;Tipo Persona

            $contenido[$contenidoInd]['datos'] .= $colocacion . "\t" . $titulo . "\t\t" . $fila['cantidad'] . "\t\t" . $fila['numcomitente'] . "\t\t" . $fila['cuit'] . "\t\t\t\t" . $tipoPersona .  "\r\n";
            
        }
        
        $this->load->library('zip');
        foreach ($contenido as $data){
            //$archivo = FCPATH . 'generadas/' . date('Y-m-d-H-i-s') . '-' . $data['colocacion'] . '-lebacs.dat';
            $archivo = date('Y-m-d-H-i-s') . '-' . $data['colocacion'] . '-letes.txt';
            $this->zip->add_data($archivo, $data['datos']);
        }
        $nombreZip = FCPATH . 'generadas/' . date('Y-m-d-H-i-s') . '-letes.zip';
        $this->zip->archive($nombreZip);
        
        return array('uris'=>array(base_url() . 'generadas/' . basename($nombreZip)));
        
    }
    
    public function enviarMercado(){
        $estado = R::load('estadoorden', 3);
        $resultado = array('exito'=>1, 'resultado'=>'Ordenes Enviadas Correctamente');
        foreach ($this->ordenes as $id) {
            $orden = R::load('letes', $id);
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
                            from   letes
                            where  cierreletes_id = ?
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
                            from    letes
                            where   usuario_id = ?
                            and     cierreletes_id = ?
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
                    $this->email->bcc(array('flagama@allariaycia.com','favio.fernandez@allariaycia.com','alejandro.oliveira@allariaycia.com'));

                    $this->email->subject('Tiene ordenes sin enviar a backoffice');
                    $this->email->message($html);	

                    $this->email->send();

                    echo $this->email->print_debugger();
                }
            }
        }
        
    }
    
}

class Model_Letes extends RedBean_SimpleModel {
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
            $auditoria->table = 'letes';
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
        $auditoria->table = 'letes';
        $auditoria->tableId = $this->prev['id'];
        $auditoria->anterior = json_encode($this->prev);
        $auditoria->actual = json_encode(array('operacion'=>'Registro Borrado'));
        R::store($auditoria);        
    }    
}

class Model_CierreLetes extends RedBean_SimpleModel {
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
            $auditoria->table = 'cierreletes';
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
        $auditoria->table = 'cierreletes';
        $auditoria->tableId = $this->prev['id'];
        $auditoria->anterior = json_encode($this->prev);
        $auditoria->actual = json_encode(array('operacion'=>'Registro Borrado'));
        R::store($auditoria);        
    }
}