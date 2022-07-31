<?php
class Correccion extends MY_AuthController {
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('correccion/grilla');
        $this->load->view('correccion/pie');
        $this->load->view('template/pie');
    }
    
    public function editar(){
        $datos['id'] = $this->input->post('id');
        $datos['origen'] = $this->session->userdata['usuario']['nombre'] . " " . $this->session->userdata['usuario']['apellido'];
        $datos['userId'] = $this->session->userdata['usuario']['id'];
        $usuarioNombre = $this->session->userdata['usuario']['nombre'] . " " . $this->session->userdata['usuario']['apellido'];  
        $datos['usuario'] = $usuarioNombre;
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('correccion/editar', $datos);
        $this->load->view('template/pie');
    }
    
    public function saveOrden(){
        $this->load->model('Correccion_model');
        $this->Correccion_model->id = $this->input->post('id');
        $this->Correccion_model->operador = $this->input->post('operador');
//        $this->Correccion_model->codBoleto = $this->input->post('codBoleto');
        $this->Correccion_model->numBoleto = $this->input->post('numBoleto');
        $this->Correccion_model->fechaConcertacion = $this->input->post('fechaConcertacion');
        $this->Correccion_model->fechaLiquidacion = $this->input->post('fechaLiquidacion');
        $this->Correccion_model->tpOperacionBurs = $this->input->post('tpOperacionBurs');
        $this->Correccion_model->numComitente = $this->input->post('numComitente');
        $this->Correccion_model->comitente = $this->input->post('comitente');
        $this->Correccion_model->administrador = $this->input->post('administrador');
        $this->Correccion_model->instrumentoAbrev = $this->input->post('instrumentoAbrev');
        $this->Correccion_model->moneda = $this->input->post('moneda');
        $this->Correccion_model->precio = $this->input->post('precio');
        $this->Correccion_model->cantidad = $this->input->post('cantidad');
        $this->Correccion_model->arancel = $this->input->post('arancel');
        $this->Correccion_model->numComitenteCorregido = $this->input->post('numComitenteCorregido');
        $this->Correccion_model->comitenteCorregido = $this->input->post('comitenteCorregido');
//        $this->Correccion_model->administradorCorregido = $this->input->post('administradorCorregido');
        $this->Correccion_model->cantidadCorregido = $this->input->post('cantidadCorregido');
        $this->Correccion_model->arancelCorregido = $this->input->post('arancelCorregido');
        
        $this->Correccion_model->precioCorregido = $this->input->post('precioCorregido');
        $this->Correccion_model->tipoOperacionCorregido = $this->input->post('tipoOperacionCorregido');
        $this->Correccion_model->especieCorregido = $this->input->post('especieCorregido');
        
        $this->Correccion_model->observaciones = $this->input->post('observaciones');
        $this->Correccion_model->autorizadores = $this->input->post('autorizadores');
        $this->Correccion_model->procesadores = $this->input->post('procesadores');
        $this->Correccion_model->controladores = $this->input->post('controladores');
        $this->Correccion_model->caja = $this->input->post('caja');
        $this->Correccion_model->visual = $this->input->post('visual');
        $this->Correccion_model->control = $this->input->post('control');
        $orden = $this->Correccion_model->saveOrden();
        echo json_encode($orden);
    }   

    
    //Esto ya no se usa más porq    ue esco rompíó la importación de minutas y boletos (importar historia, el 2020-01-21)
//    public function getBoleto(){
//        $this->load->model('Correccion_model');
////        $this->Correccion_model->codBoleto = $this->input->post('codBoleto');
//        $this->Correccion_model->numBoleto = $this->input->post('numBoleto');
//        $boleto = $this->Correccion_model->getBoleto();
//        echo json_encode($boleto);
//    }
    
    public function getOrden(){
        $this->load->model('Correccion_model');
        $this->Correccion_model->id = $this->input->post('id');
        $orden = $this->Correccion_model->getOrden();
        echo json_encode($orden);
    }
    
    public function delOrden(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Correccion_model');
        $this->Correccion_model->ordenes = $ordenes;
        $this->Correccion_model->delOrden();
        echo json_encode(array('resultado'=>'Ordenes borradas exitosamente'));
    }
    
    public function enviarOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Correccion_model');
        $this->Correccion_model->ordenes = $ordenes;
        $resultado = $this->Correccion_model->enviarOrdenes();
        echo json_encode($resultado);
    }
    
    public function aprobarOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $usuario = $this->session->userdata['usuario']['id'];  

        $this->load->model('Correccion_model');
        $this->Correccion_model->ordenes = $ordenes;
        $this->Correccion_model->usuario = $usuario;
        
        $resultado = $this->Correccion_model->aprobarOrdenes();
        echo json_encode($resultado);
    }    
    
    public function anularOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Correccion_model');
        $this->Correccion_model->ordenes = $ordenes;
        $resultado = $this->Correccion_model->anularOrdenes();
        echo json_encode($resultado);        
    }
    
    public function procesarOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $usuario = $this->session->userdata['usuario']['id'];  

        $this->load->model('Correccion_model');
        $this->Correccion_model->ordenes = $ordenes;
        $this->Correccion_model->usuario = $usuario;
        
        $resultado = $this->Correccion_model->procesarOrdenes();
        echo json_encode($resultado);     
    }
    
    public function controlarOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $usuario = $this->session->userdata['usuario']['id'];  

        $this->load->model('Correccion_model');
        $this->Correccion_model->ordenes = $ordenes;
        $this->Correccion_model->usuario = $usuario;
        
        $resultado = $this->Correccion_model->controlarOrdenes();
        echo json_encode($resultado);     
    }
    
    
    public function cierreEditar(){
        $data['id'] = $this->input->post('id');
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('correccion/cierreEditar', $data);
        $this->load->view('template/pie');
    }
    
    public function getCierre(){
        $cierrecorreccion_id = $this->input->post('cierrecorreccion_id');
        $this->load->model('Correccion_model');
        $this->Correccion_model->cierrecorreccion_id = $cierrecorreccion_id;
        $resultado = $this->Correccion_model->getCierre();
        echo json_encode($resultado);
    }
    
    public function saveCierre(){
        $cierrecorreccion_id = $this->input->post('cierrecorreccion_id');
        $fechaHora = $this->input->post('fechahora');
        $plazos = $this->input->post('plazos');
        $minimos = $this->input->post('minimos');
        $this->load->model('Correccion_model');
        $this->Correccion_model->cierrecorreccion_id = $cierrecorreccion_id;
        $this->Correccion_model->fechahora = $fechaHora;
        $this->Correccion_model->plazos = $plazos;
        $this->Correccion_model->minimos = $minimos;
        
        $cierre = $this->Correccion_model->saveCierre();
        echo json_encode($cierre);
    }
    
    public function delCierre(){
        $cierrecorreccion_id = $this->input->post('id');
        $this->load->model('Correccion_model');
        $this->Correccion_model->cierrecorreccion_id = $cierrecorreccion_id;
        $this->Correccion_model->delCierre();
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
        $table = "cierrecorreccion";
        $fields = array('id','fechahora','plazos');
        $datos = $this->grilla_model->datosGrilla($table, $fields, $pagenum, $pagesize, 
                $filterscount, $filtervalue, $filtercondition, $filterdatafield, 
                $filteroperator, $sortdatafield, $sortorder);
        echo json_encode($datos);
    }

    public function cierre(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('correccion/cierreGrilla');
        $this->load->view('template/pie');
    }
    
    public function getCierreActual(){
        $this->load->model('Correccion_model');
        $cierreActual = $this->Correccion_model->getCierreActual();
        echo json_encode($cierreActual);
    }

    public function procesar(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('correccion/procesar');
        $this->load->view('template/pie');
    }
    
    public function aprobar(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('correccion/aprobar');
        $this->load->view('template/pie');
    }
    
    public function control(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('correccion/control');
        $this->load->view('template/pie');
    }
    
    public function grilla(){        
        $usuario = $this->session->userdata('usuario');
        $usuario_id = $usuario['id'];
        $cierrecorreccion_id = $this->input->post('cierrecorreccion_id');                
        $this->load->model('Correccion_model');
        $this->Correccion_model->usuario_id = $usuario_id;
        $this->Correccion_model->cierrecorreccion_id = $cierrecorreccion_id;
        $resultado = $this->Correccion_model->grilla();
        
        echo json_encode($resultado);
    }
    
    public function procesarGrilla(){
        $cierrecorreccion_id = $this->input->post('cierrecorreccion_id');
        $this->load->model('Correccion_model');
        $this->Correccion_model->cierrecorreccion_id = $cierrecorreccion_id;
        $resultado = $this->Correccion_model->procesarGrilla();        
        
        echo json_encode($resultado);
    }
    
    public function aprobarGrilla(){
        $cierrecorreccion_id = $this->input->post('cierrecorreccion_id');
        $this->load->model('Correccion_model');
        $this->Correccion_model->cierrecorreccion_id = $cierrecorreccion_id;
        $resultado = $this->Correccion_model->aprobarGrilla();
        
        echo json_encode($resultado);
    }
    
    public function controlGrilla(){
        $cierrecorreccion_id = $this->input->post('cierrecorreccion_id');
        $this->load->model('Correccion_model');
        $this->Correccion_model->cierrecorreccion_id = $cierrecorreccion_id;
        $resultado = $this->Correccion_model->controlGrilla();
        
        echo json_encode($resultado);
    }
    
    public function getCierres(){
        $this->load->model('Correccion_model');
        $cierres = $this->Correccion_model->getCierres();
        echo json_encode($cierres);
    }
    
    public function grillaResumen(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Correccion_model');
        $this->Correccion_model->ordenes = $ordenes;
        $resultado = $this->Correccion_model->grillaResumen();
        echo json_encode($resultado);
    }
    
    
    public function previewMercado(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Correccion_model');
        $this->Correccion_model->ordenes = $ordenes;
        $resultado = $this->Correccion_model->previewMercado();
        
        echo json_encode($resultado);
    }

    public function enviarMercado(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Correccion_model');
        $this->Correccion_model->ordenes = $ordenes;
        $resultado = $this->Correccion_model->enviarMercado();
        echo json_encode($resultado);
    }
    
}
