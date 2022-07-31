<?php
class Parking extends MY_AuthController {
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('parking/grilla');
        $this->load->view('parking/pie');
        $this->load->view('template/pie');
    }
    
    public function editar(){
        $datos['id'] = $this->input->post('id');
        $datos['origen'] = $this->session->userdata['usuario']['nombre'] . " " . $this->session->userdata['usuario']['apellido'];
        $usuarioNombre = $this->session->userdata['usuario']['nombre'] . " " . $this->session->userdata['usuario']['apellido'];  
        $datos['usuario'] = $usuarioNombre;

        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('parking/editar', $datos);
        $this->load->view('template/pie');
    }
    
    public function aprobar(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('parking/aprobar');
        $this->load->view('template/pie');
    }
    
    public function control(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('parking/control');
        $this->load->view('template/pie');
    }
    
    
    public function saveOrden(){
        $this->load->model('Parking_model');
        $this->Parking_model->id = $this->input->post('id');
        $this->Parking_model->operador = $this->input->post('operador');
//        $this->Parking_model->tipoOperacion = $this->input->post('tipoOperacion');
//        $this->Parking_model->precioComitente = $this->input->post('precioComitente');
//        $this->Parking_model->precioCartera = $this->input->post('precioCartera');
        $this->Parking_model->numComitente = $this->input->post('numComitente');
        $this->Parking_model->descripcionComitente = $this->input->post('descripcionComitente');
        $this->Parking_model->especie = $this->input->post('especie');
        $this->Parking_model->especieDescripcion = $this->input->post('especieDescripcion');
//        $this->Parking_model->arancel = $this->input->post('arancel');
//        $this->Parking_model->plazo = $this->input->post('plazo');
        $this->Parking_model->parking = $this->input->post('parking');
        $this->Parking_model->cantidad = $this->input->post('cantidad');
        $this->Parking_model->codigo = $this->input->post('codigo');
//        $this->Parking_model->brutoCliente = $this->input->post('brutoCliente');
        $this->Parking_model->observaciones = $this->input->post('observaciones');
        $this->Parking_model->moneda = $this->input->post('moneda');
        $this->Parking_model->esMismaMoneda = $this->input->post('esMismaMoneda');
        $this->Parking_model->esCableMep = $this->input->post('esCableMep');
        $orden = $this->Parking_model->saveOrden();
        echo json_encode($orden);
    }
    
    
    public function saveParkingEsco(){
        $this->load->model('Parking_model');
        $this->Parking_model->orden = $this->input->post('orden');
        $this->Parking_model->parking = $this->input->post('parking');                
        $this->Parking_model->numComitente = $this->input->post('numComitente');                
        $this->Parking_model->especie = $this->input->post('especie');                
        $orden = $this->Parking_model->saveParkingEsco();
        echo json_encode($orden);
    }
    
    
    
    
    
    
    public function getOrdenesParking(){        
        $cierre_id = $this->input->post('cierre_id');
        $numComitente = $this->input->post('numComitente');
        $this->load->model('Parking_model');
        $this->Parking_model->cierre_id = $cierre_id;
        $this->Parking_model->numComitente = $numComitente;
        $resultado = $this->Parking_model->getOrdenesParking();
        
        echo json_encode($resultado);
    }
    
    public function getOrden(){
        $this->load->model('Parking_model');
        $this->Parking_model->id = $this->input->post('id');
        $orden = $this->Parking_model->getOrden();
        echo json_encode($orden);
    }
    
    public function delOrden(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Parking_model');
        $this->Parking_model->ordenes = $ordenes;
        $this->Parking_model->delOrden();
        echo json_encode(array('resultado'=>'Ordenes borradas exitosamente'));
    }
    
    public function grilla(){
        
        
        $usuario = $this->session->userdata('usuario');
        $usuario_id = $usuario['id'];
        
        $cierreparking_id = $this->input->post('cierreparking_id');
                
        $this->load->model('Parking_model');
        $this->Parking_model->usuario_id = $usuario_id;
        $this->Parking_model->cierreparking_id = $cierreparking_id;
        $resultado = $this->Parking_model->grilla();
        
        echo json_encode($resultado);

        
    }
    
    public function enviarOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Parking_model');
        $this->Parking_model->ordenes = $ordenes;
        $resultado = $this->Parking_model->enviarOrdenes();
        echo json_encode($resultado);
    }
    
    public function anularOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Parking_model');
        $this->Parking_model->ordenes = $ordenes;
        $resultado = $this->Parking_model->anularOrdenes();
        echo json_encode($resultado);        
    }
    
    public function aprobarOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $usuario = $this->session->userdata['usuario']['id'];  

        $this->load->model('Parking_model');
        $this->Parking_model->ordenes = $ordenes;
        $this->Parking_model->usuario = $usuario;
        
        $resultado = $this->Parking_model->aprobarOrdenes();
        echo json_encode($resultado);
    }    
    
    public function aprobarOrdenesOMS(){
        $ordenes = $this->input->post('ordenes');
        $usuario = $this->session->userdata['usuario']['id'];  

        $this->load->model('Parking_model');
        $this->Parking_model->ordenes = $ordenes;
        $this->Parking_model->usuario = $usuario;
        
        $resultado = $this->Parking_model->aprobarOrdenesOMS();
        echo json_encode($resultado);
    }
    
    
    public function comprobarOMS(){
        
        
        
        $cierre = $this->input->post('cierre');
//        $usuario = $this->session->userdata['usuario']['id'];  

        $this->load->model('Parking_model');
        $this->Parking_model->cierre = $cierre;
//        $this->Parking_model->usuario = $usuario;
        
        $resultado = $this->Parking_model->comprobarOMS();
        echo json_encode($resultado);
    }
    
    
    public function procesarOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $usuario = $this->session->userdata['usuario']['id'];  

        $this->load->model('Parking_model');
        $this->Parking_model->ordenes = $ordenes;
        $this->Parking_model->usuario = $usuario;
        
        $resultado = $this->Parking_model->procesarOrdenes();
        echo json_encode($resultado);     
    }
    
    public function cierreEditar(){
        $data['id'] = $this->input->post('id');
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('parking/cierreEditar', $data);
        $this->load->view('template/pie');
    }
    
    public function getCierre(){
        $cierreparking_id = $this->input->post('cierreparking_id');
        $this->load->model('Parking_model');
        $this->Parking_model->cierreparking_id = $cierreparking_id;
        $resultado = $this->Parking_model->getCierre();
        echo json_encode($resultado);
    }
    
    public function saveCierre(){
        $cierreparking_id = $this->input->post('cierreparking_id');
        $fechaHora = $this->input->post('fechahora');
        $plazos = $this->input->post('plazos');
        $minimos = $this->input->post('minimos');
        $this->load->model('Parking_model');
        $this->Parking_model->cierreparking_id = $cierreparking_id;
        $this->Parking_model->fechahora = $fechaHora;
        $this->Parking_model->plazos = $plazos;
        $this->Parking_model->minimos = $minimos;
//        $this->Parking_model->colocacionDolares = $this->input->post('colocacionDolares');
        /*
        $this->Parking_model->colocacionLebacsNov = $this->input->post('colocacionLebacsNov');
        $this->Parking_model->colocacionLebacsDic = $this->input->post('colocacionLebacsDic');

        $this->Parking_model->colocacionPesos = $this->input->post('colocacionPesos');
        $this->Parking_model->colocacionA2J9 = $this->input->post('colocacionA2J9');
         * 
         */
        
        $cierre = $this->Parking_model->saveCierre();
        echo json_encode($cierre);
    }
    
    public function delCierre(){
        $cierreparking_id = $this->input->post('id');
        $this->load->model('Parking_model');
        $this->Parking_model->cierreparking_id = $cierreparking_id;
        $this->Parking_model->delCierre();
        echo json_encode(array('resultado'=>'Cierre borrado exitosamente'));
    }
    
    public function cierreGrilla(){
        
        $filtervalue = array();
        $filtercondition = array();
        $filterdatafield = array();
        $filteroperator = array();
        $pagenum = $this->input->get('pagenum');
        $pagesize = $this->input->get('pagesize');
        if ($pagenum == ''){
            $pagenum = 0;
        }
        if ($pagesize == ''){
            $pagesize = 20;
        }
        if ($this->input->get('filterscount')){
            $filterscount = $this->input->get('filterscount');
            if ($filterscount > 0){
                for ($i=0;$i<$filterscount;$i++){
                    $filtervalue[] = $_GET['filtervalue'.$i];
                    $filtercondition[] = $_GET['filtercondition'.$i];
                    $filterdatafield[] = $_GET['filterdatafield'.$i];
                    $filteroperator[] = $_GET['filteroperator'.$i];
                }
            }
        } else {
            $filterscount = 0;
        }
        $sortdatafield = $this->input->get('sortdatafield');
        $sortorder = $this->input->get('sortorder');
        if (!$sortdatafield){
            $sortdatafield = 'fechahora';
            $sortorder = 'desc';
        }
        $this->load->model('grilla_model');
        $table = "cierreparking";
        $fields = array('id','fechahora','plazos');
        $datos = $this->grilla_model->datosGrilla($table, $fields, $pagenum, $pagesize, 
                $filterscount, $filtervalue, $filtercondition, $filterdatafield, 
                $filteroperator, $sortdatafield, $sortorder);
        echo json_encode($datos);
    }

    
    public function aprobarGrilla(){
        $cierreparking_id = $this->input->post('cierreparking_id');
        $this->load->model('Parking_model');
        $this->Parking_model->cierreparking_id = $cierreparking_id;
        $resultado = $this->Parking_model->aprobarGrilla();
        
        echo json_encode($resultado);
    }
    
    public function cierre(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('parking/cierreGrilla');
        $this->load->view('template/pie');
    }
    
    public function getCierreActual(){
        $this->load->model('Parking_model');
        $cierreActual = $this->Parking_model->getCierreActual();
        echo json_encode($cierreActual);
    }

    public function procesar(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('parking/procesar');
        $this->load->view('template/pie');
    }
    
    public function controlGrilla(){
        $cierreparking_id = $this->input->post('cierreparking_id');
        $this->load->model('Parking_model');
        $this->Parking_model->cierreparking_id = $cierreparking_id;
        $resultado = $this->Parking_model->controlGrilla();
        
        echo json_encode($resultado);
    }
    
    public function procesarGrilla(){
        
        $cierreparking_id = $this->input->post('cierreparking_id');
        
        $this->load->model('Parking_model');
        $this->Parking_model->cierreparking_id = $cierreparking_id;
        $resultado = $this->Parking_model->procesarGrilla();
        
        echo json_encode($resultado);
    }

    public function controlarOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $usuario = $this->session->userdata['usuario']['id'];  

        $this->load->model('Parking_model');
        $this->Parking_model->ordenes = $ordenes;
        $this->Parking_model->usuario = $usuario;
        
        $resultado = $this->Parking_model->controlarOrdenes();
        echo json_encode($resultado);     
    }
    
    public function procesarQuinto(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('parking/procesarQuinto');
        $this->load->view('template/pie');
    }

    public function procesarQuintoGrilla(){
        
        $cierreparking_id = $this->input->post('cierreparking_id');
        
        $this->load->model('Parking_model');
        $this->Parking_model->cierreparking_id = $cierreparking_id;
        $resultado = $this->Parking_model->procesarQuintoGrilla();
        
        echo json_encode($resultado);
    }
    
    public function getCierres(){
        $this->load->model('Parking_model');
        $cierres = $this->Parking_model->getCierres();
        echo json_encode($cierres);
    }
    
    public function getMonedas(){
        $this->load->model('Parking_model');
        $monedas = $this->Parking_model->getMonedas();
        echo json_encode($monedas);
    }
    
    public function grillaResumen(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Parking_model');
        $this->Parking_model->ordenes = $ordenes;
        $resultado = $this->Parking_model->grillaResumen();
        echo json_encode($resultado);
    }
    
    
    public function previewMercado(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Parking_model');
        $this->Parking_model->ordenes = $ordenes;
        $resultado = $this->Parking_model->previewMercado();
        
        echo json_encode($resultado);
    }

    public function enviarMercado(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Parking_model');
        $this->Parking_model->ordenes = $ordenes;
        $resultado = $this->Parking_model->enviarMercado();
        echo json_encode($resultado);
    }
    
    
    //Esto entra en desuso porque el Parking se calcula al enviar la orden, no al generarla
    public function getParkingEsco(){
                
        $numComitente = $this->input->post('numComitente');
        $especie = $this->input->post('especie');
        
        
               
///////////////////Calcular dás hábiles, quitar findes y feriados //////////////
        
        $this->load->model('Parking_model');
        $feriados = $this->Parking_model->getLosFeriados();
        
        $fechaHoy = (string) date('Y-m-d');
//        $fechaHoy = '2020-05-28';

        $losDias = array();

        $hace1dias = date('Y-m-d', strtotime($fechaHoy . ' -1 day'));
        $diaSemana1 = date("w", strtotime($hace1dias));
        if($diaSemana1 == 0){ //Si ayer fué domingo -3
            $hace1dias = date('Y-m-d', strtotime($fechaHoy . ' -3 days'));
        }else if($diaSemana1 == 6){ //Si ayer fué sabado -2
            $hace1dias = date('Y-m-d', strtotime($fechaHoy . ' -2 days'));
        }
        
        $hace2dias = date('Y-m-d', strtotime($hace1dias . ' -1 day'));
        $diaSemana2 = date("w", strtotime($hace2dias));
        if($diaSemana2 == 0){ //Si fué domingo -3
            $hace2dias = date('Y-m-d', strtotime($hace1dias . ' -3 days'));
        }else if($diaSemana2 == 6){ //Si fué sabado -2
            $hace2dias = date('Y-m-d', strtotime($hace1dias . ' -2 days'));
        }
        
        $hace3dias = date('Y-m-d', strtotime($hace2dias . ' -1 day'));
        $diaSemana3 = date("w", strtotime($hace3dias));
        if($diaSemana3 == 0){ //Si fué domingo -3
            $hace3dias = date('Y-m-d', strtotime($hace2dias . ' -3 days'));
        }else if($diaSemana3 == 6){ //Si fué sabado -2
            $hace3dias = date('Y-m-d', strtotime($hace2dias . ' -2 days'));
        }
        
        $hace4dias = date('Y-m-d', strtotime($hace3dias . ' -1 day'));
        $diaSemana4 = date("w", strtotime($hace4dias));        
        if($diaSemana4 == 0){ //Si fué domingo -3
            $hace4dias = date('Y-m-d', strtotime($hace3dias . ' -3 days'));
        }else if($diaSemana4 == 6){ //Si fué sabado -2
            $hace4dias = date('Y-m-d', strtotime($hace3dias . ' -2 days'));
        }
        
        $hace5dias = date('Y-m-d', strtotime($hace4dias . ' -1 day'));
        $diaSemana5 = date("w", strtotime($hace5dias));
        if($diaSemana5 == 0){ //Si fué domingo -3
            $hace5dias = date('Y-m-d', strtotime($hace4dias . ' -3 days'));
        }else if($diaSemana5 == 6){ //Si fué sabado -2
            $hace5dias = date('Y-m-d', strtotime($hace4dias . ' -2 days'));
        }
        
        array_push($losDias, $hace1dias, $hace2dias, $hace3dias, $hace4dias, $hace5dias);        

//        $n = date("n", strtotime($hace5dias)); // mes en formato 1 - 12
//        $j = date("j", strtotime($hace5dias)); // dia en formato 1 - 31
//        $y = date("Y", strtotime($hace5dias)); // año en formato XXXX   
        
        $contador = 0;
        
        //Esto resulta en fechaDesde, que es 5 días hábiles para atrás
        
        $fechaDesde = $hace5dias;
        
        foreach($feriados as $key => $value){
            if( in_array($value['feriado'], $losDias)){
                
                $fechaDesde = date('Y-m-d', strtotime($fechaDesde . ' -1 day'));
                
                $esSabado = date("w", strtotime($fechaDesde));
                if($esSabado == 0){ //Si fué domingo -3
                    $fechaDesde = date('Y-m-d', strtotime($fechaDesde . ' -1 day'));
                }
                
                $esDomingo = date("w", strtotime($fechaDesde));
                if($esDomingo == 6){ //Si fué domingo -3
                    $fechaDesde = date('Y-m-d', strtotime($fechaDesde . ' -1 day'));
                }
                
                $contador++;
            }
        }
                
        
///////////////////////// Fin Días Hábiles /////////////////////////////////////        
        
        $this->load->model('Esco_model');
        $this->Esco_model->fechaDesde = $fechaDesde;
        $this->Esco_model->numComitente = $numComitente;
        $this->Esco_model->especie = $especie;
        $asignaciones = $this->Esco_model->getParkingComitenteInstrumento();
        
        
//        echo chr(10);
//        print_r($asignaciones);
//        echo chr(10);
        
        foreach ($asignaciones as $k => $v){
            
            $futurosDias = array();
            $contador = 0;
            $restar = '';
            
            $suma1dias = 0;
            $suma2dias = 0;
            $suma3dias = 0;
            $suma4dias = 0;
            $suma5dias = 0;
            
            $diaSemana1 = 0;
            $diaSemana2 = 0;
            $diaSemana3 = 0;
            $diaSemana4 = 0;
            $diaSemana5 = 0;
            
            if($k == 0 && $v['FechaLiquidacion'] == ''){
                $cantidad1 = $v['CantidadVN'];
                unset($asignaciones[$k]);
            }elseif($k == 1 && $v['FechaLiquidacion'] == ''){
                $cantidad2 = $v['CantidadVN'];
                unset($asignaciones[$k]);
            }else{
                
                if($v['EsDisponible'] == 0){
                    
                    //Si es un debito o un credito su fecha queda igual y si es mayor a hoy se quita
                    if($v['TpTransaccion'] == "Débito" || $v['TpTransaccion'] == "Crédito"){
                        if( date('Y-m-d', strtotime($v['FechaLiquidacion']))  > $fechaHoy ){
                            unset($asignaciones[$k]);
                        }
                    }
                    //Si es una compra se suman 5 días hábiles y si es mayor a hoy se quita
                    //Pero si es una compra prestamo su fecha queda igual (y si es mayor a hoy se quita)
                    if($v['TpTransaccion'] == "Compra"){
                        
                        
                        //if(es pretamo CC)
                        //DescripcionCtaCte
                        $descripcionCtaCte = $v['DescripcionCtaCte'];
                        $prestamo   = 'PRESVALCC';
                        $esPrestamo = strpos($descripcionCtaCte, $prestamo);
                        
                        if($esPrestamo == true){
                            //print_r("Este es un prestamo: ". $v['FechaLiquidacion']);
                            if( date('Y-m-d', strtotime($v['FechaLiquidacion']))  > $fechaHoy ){
                                unset($asignaciones[$k]);
                            }
                        }else{
                        
//////////////////////////////////////////////////////////////////////////////// 
//                           
                            $suma1dias = $v['FechaLiquidacion'];

                            //Hago un bucle para que cuente cinco días hábiles
                            for ($i = 1; $i < 6; $i++) {                       
                                //Suma un día
                                $suma1dias = date('Y-m-d', strtotime($suma1dias . ' +1 day'));
                                //Mientras ese día no sea habil no lo cuento
                                $cuento = 0;
                                $valid = false;
                                while ($valid == false){
                                    $esSabado = false;
                                    $diaSemana1 = date("w", strtotime($suma1dias));
                                    if($diaSemana1 == '6'){ //Si fué sábado -3
                                        $esSabado = true;
                                    }else{
                                        $esSabado = false;
                                    }
                                    $esDomingo = false;
                                    if($diaSemana1 == '0'){ //Si fué domingo -3
                                      $esDomingo = true;
                                    }else{
                                        $esDomingo = false;
                                    }
                                    $esFeriado = false;
                                    foreach($feriados as $key => $value){
                                        if($value['feriado'] == $suma1dias){
                                            $esFeriado = true;
                                        }
                                    }                                    
                                    
                                    if($esFeriado == true || $esSabado == true || $esDomingo == true){
                                        $suma1dias = date('Y-m-d', strtotime($suma1dias . ' +1 day'));
                                        $cuento = 0;
                                    }else{
                                        $valid = true;
                                        $cuento = 1;
                                    }
                                    
                                    if($valid){
                                        break;
                                    }
                                }                                
                            }
//                            print_r("Así queda el día final: ");
//                            print_r($suma1dias);
////////////////////////////////////////////////////////////////////////////////
                            
                            $FechaLiquidacion = $suma1dias;
                            
                            $asignaciones[$k]['FechaLiquidacion'] = $FechaLiquidacion;

                            if($FechaLiquidacion > $fechaHoy){
                                unset($asignaciones[$k]);
                            }
                        
                        }
                    }
                    
                    // En las ventas si la fecha supera a la actual, se le dá la fecha Actual
                    if($v['TpTransaccion'] == "Venta"){
                        
                        if( date('Y-m-d', strtotime($v['FechaLiquidacion'])) > $fechaHoy ){
                            $asignaciones[$k]['FechaLiquidacion'] = $fechaHoy;
                        }
                    }
                    
                                        
                    
                    
                    
                    
                }else{
                    //Si es un debito o un credito su fecha queda igual y si es mayor a hoy se quita
                    if($v['TpTransaccion'] == "Débito" || $v['TpTransaccion'] == "Crédito"){
                        if( date('Y-m-d', strtotime($v['FechaLiquidacion']))  > $fechaHoy ){
                            unset($asignaciones[$k]);
                        }
                    }
                    //Si es una compra se suman 5 días hábiles y si es mayor a hoy se quita
                    //Pero si es una compra prestamo su fecha queda igual (y si es mayor a hoy se quita)
                    if($v['TpTransaccion'] == "Compra"){
                        
                        
                        //if(es pretamo CC)
                        //DescripcionCtaCte
                        $descripcionCtaCte = $v['DescripcionCtaCte'];
                        $prestamo   = 'PRESVALCC';
                        $esPrestamo = strpos($descripcionCtaCte, $prestamo);
                        
                        if($esPrestamo == true){
                            //print_r("Este es un prestamo: ". $v['FechaLiquidacion']);
                            if( date('Y-m-d', strtotime($v['FechaLiquidacion']))  > $fechaHoy ){
                                unset($asignaciones[$k]);
                            }
                        }else{
                        
                            
//////////////////////////////////////////////////////////////////////////////// 
//                           
                            $suma1dias = $v['FechaLiquidacion'];

                            //Hago un bucle para que cuente cinco días hábiles
                            for ($i = 1; $i < 6; $i++) {                       
                                //Suma un día
                                $suma1dias = date('Y-m-d', strtotime($suma1dias . ' +1 day'));
                                //Mientras ese día no sea habil no lo cuento
                                $cuento = 0;
                                $valid = false;
                                while ($valid == false){
                                    $esSabado = false;
                                    $diaSemana1 = date("w", strtotime($suma1dias));
                                    if($diaSemana1 == '6'){ //Si fué sábado -3
                                        $esSabado = true;
                                    }else{
                                        $esSabado = false;
                                    }
                                    $esDomingo = false;
                                    if($diaSemana1 == '0'){ //Si fué domingo -3
                                      $esDomingo = true;
                                    }else{
                                        $esDomingo = false;
                                    }
                                    $esFeriado = false;
                                    foreach($feriados as $key => $value){
                                        if($value['feriado'] == $suma1dias){
                                            $esFeriado = true;
                                        }
                                    }                                    
                                    
                                    if($esFeriado == true || $esSabado == true || $esDomingo == true){
                                        $suma1dias = date('Y-m-d', strtotime($suma1dias . ' +1 day'));
                                        $cuento = 0;
                                    }else{
                                        $valid = true;
                                        $cuento = 1;
                                    }
                                    
                                    if($valid){
                                        break;
                                    }
                                }                                
                            }
//                            print_r("Así queda el día final: ");
//                            print_r($suma1dias);
////////////////////////////////////////////////////////////////////////////////
                            
                            $FechaLiquidacion = $suma1dias;
                            
                            $asignaciones[$k]['FechaLiquidacion'] = $FechaLiquidacion;

                            if($FechaLiquidacion > $fechaHoy){
                                unset($asignaciones[$k]);
                            }
                        
                        }
                    }
                    
                    // En las ventas si la fecha supera a la actual, se le dá la fecha Actual
                    if($v['TpTransaccion'] == "Venta"){
                        
                        if( date('Y-m-d', strtotime($v['FechaLiquidacion'])) > $fechaHoy ){
                            $asignaciones[$k]['FechaLiquidacion'] = $fechaHoy;
                        }
                    }
                }
            }
        }
        
//        echo chr(10);
//        print_r($asignaciones);
//        echo chr(10);        
        
        
        $suma = 0;
        foreach ($asignaciones as $k => $v){
            $suma = (float)$suma + (float)$v['CantidadVN'];
        }
        
//        echo chr(10);
//        print_r($suma);
//        echo chr(10);
        
//        die;
//        
        
//        
//        echo chr(10);
//        print_r($cantidad2);
//        echo chr(10);
        
        if(!(isset($cantidad1))){
           $cantidad1 = 0;
        }
        
        
        if(isset($cantidad2)){
            $suma2 = $cantidad1 + $cantidad2 + $suma;
        }else{
            $suma2 = $cantidad1 + $suma;
        }
        
//        echo chr(10);
//        print_r($suma);
//        echo chr(10);
//        echo chr(10);
//        print_r($suma2);
//        echo chr(10);
//
//        die;
        
//        $this->load->model('ControlAceptacionDDJJ_model');
//        $this->ControlAceptacionDDJJ_model->asignaciones = $asignaciones;
//        $result = $this->ControlAceptacionDDJJ_model->importarAsignacionesEsco();
        
//        echo json_encode($suma2);
        echo json_encode(array('resultado'=>$suma2));

    }
    
    
    
    
    
    
    
    
    public function obtenerParkingEsco(){
                
        $ordenId = $this->input->post('orden');
            
        $this->load->model('Parking_model');
        $this->Parking_model->id = $ordenId;
        $orden = $this->Parking_model->getOrden();        
        
        $numComitente = $orden['numComitente'];
        $especie = $orden['especie'];
               
///////////////////Calcular dás hábiles, quitar findes y feriados //////////////
        
        $this->load->model('Parking_model');
        $feriados = $this->Parking_model->getLosFeriados();
        
        $fechaHoy = (string) date('Y-m-d');
        $losDias = array();

        $hace1dias = date('Y-m-d', strtotime($fechaHoy . ' -1 day'));
        $diaSemana1 = date("w", strtotime($hace1dias));
        if($diaSemana1 == 0){ //Si ayer fué domingo -3
            $hace1dias = date('Y-m-d', strtotime($fechaHoy . ' -3 days'));
        }else if($diaSemana1 == 6){ //Si ayer fué sabado -2
            $hace1dias = date('Y-m-d', strtotime($fechaHoy . ' -2 days'));
        }
        
        $hace2dias = date('Y-m-d', strtotime($hace1dias . ' -1 day'));
        $diaSemana2 = date("w", strtotime($hace2dias));
        if($diaSemana2 == 0){ //Si fué domingo -3
            $hace2dias = date('Y-m-d', strtotime($hace1dias . ' -3 days'));
        }else if($diaSemana2 == 6){ //Si fué sabado -2
            $hace2dias = date('Y-m-d', strtotime($hace1dias . ' -2 days'));
        }
        
        $hace3dias = date('Y-m-d', strtotime($hace2dias . ' -1 day'));
        $diaSemana3 = date("w", strtotime($hace3dias));
        if($diaSemana3 == 0){ //Si fué domingo -3
            $hace3dias = date('Y-m-d', strtotime($hace2dias . ' -3 days'));
        }else if($diaSemana3 == 6){ //Si fué sabado -2
            $hace3dias = date('Y-m-d', strtotime($hace2dias . ' -2 days'));
        }
        
        $hace4dias = date('Y-m-d', strtotime($hace3dias . ' -1 day'));
        $diaSemana4 = date("w", strtotime($hace4dias));        
        if($diaSemana4 == 0){ //Si fué domingo -3
            $hace4dias = date('Y-m-d', strtotime($hace3dias . ' -3 days'));
        }else if($diaSemana4 == 6){ //Si fué sabado -2
            $hace4dias = date('Y-m-d', strtotime($hace3dias . ' -2 days'));
        }
        
        $hace5dias = date('Y-m-d', strtotime($hace4dias . ' -1 day'));
        $diaSemana5 = date("w", strtotime($hace5dias));
        if($diaSemana5 == 0){ //Si fué domingo -3
            $hace5dias = date('Y-m-d', strtotime($hace4dias . ' -3 days'));
        }else if($diaSemana5 == 6){ //Si fué sabado -2
            $hace5dias = date('Y-m-d', strtotime($hace4dias . ' -2 days'));
        }
        
        array_push($losDias, $hace1dias, $hace2dias, $hace3dias, $hace4dias, $hace5dias);        

        $contador = 0;
        
        //Esto resulta en fechaDesde, que es 5 días hábiles para atrás        
        $fechaDesde = $hace5dias;
        
        foreach($feriados as $key => $value){
            if( in_array($value['feriado'], $losDias)){
                
                $fechaDesde = date('Y-m-d', strtotime($fechaDesde . ' -1 day'));
                
                $esSabado = date("w", strtotime($fechaDesde));
                if($esSabado == 0){ //Si fué domingo -3
                    $fechaDesde = date('Y-m-d', strtotime($fechaDesde . ' -1 day'));
                }
                
                $esDomingo = date("w", strtotime($fechaDesde));
                if($esDomingo == 6){ //Si fué domingo -3
                    $fechaDesde = date('Y-m-d', strtotime($fechaDesde . ' -1 day'));
                }
                
                $contador++;
            }
        }
        
///////////////////////// Fin Días Hábiles /////////////////////////////////////        
        
        $this->load->model('Esco_model');
        $this->Esco_model->fechaDesde = $fechaDesde;
        $this->Esco_model->numComitente = $numComitente;
        $this->Esco_model->especie = $especie;
        $asignaciones = $this->Esco_model->getParkingComitenteInstrumento();
        
        foreach ($asignaciones as $k => $v){
            
            $contador = 0;            
            $suma1dias = 0;
            
            $diaSemana1 = 0;
            $diaSemana2 = 0;
            $diaSemana3 = 0;
            $diaSemana4 = 0;
            $diaSemana5 = 0;
            
            if($k == 0 && $v['FechaLiquidacion'] == ''){
                $cantidad1 = $v['CantidadVN'];
                unset($asignaciones[$k]);
            }elseif($k == 1 && $v['FechaLiquidacion'] == ''){
                $cantidad2 = $v['CantidadVN'];
                unset($asignaciones[$k]);
            }else{
                
                if($v['EsDisponible'] == 0){
                    
                    //Si es un debito o un credito su fecha queda igual y si es mayor a hoy se quita
                    if($v['TpTransaccion'] == "Débito" || $v['TpTransaccion'] == "Crédito"){
                        if( date('Y-m-d', strtotime($v['FechaLiquidacion']))  > $fechaHoy ){
                            unset($asignaciones[$k]);
                        }
                    }
                    //Si es una compra se suman 5 días hábiles y si es mayor a hoy se quita
                    //Pero si es una compra prestamo su fecha queda igual (y si es mayor a hoy se quita)
                    if($v['TpTransaccion'] == "Compra"){                        
                        //if(es pretamo CC)
                        //DescripcionCtaCte
                        $descripcionCtaCte = $v['DescripcionCtaCte'];
                        $prestamo   = 'PRESVALCC';
                        $esPrestamo = strpos($descripcionCtaCte, $prestamo);
                        
                        if($esPrestamo == true){
                            //print_r("Este es un prestamo: ". $v['FechaLiquidacion']);
                            if( date('Y-m-d', strtotime($v['FechaLiquidacion']))  > $fechaHoy ){
                                unset($asignaciones[$k]);
                            }
                        }else{
                        
//////////////////////////////////////////////////////////////////////////////// 
//                           
                            $suma1dias = $v['FechaLiquidacion'];

                            //Hago acá cuenta cinco días hábiles
                            for ($i = 1; $i < 6; $i++) {                       
                                //Suma un día
                                $suma1dias = date('Y-m-d', strtotime($suma1dias . ' +1 day'));
                                //Mientras ese día no sea habil no lo cuento
                                $cuento = 0;
                                $valid = false;
                                while ($valid == false){
                                    $esSabado = false;
                                    $diaSemana1 = date("w", strtotime($suma1dias));
                                    if($diaSemana1 == '6'){ //Si fué sábado -3
                                        $esSabado = true;
                                    }else{
                                        $esSabado = false;
                                    }
                                    $esDomingo = false;
                                    if($diaSemana1 == '0'){ //Si fué domingo -3
                                      $esDomingo = true;
                                    }else{
                                        $esDomingo = false;
                                    }
                                    $esFeriado = false;
                                    foreach($feriados as $key => $value){
                                        if($value['feriado'] == $suma1dias){
                                            $esFeriado = true;
                                        }
                                    }                                    
                                    
                                    if($esFeriado == true || $esSabado == true || $esDomingo == true){
                                        $suma1dias = date('Y-m-d', strtotime($suma1dias . ' +1 day'));
                                        $cuento = 0;
                                    }else{
                                        $valid = true;
                                        $cuento = 1;
                                    }
                                    
                                    if($valid){
                                        break;
                                    }
                                }                                
                            }

////////////////////////////////////////////////////////////////////////////////
                            
                            $FechaLiquidacion = $suma1dias;
                            
                            $asignaciones[$k]['FechaLiquidacion'] = $FechaLiquidacion;

                            if($FechaLiquidacion > $fechaHoy){
                                unset($asignaciones[$k]);
                            }
                        }
                    }
                    
                    // En las ventas si la fecha supera a la actual, se le dá la fecha Actual
                    if($v['TpTransaccion'] == "Venta"){
                        
                        if( date('Y-m-d', strtotime($v['FechaLiquidacion'])) > $fechaHoy ){
                            $asignaciones[$k]['FechaLiquidacion'] = $fechaHoy;
                        }
                    }

                }else{
                    //Si es un debito o un credito su fecha queda igual y si es mayor a hoy se quita
                    if($v['TpTransaccion'] == "Débito" || $v['TpTransaccion'] == "Crédito"){
                        if( date('Y-m-d', strtotime($v['FechaLiquidacion']))  > $fechaHoy ){
                            unset($asignaciones[$k]);
                        }
                    }
                    //Si es una compra se suman 5 días hábiles y si es mayor a hoy se quita
                    //Pero si es una compra prestamo su fecha queda igual (y si es mayor a hoy se quita)
                    if($v['TpTransaccion'] == "Compra"){
                        
                        //if(es pretamo CC)
                        //DescripcionCtaCte
                        $descripcionCtaCte = $v['DescripcionCtaCte'];
                        $prestamo   = 'PRESVALCC';
                        $esPrestamo = strpos($descripcionCtaCte, $prestamo);
                        
                        if($esPrestamo == true){
                            //print_r("Este es un prestamo: ". $v['FechaLiquidacion']);
                            if( date('Y-m-d', strtotime($v['FechaLiquidacion']))  > $fechaHoy ){
                                unset($asignaciones[$k]);
                            }
                        }else{
                            
//////////////////////////////////////////////////////////////////////////////// 
                           
                            $suma1dias = $v['FechaLiquidacion'];
                            //Hago un bucle para que cuente cinco días hábiles
                            for ($i = 1; $i < 6; $i++) {                       
                                //Suma un día
                                $suma1dias = date('Y-m-d', strtotime($suma1dias . ' +1 day'));
                                //Mientras ese día no sea habil no lo cuento
                                $cuento = 0;
                                $valid = false;
                                while ($valid == false){
                                    $esSabado = false;
                                    $diaSemana1 = date("w", strtotime($suma1dias));
                                    if($diaSemana1 == '6'){ //Si fué sábado -3
                                        $esSabado = true;
                                    }else{
                                        $esSabado = false;
                                    }
                                    $esDomingo = false;
                                    if($diaSemana1 == '0'){ //Si fué domingo -3
                                      $esDomingo = true;
                                    }else{
                                        $esDomingo = false;
                                    }
                                    $esFeriado = false;
                                    foreach($feriados as $key => $value){
                                        if($value['feriado'] == $suma1dias){
                                            $esFeriado = true;
                                        }
                                    }                                    
                                    
                                    if($esFeriado == true || $esSabado == true || $esDomingo == true){
                                        $suma1dias = date('Y-m-d', strtotime($suma1dias . ' +1 day'));
                                        $cuento = 0;
                                    }else{
                                        $valid = true;
                                        $cuento = 1;
                                    }
                                    
                                    if($valid){
                                        break;
                                    }
                                }                                
                            }

////////////////////////////////////////////////////////////////////////////////
                            
                            $FechaLiquidacion = $suma1dias;
                            
                            $asignaciones[$k]['FechaLiquidacion'] = $FechaLiquidacion;

                            if($FechaLiquidacion > $fechaHoy){
                                unset($asignaciones[$k]);
                            }
                        
                        }
                    }
                    
                    // En las ventas si la fecha supera a la actual, se le dá la fecha Actual
                    if($v['TpTransaccion'] == "Venta"){
                        
                        if( date('Y-m-d', strtotime($v['FechaLiquidacion'])) > $fechaHoy ){
                            $asignaciones[$k]['FechaLiquidacion'] = $fechaHoy;
                        }
                    }
                }
            }
        }
        
//        echo chr(10);
//        print_r($asignaciones);
//        echo chr(10);        
        
        $suma = 0;
        foreach ($asignaciones as $k => $v){
            $suma = (float)$suma + (float)$v['CantidadVN'];
        }

        if(!(isset($cantidad1))){
           $cantidad1 = 0;
        }
        
        if(isset($cantidad2)){
            $suma2 = $cantidad1 + $cantidad2 + $suma;
        }else{
            $suma2 = $cantidad1 + $suma;
        }
        
        echo json_encode(array('resultado'=>$suma2, 'numComitente'=>$numComitente, 'especie'=>$especie));

    }

    
    
    
    
    public function grabarExcel(){
        
        $archivo = $this->input->post('file');
        $cierre = $this->input->post('cierre');
        
        $this->load->model('Parking_model');
        $this->Parking_model->archivo = $archivo;
        $this->Parking_model->cierre = $cierre;
        
        $resultado = $this->Parking_model->grabarExcel();
        echo json_encode($resultado);
        
    }
    
    
    
    
}
