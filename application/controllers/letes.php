<?php
class Letes extends MY_AuthController {
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('letes/grilla');
        $this->load->view('letes/pie');
        $this->load->view('template/pie');
    }
    
    public function editar(){
        $datos['id'] = $this->input->post('id');
        $datos['origen'] = $this->input->post('origen');
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('letes/editar', $datos);
        $this->load->view('template/pie');
    }
    
    public function saveOrden(){
        $this->load->model('Letes_model');
        $this->Letes_model->id = $this->input->post('id');
        //$this->Letes_model->tramo = $this->input->post('tramo');
        $this->Letes_model->numComitente = $this->input->post('numComitente');
        $this->Letes_model->moneda = $this->input->post('moneda');
        $this->Letes_model->cable = $this->input->post('cable');
        $this->Letes_model->plazo = $this->input->post('plazo');
        $this->Letes_model->comision = $this->input->post('comision');
        $this->Letes_model->cantidad = $this->input->post('cantidad');
        //$this->Letes_model->precio = $this->input->post('precio');
        $this->Letes_model->comitente = $this->input->post('comitente');
        $this->Letes_model->tipoPersona = $this->input->post('tipoPersona');
        $this->Letes_model->oficial = $this->input->post('oficial');
        $this->Letes_model->cuit = $this->input->post('cuit');
        $orden = $this->Letes_model->saveOrden();
        echo json_encode($orden);
    }
    
    public function getOrden(){
        $this->load->model('Letes_model');
        $this->Letes_model->id = $this->input->post('id');
        $orden = $this->Letes_model->getOrden();
        echo json_encode($orden);
    }
    
    public function delOrden(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Letes_model');
        $this->Letes_model->ordenes = $ordenes;
        $this->Letes_model->delOrden();
        echo json_encode(array('resultado'=>'Ordenes borradas exitosamente'));
    }
    
    public function grilla(){
        
        
        $usuario = $this->session->userdata('usuario');
        $usuario_id = $usuario['id'];
        
        $cierreletes_id = $this->input->post('cierreletes_id');
        
                
        $this->load->model('Letes_model');
        $this->Letes_model->usuario_id = $usuario_id;
        $this->Letes_model->cierreletes_id = $cierreletes_id;
        $resultado = $this->Letes_model->grilla();
        
        echo json_encode($resultado);

        
    }
    
    public function enviarOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Letes_model');
        $this->Letes_model->ordenes = $ordenes;
        $resultado = $this->Letes_model->enviarOrdenes();
        echo json_encode($resultado);
    }
    
    public function anularOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Letes_model');
        $this->Letes_model->ordenes = $ordenes;
        $resultado = $this->Letes_model->anularOrdenes();
        echo json_encode($resultado);        
    }
    
    public function cierreEditar(){
        $data['id'] = $this->input->post('id');
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('letes/cierreEditar', $data);
        $this->load->view('template/pie');
    }
    
    public function getCierre(){
        $cierreletes_id = $this->input->post('cierreletes_id');
        $this->load->model('Letes_model');
        $this->Letes_model->cierreletes_id = $cierreletes_id;
        $resultado = $this->Letes_model->getCierre();
        echo json_encode($resultado);
    }
    
    public function saveCierre(){
        $cierreletes_id = $this->input->post('cierreletes_id');
        $fechaHora = $this->input->post('fechahora');
        $plazos = $this->input->post('plazos');
        $minimos = $this->input->post('minimos');
        $this->load->model('Letes_model');
        $this->Letes_model->cierreletes_id = $cierreletes_id;
        $this->Letes_model->fechahora = $fechaHora;
        $this->Letes_model->plazos = $plazos;
        $this->Letes_model->minimos = $minimos;
        $this->Letes_model->colocacionPesos = $this->input->post('colocacionPesos');
        $this->Letes_model->colocacionDolares = $this->input->post('colocacionDolares');
        $cierre = $this->Letes_model->saveCierre();
        echo json_encode($cierre);
    }
    
    public function delCierre(){
        $cierreletes_id = $this->input->post('id');
        $this->load->model('Letes_model');
        $this->Letes_model->cierreletes_id = $cierreletes_id;
        $this->Letes_model->delCierre();
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
        $table = "cierreletes";
        $fields = array('id','fechahora','plazos');
        $datos = $this->grilla_model->datosGrilla($table, $fields, $pagenum, $pagesize, 
                $filterscount, $filtervalue, $filtercondition, $filterdatafield, 
                $filteroperator, $sortdatafield, $sortorder);
        echo json_encode($datos);
    }

    public function cierre(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('letes/cierreGrilla');
        $this->load->view('template/pie');
    }
    
    public function getCierreActual(){
        $this->load->model('Letes_model');
        $cierreActual = $this->Letes_model->getCierreActual();
        echo json_encode($cierreActual);
    }

    public function procesar(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('letes/procesar');
        $this->load->view('template/pie');
    }
    
    public function procesarGrilla(){
        
        $cierreletes_id = $this->input->post('cierreletes_id');
        
        $this->load->model('Letes_model');
        $this->Letes_model->cierreletes_id = $cierreletes_id;
        $resultado = $this->Letes_model->procesarGrilla();
        
        echo json_encode($resultado);
    }
    
    public function getCierres(){
        $this->load->model('Letes_model');
        $cierres = $this->Letes_model->getCierres();
        echo json_encode($cierres);
    }
    
    public function grillaResumen(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Letes_model');
        $this->Letes_model->ordenes = $ordenes;
        $resultado = $this->Letes_model->grillaResumen();
        echo json_encode($resultado);
    }
    
    
    public function previewMercado(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Letes_model');
        $this->Letes_model->ordenes = $ordenes;
        $resultado = $this->Letes_model->previewMercado();
        
        echo json_encode($resultado);
    }

    public function enviarMercado(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Letes_model');
        $this->Letes_model->ordenes = $ordenes;
        $resultado = $this->Letes_model->enviarMercado();
        echo json_encode($resultado);
    }
    
}
