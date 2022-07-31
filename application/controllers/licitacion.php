<?php
class Licitacion extends MY_AuthController {
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('licitacion/grilla');
        $this->load->view('licitacion/pie');
        $this->load->view('template/pie');
    }
    
    public function editar(){
        $datos['id'] = $this->input->post('id');
        $datos['cierre_id'] = $this->input->post('cierre_id');
        $datos['origen'] = $this->input->post('origen');
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('licitacion/editar', $datos);
        $this->load->view('template/pie');
    }
    
    public function saveOrden(){
        $this->load->model('Licitacion_model');
        $this->Licitacion_model->id = $this->input->post('id');
        $this->Licitacion_model->tramo = $this->input->post('tramo');
        $this->Licitacion_model->numComitente = $this->input->post('numComitente');
        $this->Licitacion_model->moneda = $this->input->post('moneda');
        $this->Licitacion_model->cable = $this->input->post('cable');
        $this->Licitacion_model->plazo = $this->input->post('plazo');
        $this->Licitacion_model->especie = $this->input->post('especie');
        $this->Licitacion_model->comision = $this->input->post('comision');
        $this->Licitacion_model->cantidad = $this->input->post('cantidad');
        $this->Licitacion_model->precio = $this->input->post('precio');
        $this->Licitacion_model->comitente = $this->input->post('comitente');
        $this->Licitacion_model->tipoInversor = $this->input->post('tipoInversor');
        $this->Licitacion_model->tipoPersona = $this->input->post('tipoPersona');
        $this->Licitacion_model->oficial = $this->input->post('oficial');
        $this->Licitacion_model->cuit = $this->input->post('cuit');
        $this->Licitacion_model->cierrelicitacion_id = $this->input->post('cierre_id');
        $orden = $this->Licitacion_model->saveOrden();
        echo json_encode($orden);
    }
    
    public function getOrden(){
        $this->load->model('Licitacion_model');
        $this->Licitacion_model->id = $this->input->post('id');
        $orden = $this->Licitacion_model->getOrden();
        echo json_encode($orden);
    }
    
    public function delOrden(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Licitacion_model');
        $this->Licitacion_model->ordenes = $ordenes;
        $this->Licitacion_model->delOrden();
        echo json_encode(array('resultado'=>'Ordenes borradas exitosamente'));
    }
    
    public function grilla(){
        
        
        $usuario = $this->session->userdata('usuario');
        $usuario_id = $usuario['id'];
        
        $cierre_id = $this->input->post('cierrelicitacion_id');
        
                
        $this->load->model('Licitacion_model');
        $this->Licitacion_model->usuario_id = $usuario_id;
        $this->Licitacion_model->cierrelicitacion_id = $cierre_id;
        $resultado = $this->Licitacion_model->grilla();
        
        echo json_encode($resultado);

        
    }
    
    public function enviarOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Licitacion_model');
        $this->Licitacion_model->ordenes = $ordenes;
        $resultado = $this->Licitacion_model->enviarOrdenes();
        echo json_encode($resultado);
    }
    
    public function retirarOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Licitacion_model');
        $this->Licitacion_model->ordenes = $ordenes;
        $resultado = $this->Licitacion_model->retirarOrdenes();
        echo json_encode($resultado);
    }
    
    
    public function anularOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Licitacion_model');
        $this->Licitacion_model->ordenes = $ordenes;
        $resultado = $this->Licitacion_model->anularOrdenes();
        echo json_encode($resultado);        
    }
    
    public function cierreEditar(){
        $data['id'] = $this->input->post('id');
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('licitacion/cierreEditar', $data);
        $this->load->view('template/pie');
    }
    
    public function getCierre(){
        $cierre_id = $this->input->post('cierrelicitacion_id');
        $this->load->model('Licitacion_model');
        $this->Licitacion_model->cierrelicitacion_id = $cierre_id;
        $resultado = $this->Licitacion_model->getCierre();
        echo json_encode($resultado);
    }
    
    public function getPlazos(){
        $this->load->model('Licitacion_model');
        $this->Licitacion_model->cierrelicitacion_id = $this->input->post('cierrelicitacion_id');
        $this->Licitacion_model->moneda = $this->input->post('moneda');
        $resultado = $this->Licitacion_model->getPlazos();
        echo json_encode($resultado);
    }
    
    public function getPlazosEspecies(){
        $this->load->model('Licitacion_model');
        $this->Licitacion_model->cierrelicitacion_id = $this->input->post('cierrelicitacion_id');
//        $this->Licitacion_model->moneda = $this->input->post('moneda');
        $resultado = $this->Licitacion_model->getPlazosEspecies();
        echo json_encode($resultado);
    }
    
    public function getMonedas(){
        $this->load->model('Licitacion_model');
        $this->Licitacion_model->cierrelicitacion_id = $this->input->post('cierrelicitacion_id');
        $resultado = $this->Licitacion_model->getMonedas();
        echo json_encode($resultado);
    }
    
    public function saveCierre(){
        $cierre_id = $this->input->post('cierrelicitacion_id');
        $fechaHora = $this->input->post('fechahora');
        $plazos = $this->input->post('plazos');
        $plazosBorrar = $this->input->post('plazosBorrar');
        $this->load->model('Licitacion_model');
        $this->Licitacion_model->cierrelicitacion_id = $cierre_id;
        $this->Licitacion_model->fechahora = $fechaHora;
        $this->Licitacion_model->plazos = $plazos;
        $this->Licitacion_model->plazosBorrar = $plazosBorrar;
        $cierre = $this->Licitacion_model->saveCierre();
        echo json_encode($cierre);
    }
    
    public function delCierre(){
        $cierre_id = $this->input->post('id');
        $this->load->model('Licitacion_model');
        $this->Licitacion_model->cierrelicitacion_id = $cierre_id;
        $this->Licitacion_model->delCierre();
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
        $table = "(select * from cierrelicitacion order by fechahora desc) as cierre";
        $fields = array('id','fechahora');
        $datos = $this->grilla_model->datosGrilla($table, $fields, $pagenum, $pagesize, 
                $filterscount, $filtervalue, $filtercondition, $filterdatafield, 
                $filteroperator, $sortdatafield, $sortorder);
        echo json_encode($datos);
    }

    public function cierre(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('licitacion/cierreGrilla');
        $this->load->view('template/pie');
    }
    
//    public function getCierreActual($cierreId){
//        
//        print_r($cierreActual); die;
//        
//        $this->load->model('Licitacion_model');
//        $cierreActual = $this->Licitacion_model->getCierreActual($cierreId);
//        echo json_encode($cierreActual);
//    }
    
    public function getCierreActual(){
        $this->load->model('Licitacion_model');
        $cierreActual = $this->Licitacion_model->getCierreActual();
        echo json_encode($cierreActual);
    }
    

    public function procesar(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('licitacion/procesar');
        $this->load->view('template/pie');
    }
    
    public function procesarGrilla(){
        
        $cierre_id = $this->input->post('cierrelicitacion_id');
        
        $this->load->model('Licitacion_model');
        $this->Licitacion_model->cierrelicitacion_id = $cierre_id;
        $resultado = $this->Licitacion_model->procesarGrilla();
        
        echo json_encode($resultado);
    }
    
    public function getCierres(){
        $this->load->model('Licitacion_model');
        $cierres = $this->Licitacion_model->getCierres();
        echo json_encode($cierres);
    }
    
    public function getCierresAbiertos(){
        $this->load->model('Licitacion_model');
        $cierres = $this->Licitacion_model->getCierresAbiertos();
        echo json_encode($cierres);
    }
    
    public function grillaResumen(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Licitacion_model');
        $this->Licitacion_model->ordenes = $ordenes;
        $resultado = $this->Licitacion_model->grillaResumen();
        echo json_encode($resultado);
    }
    
    public function previewSantander(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Licitacion_model');
        $this->Licitacion_model->ordenes = $ordenes;
        $resultado = $this->Licitacion_model->previewSantander();
        echo json_encode($resultado);
    }
    
    public function enviarSantander(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Licitacion_model');
        $this->Licitacion_model->ordenes = $ordenes;
        $resultado = $this->Licitacion_model->enviarSantander();
        echo json_encode($resultado);
    }
    
    public function previewMercado(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Licitacion_model');
        $this->Licitacion_model->ordenes = $ordenes;
        $resultado = $this->Licitacion_model->previewMercado();
        echo json_encode($resultado);
    }

    public function enviarMercado(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Licitacion_model');
        $this->Licitacion_model->ordenes = $ordenes;
        $resultado = $this->Licitacion_model->enviarMercado();
        echo json_encode($resultado);
    }
    
    public function grabarExcel(){
        
        $archivo = $this->input->post('file');
        $cierre = $this->input->post('cierre');
        
        $this->load->model('Licitacion_model');
        $this->Licitacion_model->archivo = $archivo;
        $this->Licitacion_model->cierre = $cierre;
        
        $resultado = $this->Licitacion_model->grabarExcel();
        echo json_encode($resultado);
        
    }
    
}
