<?php
class Canje extends MY_AuthController {
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('canje/grilla');
        $this->load->view('canje/pie');
        $this->load->view('template/pie');
    }
    
    public function editar(){
        $datos['id'] = $this->input->post('id');
        $datos['origen'] = $this->input->post('origen');
        $user = $this->session->userdata('usuario');
        $datos['user'] = $user['nombreUsuario'];
//        print_r( $this->session->userdata('usuario'));
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('canje/editar', $datos);
        $this->load->view('template/pie');
    }
    
    public function saveOrden(){
        $this->load->model('Canje_model');
        $this->Canje_model->id = $this->input->post('id');
        $this->Canje_model->numComitente = $this->input->post('numComitente');
//        $this->Canje_model->comision = $this->input->post('comision');
        $this->Canje_model->cantidad = $this->input->post('cantidad');
        $this->Canje_model->plazo = $this->input->post('plazo');
        $this->Canje_model->arancel = $this->input->post('arancel');
//        $this->Canje_model->bono = $this->input->post('bono');
//        $this->Canje_model->cantidadACrecer = $this->input->post('cantidadACrecer');
//        $this->Canje_model->precio = $this->input->post('precio');
//        $this->Canje_model->segundaParte = $this->input->post('segundaParte');
//        $this->Canje_model->cantidadAcrecerSegunda = $this->input->post('cantidadAcrecerSegunda');
        $this->Canje_model->comitente = $this->input->post('comitente');
        $this->Canje_model->tipoPersona = $this->input->post('tipoPersona');
        $this->Canje_model->oficial = $this->input->post('oficial');
        $this->Canje_model->cuit = $this->input->post('cuit');
        $this->Canje_model->posicion = $this->input->post('posicion');
        $orden = $this->Canje_model->saveOrden();
        echo json_encode($orden);
    }
    
    public function getPlazos(){
        $this->load->model('Canje_model');
        $this->Canje_model->cierrecanje_id = $this->input->post('cierrecanje_id');
//        $this->Canje_model->moneda = $this->input->post('moneda');
        $resultado = $this->Canje_model->getPlazos();
        echo json_encode($resultado);
    }
    
    public function getPlazosEspecies(){
        $this->load->model('Canje_model');
        $this->Canje_model->cierrecanje_id = $this->input->post('cierrecanje_id');
//        $this->Canje_model->moneda = $this->input->post('moneda');
        $resultado = $this->Canje_model->getPlazosEspecies();
        echo json_encode($resultado);
    }
    
    
    public function getOrden(){
        $this->load->model('Canje_model');
        $this->Canje_model->id = $this->input->post('id');
        $orden = $this->Canje_model->getOrden();
        echo json_encode($orden);
    }
    
    public function delOrden(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Canje_model');
        $this->Canje_model->ordenes = $ordenes;
        $this->Canje_model->delOrden();
        echo json_encode(array('resultado'=>'Ordenes borradas exitosamente'));
    }
    
    public function grilla(){
        
        
        $usuario = $this->session->userdata('usuario');
        $usuario_id = $usuario['id'];
        
        $cierre_id = $this->input->post('cierrecanje_id');
        
                
        $this->load->model('Canje_model');
        $this->Canje_model->usuario_id = $usuario_id;
        $this->Canje_model->cierrecanje_id = $cierre_id;
        $resultado = $this->Canje_model->grilla();
        
        echo json_encode($resultado);

        
    }
    
    public function enviarOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Canje_model');
        $this->Canje_model->ordenes = $ordenes;
        $resultado = $this->Canje_model->enviarOrdenes();
        echo json_encode($resultado);
    }
    
    public function anularOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Canje_model');
        $this->Canje_model->ordenes = $ordenes;
        $resultado = $this->Canje_model->anularOrdenes();
        echo json_encode($resultado);        
    }
    
    public function cierreEditar(){
        $data['id'] = $this->input->post('id');
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('canje/cierreEditar', $data);
        $this->load->view('template/pie');
    }
    
    public function getCierre(){
        $cierre_id = $this->input->post('cierrecanje_id');
        $this->load->model('Canje_model');
        $this->Canje_model->cierrecanje_id = $cierre_id;
        $resultado = $this->Canje_model->getCierre();
        echo json_encode($resultado);
    }
    
    public function saveCierre(){
        $cierrecanje_id = $this->input->post('cierrecanje_id');
        $fechaHora = $this->input->post('fechahora');
        $plazos = $this->input->post('plazos');
        $plazosBorrar = $this->input->post('plazosBorrar');
//        $instrumento = $this->input->post('instrumento');
        $this->load->model('Canje_model');
        $this->Canje_model->cierrecanje_id = $cierrecanje_id;
        $this->Canje_model->fechahora = $fechaHora;
        $this->Canje_model->plazos = $plazos;
        $this->Canje_model->plazosBorrar = $plazosBorrar;
//        $this->Canje_model->instrumento = $instrumento;
        $cierre = $this->Canje_model->saveCierre();
        echo json_encode($cierre);
    }
    
    public function delCierre(){
        $cierrecanje_id = $this->input->post('id');
        $this->load->model('Canje_model');
        $this->Canje_model->cierrecanje_id = $cierrecanje_id;
        $this->Canje_model->delCierre();
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
        $table = "(select * from cierrecanje order by fechahora desc) as cierre";
        $fields = array('id','fechahora');
        $datos = $this->grilla_model->datosGrilla($table, $fields, $pagenum, $pagesize, 
                $filterscount, $filtervalue, $filtercondition, $filterdatafield, 
                $filteroperator, $sortdatafield, $sortorder);
        echo json_encode($datos);
    }

    public function cierre(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('canje/cierreGrilla');
        $this->load->view('template/pie');
    }
    
    public function getCierreActual(){
        $this->load->model('Canje_model');
        $cierreActual = $this->Canje_model->getCierreActual();
        echo json_encode($cierreActual);
    }

    public function procesar(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('canje/procesar');
        $this->load->view('template/pie');
    }
    
    public function procesarGrilla(){
        
        $cierre_id = $this->input->post('cierrecanje_id');
        
        $this->load->model('Canje_model');
        $this->Canje_model->cierrecanje_id = $cierre_id;
        $resultado = $this->Canje_model->procesarGrilla();
        
        echo json_encode($resultado);
    }
    
    public function getCierres(){
        $this->load->model('Canje_model');
        $cierres = $this->Canje_model->getCierres();
        echo json_encode($cierres);
    }
    
    public function grillaResumen(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Canje_model');
        $this->Canje_model->ordenes = $ordenes;
        $resultado = $this->Canje_model->grillaResumen();
        echo json_encode($resultado);
    }
    
    public function previewSantander(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Canje_model');
        $this->Canje_model->ordenes = $ordenes;
        $resultado = $this->Canje_model->previewSantander();
        echo json_encode($resultado);
    }
    
    public function enviarSantander(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Canje_model');
        $this->Canje_model->ordenes = $ordenes;
        $resultado = $this->Canje_model->enviarSantander();
        echo json_encode($resultado);
    }
    
    public function previewMercado(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Canje_model');
        $this->Canje_model->ordenes = $ordenes;
        $resultado = $this->Canje_model->previewMercado();
        echo json_encode($resultado);
    }

    public function enviarMercado(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Canje_model');
        $this->Canje_model->ordenes = $ordenes;
        $resultado = $this->Canje_model->enviarMercado();
        echo json_encode($resultado);
    }
 
    
    
    public function grabarExcel(){
        
        $archivo = $this->input->post('file');
        $cierre = $this->input->post('cierre');
        
        $this->load->model('Canje_model');
        $this->Canje_model->archivo = $archivo;
        $this->Canje_model->cierre = $cierre;
        
        $resultado = $this->Canje_model->grabarExcel();
        echo json_encode($resultado);
        
    }

    
}
