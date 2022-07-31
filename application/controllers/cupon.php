<?php
class Cupon extends MY_AuthController {
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('cupon/grilla');
        $this->load->view('cupon/pie');
        $this->load->view('template/pie');
    }
    
    public function editar(){
        $datos['id'] = $this->input->post('id');
        $datos['origen'] = $this->input->post('origen');
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('cupon/editar', $datos);
        $this->load->view('template/pie');
    }
    
    public function saveOrden(){
        $this->load->model('Cupon_model');
        $this->Cupon_model->id = $this->input->post('id');
        $this->Cupon_model->numComitente = $this->input->post('numComitente');
        
//        $this->Cupon_model->comision = $this->input->post('comision');
        $this->Cupon_model->cantidad = $this->input->post('cantidad');
        $this->Cupon_model->plazo = $this->input->post('plazo');
        $this->Cupon_model->arancel = $this->input->post('arancel');

        $this->Cupon_model->bono = $this->input->post('bono');
       
        $this->Cupon_model->bonoNombre = $this->input->post('bonoNombre');
        $this->Cupon_model->tipo = $this->input->post('tipo');
//        $this->Cupon_model->cantidadACrecer = $this->input->post('cantidadACrecer');
//        $this->Cupon_model->precio = $this->input->post('precio');
//        $this->Cupon_model->segundaParte = $this->input->post('segundaParte');
//        $this->Cupon_model->cantidadAcrecerSegunda = $this->input->post('cantidadAcrecerSegunda');
        $this->Cupon_model->comitente = $this->input->post('comitente');
        $this->Cupon_model->tipoPersona = $this->input->post('tipoPersona');
        $this->Cupon_model->oficial = $this->input->post('oficial');
        $this->Cupon_model->cuit = $this->input->post('cuit');
        $this->Cupon_model->posicion = $this->input->post('posicion');
        $this->Cupon_model->estaConfirmado = $this->input->post('estaConfirmado');
        $orden = $this->Cupon_model->saveOrden();
        echo json_encode($orden);
    }
    
    public function comprobarEstadoCierre(){
        $cierre = $this->input->post('cierre');
        $this->load->model('Cupon_model');
        $this->Cupon_model->cierre = $cierre;
        $resultado = $this->Cupon_model->comprobarEstadoCierre();
        echo json_encode($resultado);
    }
    
    
    public function getPlazos(){
        $this->load->model('Cupon_model');
        $this->Cupon_model->cierrecupon_id = $this->input->post('cierrecupon_id');
//        $this->Cupon_model->moneda = $this->input->post('moneda');
        $resultado = $this->Cupon_model->getPlazos();
        echo json_encode($resultado);
    }
    
    public function getEspecie(){
        $this->load->model('Cupon_model');
        $this->Cupon_model->plazo = $this->input->post('plazo');
        $resultado = $this->Cupon_model->getEspecie();
        echo json_encode($resultado);
    }
    
    public function getPlazosEspecies(){
        $this->load->model('Cupon_model');
        $this->Cupon_model->cierrecupon_id = $this->input->post('cierrecupon_id');
//        $this->Cupon_model->moneda = $this->input->post('moneda');
        $resultado = $this->Cupon_model->getPlazosEspecies();
        echo json_encode($resultado);
    }
    
    public function getPlazosBono(){
        $this->load->model('Cupon_model');
        $this->Cupon_model->plazocupon_id = $this->input->post('plazocupon_id');
        $resultado = $this->Cupon_model->getPlazosBono();
        echo json_encode($resultado);
    }
    
    public function getOrden(){
        $this->load->model('Cupon_model');
        $this->Cupon_model->id = $this->input->post('id');
        $orden = $this->Cupon_model->getOrden();
        echo json_encode($orden);
    }
    
    public function delOrden(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Cupon_model');
        $this->Cupon_model->ordenes = $ordenes;
        $this->Cupon_model->delOrden();
        echo json_encode(array('resultado'=>'Ordenes borradas exitosamente'));
    }
    
    public function grilla(){
        
        
        $usuario = $this->session->userdata('usuario');
        $usuario_id = $usuario['id'];
        
        $cierre_id = $this->input->post('cierrecupon_id');
        
                
        $this->load->model('Cupon_model');
        $this->Cupon_model->usuario_id = $usuario_id;
        $this->Cupon_model->cierrecupon_id = $cierre_id;
        $resultado = $this->Cupon_model->grilla();
        
        echo json_encode($resultado);

        
    }
    
    public function enviarOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Cupon_model');
        $this->Cupon_model->ordenes = $ordenes;
        $resultado = $this->Cupon_model->enviarOrdenes();
        echo json_encode($resultado);
    }
    
    public function anularOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Cupon_model');
        $this->Cupon_model->ordenes = $ordenes;
        $resultado = $this->Cupon_model->anularOrdenes();
        echo json_encode($resultado);        
    }
    
    public function cierreEditar(){
        $data['id'] = $this->input->post('id');
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('cupon/cierreEditar', $data);
        $this->load->view('template/pie');
    }
    
    public function getCierre(){
        $cierre_id = $this->input->post('cierrecupon_id');
        $this->load->model('Cupon_model');
        $this->Cupon_model->cierrecupon_id = $cierre_id;
        $resultado = $this->Cupon_model->getCierre();
        echo json_encode($resultado);
    }
    
    public function saveCierre(){
        $cierrecupon_id = $this->input->post('cierrecupon_id');
        $fechaHora = $this->input->post('fechahora');
        $plazos = $this->input->post('plazos');
        $plazosBorrar = $this->input->post('plazosBorrar');
        $pausarCierre = $this->input->post('pausarCierre');
//        $instrumento = $this->input->post('instrumento');
        $this->load->model('Cupon_model');
        $this->Cupon_model->cierrecupon_id = $cierrecupon_id;
        $this->Cupon_model->fechahora = $fechaHora;
        $this->Cupon_model->plazos = $plazos;
        $this->Cupon_model->plazosBorrar = $plazosBorrar;
        $this->Cupon_model->pausarCierre = $pausarCierre;
//        $this->Cupon_model->instrumento = $instrumento;
        $cierre = $this->Cupon_model->saveCierre();
        echo json_encode($cierre);
    }
    
    public function delCierre(){
        $cierrecupon_id = $this->input->post('id');
        $this->load->model('Cupon_model');
        $this->Cupon_model->cierrecupon_id = $cierrecupon_id;
        $this->Cupon_model->delCierre();
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
        $table = "(select * from cierrecupon order by fechahora desc) as cierre";
        $fields = array('id','fechahora');
        $datos = $this->grilla_model->datosGrilla($table, $fields, $pagenum, $pagesize, 
                $filterscount, $filtervalue, $filtercondition, $filterdatafield, 
                $filteroperator, $sortdatafield, $sortorder);
        echo json_encode($datos);
    }

    public function cierre(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('cupon/cierreGrilla');
        $this->load->view('template/pie');
    }
    
    public function getCierreActual(){
        $this->load->model('Cupon_model');
        $cierreActual = $this->Cupon_model->getCierreActual();
        echo json_encode($cierreActual);
    }

    public function procesar(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('cupon/procesar');
        $this->load->view('template/pie');
    }
    
    public function procesarGrilla(){
        
        $cierre_id = $this->input->post('cierrecupon_id');
        
        $this->load->model('Cupon_model');
        $this->Cupon_model->cierrecupon_id = $cierre_id;
        $resultado = $this->Cupon_model->procesarGrilla();
        
        echo json_encode($resultado);
    }
    
    public function getCierres(){
        $this->load->model('Cupon_model');
        $cierres = $this->Cupon_model->getCierres();
        echo json_encode($cierres);
    }
    
    public function grillaResumen(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Cupon_model');
        $this->Cupon_model->ordenes = $ordenes;
        $resultado = $this->Cupon_model->grillaResumen();
        echo json_encode($resultado);
    }
    
    public function previewSantander(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Cupon_model');
        $this->Cupon_model->ordenes = $ordenes;
        $resultado = $this->Cupon_model->previewSantander();
        echo json_encode($resultado);
    }
    
    public function enviarSantander(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Cupon_model');
        $this->Cupon_model->ordenes = $ordenes;
        $resultado = $this->Cupon_model->enviarSantander();
        echo json_encode($resultado);
    }
    
    public function previewMercado(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Cupon_model');
        $this->Cupon_model->ordenes = $ordenes;
        $resultado = $this->Cupon_model->previewMercado();
        echo json_encode($resultado);
    }

    public function enviarMercado(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Cupon_model');
        $this->Cupon_model->ordenes = $ordenes;
        $resultado = $this->Cupon_model->enviarMercado();
        echo json_encode($resultado);
    }
    
    public function grabarExcel(){
        
        $archivo = $this->input->post('file');
        $cierre = $this->input->post('cierre');

        
        $this->load->model('Cupon_model');
        $this->Cupon_model->archivo = $archivo;
        $this->Cupon_model->cierre = $cierre;
        
        $resultado = $this->Cupon_model->grabarExcel();
        echo json_encode($resultado);
        
    }
    
}
