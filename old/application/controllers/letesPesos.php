<?php
class LetesPesos extends MY_AuthController {
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('letesPesos/grilla');
        $this->load->view('letesPesos/pie');
        $this->load->view('template/pie');
    }
    
    public function editar(){
        $datos['id'] = $this->input->post('id');
        $datos['origen'] = $this->input->post('origen');
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('letesPesos/editar', $datos);
        $this->load->view('template/pie');
    }
    
    public function saveOrden(){
        $this->load->model('LetesPesos_model');
        $this->LetesPesos_model->id = $this->input->post('id');
        $this->LetesPesos_model->tramo = $this->input->post('tramo');
        $this->LetesPesos_model->numComitente = $this->input->post('numComitente');
        $this->LetesPesos_model->moneda = $this->input->post('moneda');
        $this->LetesPesos_model->cable = $this->input->post('cable');
        $this->LetesPesos_model->plazo = $this->input->post('plazo');
        $this->LetesPesos_model->comision = $this->input->post('comision');
        $this->LetesPesos_model->cantidad = $this->input->post('cantidad');
        $this->LetesPesos_model->precio = $this->input->post('precio');
        $this->LetesPesos_model->comitente = $this->input->post('comitente');
        $this->LetesPesos_model->tipoPersona = $this->input->post('tipoPersona');
        $this->LetesPesos_model->oficial = $this->input->post('oficial');
        $this->LetesPesos_model->cuit = $this->input->post('cuit');
        $orden = $this->LetesPesos_model->saveOrden();
        echo json_encode($orden);
    }
    
    public function getOrden(){
        $this->load->model('LetesPesos_model');
        $this->LetesPesos_model->id = $this->input->post('id');
        $orden = $this->LetesPesos_model->getOrden();
        echo json_encode($orden);
    }
    
    public function delOrden(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('LetesPesos_model');
        $this->LetesPesos_model->ordenes = $ordenes;
        $this->LetesPesos_model->delOrden();
        echo json_encode(array('resultado'=>'Ordenes borradas exitosamente'));
    }
    
    public function grilla(){
        
        
        $usuario = $this->session->userdata('usuario');
        $usuario_id = $usuario['id'];
        
        $cierreletespesos_id = $this->input->post('cierreletespesos_id');
        
                
        $this->load->model('LetesPesos_model');
        $this->LetesPesos_model->usuario_id = $usuario_id;
        $this->LetesPesos_model->cierreletespesos_id = $cierreletespesos_id;
        $resultado = $this->LetesPesos_model->grilla();
        
        echo json_encode($resultado);

        
    }
    
    public function enviarOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('LetesPesos_model');
        $this->LetesPesos_model->ordenes = $ordenes;
        $resultado = $this->LetesPesos_model->enviarOrdenes();
        echo json_encode($resultado);
    }
    
    public function anularOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('LetesPesos_model');
        $this->LetesPesos_model->ordenes = $ordenes;
        $resultado = $this->LetesPesos_model->anularOrdenes();
        echo json_encode($resultado);        
    }
    
    public function cierreEditar(){
        $data['id'] = $this->input->post('id');
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('letesPesos/cierreEditar', $data);
        $this->load->view('template/pie');
    }
    
    public function getCierre(){
        $cierreletespesos_id = $this->input->post('cierreletespesos_id');
        $this->load->model('LetesPesos_model');
        $this->LetesPesos_model->cierreletespesos_id = $cierreletespesos_id;
        $resultado = $this->LetesPesos_model->getCierre();
        echo json_encode($resultado);
    }
    
    public function getPlazos(){
        $this->load->model('LetesPesos_model');
        $this->LetesPesos_model->cierreletespesos_id = $this->input->post('cierreletespesos_id');
        $this->LetesPesos_model->moneda = $this->input->post('moneda');
        $resultado = $this->LetesPesos_model->getPlazos();
        echo json_encode($resultado);
    }
    
    public function getMonedas(){
        $this->load->model('LetesPesos_model');
        $this->LetesPesos_model->cierreletespesos_id = $this->input->post('cierreletespesos_id');
        $resultado = $this->LetesPesos_model->getMonedas();
        echo json_encode($resultado);
    }
    
    public function saveCierre(){
        $cierreletespesos_id = $this->input->post('cierreletespesos_id');
        $fechaHora = $this->input->post('fechahora');
        $plazos = $this->input->post('plazos');
        $plazosBorrar = $this->input->post('plazosBorrar');
        $instrumento = $this->input->post('instrumento');
        $this->load->model('LetesPesos_model');
        $this->LetesPesos_model->cierreletespesos_id = $cierreletespesos_id;
        $this->LetesPesos_model->fechahora = $fechaHora;
        $this->LetesPesos_model->plazos = $plazos;
        $this->LetesPesos_model->plazosBorrar = $plazosBorrar;
        $this->LetesPesos_model->instrumento = $instrumento;
        $cierre = $this->LetesPesos_model->saveCierre();
        echo json_encode($cierre);
    }
    
    public function delCierre(){
        $cierreletespesos_id = $this->input->post('id');
        $this->load->model('LetesPesos_model');
        $this->LetesPesos_model->cierreletespesos_id = $cierreletespesos_id;
        $this->LetesPesos_model->delCierre();
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
        $this->load->model('grilla_model');
        $table = "(select * from cierreletespesos order by fechahora desc) as cierre";
        $fields = array('id','fechahora');
        $datos = $this->grilla_model->datosGrilla($table, $fields, $pagenum, $pagesize, 
                $filterscount, $filtervalue, $filtercondition, $filterdatafield, 
                $filteroperator, $sortdatafield, $sortorder);
        echo json_encode($datos);
    }

    public function cierre(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('letesPesos/cierreGrilla');
        $this->load->view('template/pie');
    }
    
    public function getCierreActual(){
        $this->load->model('LetesPesos_model');
        $cierreActual = $this->LetesPesos_model->getCierreActual();
        echo json_encode($cierreActual);
    }

    public function procesar(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('letesPesos/procesar');
        $this->load->view('template/pie');
    }
    
    public function procesarGrilla(){
        
        $cierreletespesos_id = $this->input->post('cierreletespesos_id');
        $this->load->model('LetesPesos_model');
        $this->LetesPesos_model->cierreletespesos_id = $cierreletespesos_id;
        $resultado = $this->LetesPesos_model->procesarGrilla();
        
        echo json_encode($resultado);
    }
    
    public function getOrdenes(){
        $cierreletespesos_id = $this->input->post('cierreletespesos_id');
        $numComitente = $this->input->post('numComitente');
        $this->load->model('LetesPesos_model');
        $this->LetesPesos_model->cierreletespesos_id = $cierreletespesos_id;
        $this->LetesPesos_model->numComitente = $numComitente;
        $resultado = $this->LetesPesos_model->getOrdenes();
        
        echo json_encode($resultado);
    }    
    public function getCierres(){
        $this->load->model('LetesPesos_model');
        $cierres = $this->LetesPesos_model->getCierres();
        echo json_encode($cierres);
    }
    
    public function grillaResumen(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('LetesPesos_model');
        $this->LetesPesos_model->ordenes = $ordenes;
        $resultado = $this->LetesPesos_model->grillaResumen();
        echo json_encode($resultado);
    }
    
    public function previewSantander(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('LetesPesos_model');
        $this->LetesPesos_model->ordenes = $ordenes;
        $resultado = $this->LetesPesos_model->previewSantander();
        echo json_encode($resultado);
    }
    
    public function enviarSantander(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('LetesPesos_model');
        $this->LetesPesos_model->ordenes = $ordenes;
        $resultado = $this->LetesPesos_model->enviarSantander();
        echo json_encode($resultado);
    }
    
    public function previewMercado(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('LetesPesos_model');
        $this->LetesPesos_model->ordenes = $ordenes;
        $resultado = $this->LetesPesos_model->previewMercado();
        echo json_encode($resultado);
    }

    public function enviarMercado(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('LetesPesos_model');
        $this->LetesPesos_model->ordenes = $ordenes;
        $resultado = $this->LetesPesos_model->enviarMercado();
        echo json_encode($resultado);
    }
    
}
