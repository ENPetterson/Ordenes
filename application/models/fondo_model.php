<?php

class Fondo_model extends CI_Model{
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
    
    public $cierrefondo_id;
    public $fechahora;
    public $plazos;
    public $minimos;
//    public $colocacionPesos;
    public $colocacionDolares;
    /*
    public $colocacionLebacsNov;
    public $colocacionLebacsDic;
    */
//    public $colocacionA2J9;
    private $workbook;
    private $sheetIndex;
    
    
    
    
    public function saveOrden(){
        
        $usuarioParam = $this->session->userdata('usuario');
        $usuario = R::load('usuario', $usuarioParam['id']);

        $orden = R::load('fondo', $this->id);
//        $ordenAnterior = $orden;
        if ($orden->id == 0 ){
            $estadoorden = R::load('estadoorden', 1);
            $cierreActual = $this->getCierreActual();
            if (isset($cierreActual['cerrado'])){
                return array('id'=>0);
            } else {
                $cierre = R::load('cierrefondo', $cierreActual['id']);
                $orden->cierrefondo = $cierre;
            }
            $orden->usuario = $usuario;
            $orden->estado = $estadoorden;
        }
        
        $orden->fechaConcertacion = $this->fechaConcertacion;
        $orden->operador = $this->operador;
        $orden->operacion = $this->operacion;
        $orden->fondo = $this->fondo;
        $orden->nombreFondo = $this->nombreFondo;
        $orden->numComitente = $this->numComitente;
        $orden->nombreComitente = $this->nombreComitente;
        $orden->rescate = $this->rescate;
        $orden->importe = $this->importe;
        $orden->esAcdi = $this->esAcdi;
        $orden->noAcdiTipo = $this->noEsAcdiTipo;
        $orden->destinoRescate = $this->destinoRescate;
        $orden->totalCuotapartes = $this->totalCuotapartes;
        $orden->saldoMonetario = $this->saldoMonetario;
        $orden->saldoMonetarioDolar = $this->saldoMonetarioDolar;
        $orden->saldoMonetarioMep = $this->saldoMonetarioMep;
        $orden->origenFondos = $this->origenFondos;
//        $orden->usuario = $this->usuario;
        $orden->moneda = $this->moneda;
        $orden->observaciones = $this->observaciones;
        $orden->saldoAcdi = $this->saldoAcdi;
        $orden->saldoColocadorSimple = $this->saldoColocadorSimple;
//        $orden->fechaActualizacion =  R::isoDateTime();
        
        $this->id = R::store($orden);       
        
        return $orden->export();
    }
    
    public function getOrden(){
        $orden = R::load('fondo', $this->id);
        return $orden->export();
    }
    
    public function getOrdenesFondos(){
        
        if ($this->cierre_id == 0){
            $cierre = $this->getCierreActual();
            $this->cierre_id = $cierre['id'];
        }
        
        $sql = "(select l.id,
                l.moneda,
                l.operacion,
                l.fondo,
                l.nombreFondo,
                l.importe
         from   fondo l
         join   estadoorden eo 
         on     l.estado_id = eo.id
         join   cierrefondo c
         on     l.cierrefondo_id = c.id
         join   usuario u
         on     l.usuario_id = u.id
         where  l.estado_id <> 4
         and    l.cierrefondo_id = ?
         and    l.numComitente = ?
         order by l.id desc)"; 
        
        $resultado = R::getAll($sql, array($this->cierre_id, $this->numComitente));
               
        return $resultado;
    }
    
    public function delOrden(){
        foreach ($this->ordenes as $id){
            $fondo = R::load('fondo', $id);
            R::trash($fondo);
        }
    }
    
    
    public function grilla(){

        $sql = "select l.id,
                l.fechaConcertacion,
                l.operador,
                l.operacion,
                l.fondo,
                l.nombreFondo,
                l.numComitente,
                l.nombreComitente,
                l.rescate,
                l.importe,
                l.esAcdi,
                l.noAcdiTipo as esNoAcdiTipo,
                l.destinoRescate,
                l.totalCuotapartes,
                l.saldoMonetario,
                l.saldoMonetarioDolar,
                l.saldoMonetarioMep,
                l.origenFondos,
                l.usuario,
                l.moneda,
                l.observaciones,
                l.saldoAcdi,
                l.saldoColocadorSimple,
                eo.estado,
                l.estado_id,
                c.fechahora as cierre

         from fondo l
         join   cierrefondo c
         on     l.cierrefondo_id = c.id
         join   estadoorden eo 
         on     l.estado_id = eo.id
         where  l.usuario_id = ?
         and    l.cierrefondo_id = ?
         order by l.fechaConcertacion desc";

        $resultado = R::getAll($sql, array($this->usuario_id, $this->cierrefondo_id));
        

        
        return $resultado;

    }
    
    public function enviarOrdenes(){        
        $estado = R::load('estadoorden', 2);
        $resultado = array('exito'=>1, 'resultado'=>'Ordenes Enviadas Correctamente');
        $ahora = new DateTime();
        
        foreach ($this->ordenes as $id) {
            $orden = R::load('fondo', $id);            
            $usuario = R::load('usuario', $orden['usuario_id']);            
            
            $fechaCierre = new DateTime($orden->cierre->fechahora);
            if ($fechaCierre < $ahora){
                $resultado = array('exito'=>0, 'resultado'=>'Algunas ordenes no se pudieron enviar porque ya estaban cerradas');
            } else {

                $fecha = new DateTime();
                $fechaStr = $fecha->format('Y-m-d H:i:s');

//                $textoEmail = " <meta charset=\"utf-8\">
//                                <p>PRESENTACIÓN </p>
//                                <p>Estimado: </p>
//                                <p>Se ha generado una nueva orden Fondo. {$id} {$usuario['nombre']} {$usuario['apellido']}</p>
//                                <p>Saludos.</p>";
//
//                $this->load->model('Mailing_model');
//                $respuesta = $this->Mailing_model->enviarMail($textoEmail, 'Allaria Ledesma', 'micaela.petterson@allaria.com.ar', 'Ordenes Fondo', array('m.r.petterson@hotmail.com', 'micaela.petterson@allaria.com.ar', 'alejandro.oliveira@allaria.com.ar'), 'Ordenes Fondo', array(), array());
                
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
            $orden = R::load('fondo', $id);
            $orden->estado = $estado;
            R::store($orden);
        }
        return $resultado;
    }
    
    public function procesarOrdenes(){        
        $estado = R::load('estadoorden', 3);
        $resultado = array('exito'=>1, 'resultado'=>'Ordenes Procesadas Correctamente');
        foreach ($this->ordenes as $id) {
            $orden = R::load('fondo', $id);
            $orden->estado = $estado;
            R::store($orden);
        }
        return $resultado;
    }
    
    
    public function getCierre(){
        $cierre = R::load('cierrefondo', $this->cierrefondo_id);
        return $cierre->export();
    }
    
    public function saveCierre(){
        $cierre = R::load('cierrefondo', $this->cierrefondo_id);
        $cierre->fechahora = $this->fechahora;
        $cierre->plazos = $this->plazos;
        $cierre->minimos = $this->minimos;
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
        $cierre = R::load('cierrefondo', $this->cierrefondo_id);
        R::trash($cierre);
    }
    
    public function getCierreActual(){
        $cierreActual = R::findOne('cierrefondo', 'fechahora > NOW() order by fechahora' );
        
        if (is_null($cierreActual)){
            return array('cerrado'=>true);
        } else {
            return $cierreActual->export();
        }
    }
    
    public function getCierres(){
        $cierres = R::getAll('select * from cierrefondo order by fechahora desc');
        return $cierres;
    }
 
    public function grillaResumen(){        
        $ordenes_in = implode(',', $this->ordenes);
        $sql = "SELECT  plazo, 
                        moneda, 
                        count(*)      cantidadOrdenes, 
                        sum(cantidad) sumaCantidad
                        FROM    fondo
                WHERE   id in ({$ordenes_in})
                GROUP BY plazo, moneda";
        $resultado = R::getAll($sql);
        return $resultado;
    }
    
    public function procesarGrilla(){
        $sql = "(select l.id,
                l.fechaConcertacion,
                l.operador,
                l.operacion,
                l.fondo,
                l.nombreFondo,
                l.numComitente,
                l.nombreComitente,
                l.rescate,
                l.importe,
                l.esAcdi,
                l.noAcdiTipo as esNoAcdiTipo,
                l.destinoRescate,
                l.totalCuotapartes,
                l.saldoMonetario,
                l.saldoMonetarioDolar,
                l.saldoMonetarioMep,
                l.origenFondos,
                l.usuario,
                l.moneda,
                l.observaciones,
                l.saldoAcdi,
                l.saldoColocadorSimple,
                eo.estado,
                l.estado_id,
                c.fechahora as cierre
         from   fondo l
         join   estadoorden eo 
         on     l.estado_id = eo.id
         join   cierrefondo c
         on     l.cierrefondo_id = c.id
         join   usuario u
         on     l.usuario_id = u.id
         where  l.estado_id <> 1
         and    l.cierrefondo_id = ?
         order by l.fechaConcertacion desc)"; 
        
        $resultado = R::getAll($sql, array($this->cierrefondo_id));
        
        return $resultado;
    }
    
    public function previewMercado(){
        $ordenes_in = implode(',', $this->ordenes);
        $sql = "SELECT  cierrefondo_id,
                        tramo,
                        moneda, 
                        tipopersona,
                        plazo, 
                        numcomitente,
                        cantidad,
                        precio,
                        cuit,
                        comision
                        FROM    fondo
                WHERE   id in ({$ordenes_in})
                ORDER BY cierrefondo_id, moneda ";
        $resultado = R::getAll($sql);
        $colocacionAnterior = 0;
        $contenidoInd = 0;

        foreach ($resultado as $indice=>$fila){
            
            $cierre = R::load('cierrefondo', $fila['cierrefondo_id']);
            
            
            /*
             * 
             *   Esto es para LECAPS  
             * 
            if ($fila['plazo'] == '213'){
                $vencimiento = '31/05/2019';
            } else {
                $vencimiento = '30/04/2020';
            }
             * 
             */
            
            
            
            switch ($fila['moneda']){
                /*
                case '$':
                    if ($fila['tramo'] == 'Competitiva') {
                        switch ($fila['plazo']){
                            case 111:
                                $titulo = utf8_decode("Lelink Vto 04/09/2019  - Int Pesos - Tramo Competitivo");
                                break;
                            case 140:
                                $titulo = utf8_decode("Lelink Vto 03/10/2019  - Int Pesos - Tramo Competitivo");
                                break;
                            case 173:
                                $titulo = utf8_decode("Lelink Vto 05/11/2019  - Int Pesos - Tramo Competitivo");
                                break;
                            case 202:
                                $titulo = utf8_decode("Lelink Vto 04/12/2019  - Int Pesos - Tramo Competitivo");
                                break;
                        }
                        $precio = $fila['precio'];
                    } else {
                        switch ($fila['plazo']){
                            case 111:
                                $titulo = utf8_decode("Lelink Vto 04/09/2019  - Int Pesos - Tramo NO Competitivo");
                                break;
                            case 140:
                                $titulo = utf8_decode("Lelink Vto 03/10/2019  - Int Pesos - Tramo NO Competitivo");
                                break;
                            case 173:
                                $titulo = utf8_decode("Lelink Vto 05/11/2019  - Int Pesos - Tramo NO Competitivo");
                                break;
                            case 202:
                                $titulo = utf8_decode("Lelink Vto 04/12/2019  - Int Pesos - Tramo NO Competitivo");
                                break;
                        }
                        $precio = '';
                    }
                    $colocacion = $cierre['colocacionPesos'];
                    break;
                */
                /*    
                case 'LN':
                    if ($fila['tramo'] == 'Competitiva') {
                        $titulo = utf8_decode("LECAP en Pesos Vto {$vencimiento}  - INT LEBAC NOVIEMBRE - Tramo Competitivo");
                        $precio = $fila['precio'];
                    } else {
                        $titulo = utf8_decode("LECAP en Pesos Vto {$vencimiento}  - INT LEBAC NOVIEMBRE - Tramo NO Competitivo");
                        $precio = '';
                    }
                    $colocacion = $cierre['colocacionLebacsNov'];                    
                    break;
                case 'LD':
                    if ($fila['tramo'] == 'Competitiva') {
                        $titulo = utf8_decode("LECAP en Pesos Vto {$vencimiento}  - INT LEBAC DICIEMBRE - Tramo Competitivo");
                        $precio = $fila['precio'];
                    } else {
                        $titulo = utf8_decode("LECAP en Pesos Vto {$vencimiento}  - INT LEBAC DICIEMBRE - Tramo NO Competitivo");
                        $precio = '';
                    }
                    $colocacion = $cierre['colocacionLebacsDic'];                    
                    break;
                  
                  
                  
                case 'A2J9':
                    $titulo = utf8_decode("Lelink - Int Bono Dual 2019 -");
                    $precio = '';
                    $colocacion = $cierre['colocacionA2J9'];
                    break;
                 * 
                 */
                
                case 'u$s':
                    if ($fila['tramo'] == 'Competitiva') {
                        switch ($fila['plazo']){
                            case 63:
                                $titulo = utf8_decode("Letras 63 dias Vto. 26/07/2019 Tramo Competitivo");
                                break;
                            case 210:
                                $titulo = utf8_decode("Letras 210 dias Vto. 20/12/2019 Tramo Competitivo");
                                break;
                            case 173:
                                $titulo = utf8_decode("Lelink Vto 05/11/2019  - Int Dolar - Tramo Competitivo");
                                break;
                            case 202:
                                $titulo = utf8_decode("Lelink Vto 04/12/2019  - Int Dolar - Tramo Competitivo");
                                break;
                        }
                        $precio = $fila['precio'];
                    } else {
                        switch ($fila['plazo']){
                            case 63:
                                $titulo = utf8_decode("Letras 63 dias Vto. 26/07/2019 Tramo NO Competitivo");
                                break;
                            case 210:
                                $titulo = utf8_decode("Letras 210 dias Vto. 20/12/2019 Tramo NO Competitivo");
                                break;
                            case 173:
                                $titulo = utf8_decode("Lelink Vto 05/11/2019  - Int Dolar - Tramo NO Competitivo");
                                break;
                            case 202:
                                $titulo = utf8_decode("Lelink Vto 04/12/2019  - Int Dolar - Tramo NO Competitivo");
                                break;
                        }
                        $precio = '';
                    }
                    $colocacion = $cierre['colocacionDolares'];
                    
                    break;
            }
            /*
            switch ($fila['moneda']){
                case '$':
                    if ($fila['tramo'] == 'Competitiva') {
                        $titulo = utf8_decode("Letras 196 dias Vto. 28/06/2019 Tramo Competitivo - Integración Pesos");
                        $precio = $fila['precio'];
                    } else {
                        $titulo = utf8_decode("Letras 196 dias Vto. 28/06/2019 Tramo NO Competitivo - Integración Pesos");
                        $precio = '';
                    }
                    $colocacion = $cierre['colocacionPesos'];
                    break;
                case 'u$s':
            // 2019/02/18 Se comenta.
//                    if ($fila['tramo'] == 'Competitiva') {
//                        $titulo = utf8_decode("fondo a 217 dias  Vto 13/09/2019  Tramo Competitivo");
//                        $precio = $fila['precio'];
//                    } else {
//                        $titulo = utf8_decode("fondo a 217 dias  Vto 13/09/2019 Tramo NO Competitivo");
//                        $precio = '';
//                    }
//                    $colocacion = $cierre['colocacionDolares'];
            // 2019/02/18
            if ($fila['tramo'] == 'Competitiva') {
                        $titulo = utf8_decode("Letras 70 dias Vto. 19/07/2019 Tramo Competitivo");
                        $precio = $fila['precio'];
                    } else {
                        $titulo = utf8_decode("Letras 70 dias Vto. 19/07/2019 Tramo NO Competitivo");
                        $precio = '';
                    }
             * 
            $colocacion = $cierre['colocacionDolares'];    
             * 
             */
            
            if ($fila['tipopersona'] == 'FISICA'){
                $tipoPersona = 'Persona Fisica';
            } else {
                $tipoPersona = 'Persona Juridica';
            }
                    
                    
                    
                    
            /*
                    break;
            }
            */
                
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

            $contenido[$contenidoInd]['datos'] .= $colocacion . "\t" . $titulo . "\t" . $precio . "\t" . $fila['cantidad'] . "\t\t" . $fila['numcomitente'] . "\t\t" . $fila['cuit'] . "\t\t\t\t" . $tipoPersona .  "\t\t\tCUIT\r\n";
                
        }
        
        $this->load->library('zip');
        foreach ($contenido as $data){
            //$archivo = FCPATH . 'generadas/' . date('Y-m-d-H-i-s') . '-' . $data['colocacion'] . '-lebacs.dat';
            $archivo = date('Y-m-d-H-i-s') . '-' . $data['colocacion'] . '-fondo.txt';
            $this->zip->add_data($archivo, $data['datos']);
        }
        $nombreZip = FCPATH . 'generadas/' . date('Y-m-d-H-i-s') . '-fondo.zip';
        $this->zip->archive($nombreZip);
        
        return array('uris'=>array(base_url() . 'generadas/' . basename($nombreZip)));
        
    }
    
    public function enviarMercado(){
        $estado = R::load('estadoorden', 3);
        $resultado = array('exito'=>1, 'resultado'=>'Ordenes Enviadas Correctamente');
        foreach ($this->ordenes as $id) {
            $orden = R::load('fondo', $id);
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
                            from   fondo
                            where  cierrefondo_id = ?
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
                            from    fondo
                            where   usuario_id = ?
                            and     cierrefondo_id = ?
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

class Model_Fondo extends RedBean_SimpleModel {
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
            $auditoria->table = 'fondo';
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
        $auditoria->table = 'fondo';
        $auditoria->tableId = $this->prev['id'];
        $auditoria->anterior = json_encode($this->prev);
        $auditoria->actual = json_encode(array('operacion'=>'Registro Borrado'));
        R::store($auditoria);        
    }    
}

class Model_CierreFondo extends RedBean_SimpleModel {
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
            $auditoria->table = 'cierrefondo';
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
        $auditoria->table = 'cierrefondo';
        $auditoria->tableId = $this->prev['id'];
        $auditoria->anterior = json_encode($this->prev);
        $auditoria->actual = json_encode(array('operacion'=>'Registro Borrado'));
        R::store($auditoria);        
    }
}
