<?php
class Lebac extends MY_AuthController {
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('lebac/grilla');
        $this->load->view('lebac/pie');
        $this->load->view('template/pie');
    }
    
    public function editar(){
        $datos['id'] = $this->input->post('id');
        $datos['origen'] = $this->input->post('origen');
        $datos['cierre'] = $this->input->post('cierre');
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('lebac/editar', $datos);
        $this->load->view('template/pie');
    }
    
    public function saveOrden(){
        $this->load->model('Lebac_model');
        $this->Lebac_model->id = $this->input->post('id');
        $this->Lebac_model->tramo = $this->input->post('tramo');
        $this->Lebac_model->numComitente = $this->input->post('numComitente');
        $this->Lebac_model->moneda = $this->input->post('moneda');
        $this->Lebac_model->plazo = $this->input->post('plazo');
        $this->Lebac_model->comision = $this->input->post('comision');
        $this->Lebac_model->cantidad = $this->input->post('cantidad');
        $this->Lebac_model->precio = $this->input->post('precio');
        $this->Lebac_model->comitente = $this->input->post('comitente');
        $this->Lebac_model->tipoPersona = $this->input->post('tipoPersona');
        $this->Lebac_model->oficial = $this->input->post('oficial');
        $this->Lebac_model->cuit = $this->input->post('cuit');
        $orden = $this->Lebac_model->saveOrden();
        echo json_encode($orden);
    }
    
    public function getOrden(){
        $this->load->model('Lebac_model');
        $this->Lebac_model->id = $this->input->post('id');
        $orden = $this->Lebac_model->getOrden();
        echo json_encode($orden);
    }
    
    public function delOrden(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Lebac_model');
        $this->Lebac_model->ordenes = $ordenes;
        $this->Lebac_model->delOrden();
        echo json_encode(array('resultado'=>'Ordenes borradas exitosamente'));
    }
    
    public function comprobarEstadoCierre(){
        $cierre = $this->input->post('cierre');
        $this->load->model('Lebac_model');
        $this->Lebac_model->cierre = $cierre;
        $resultado = $this->Lebac_model->comprobarEstadoCierre();
        echo json_encode($resultado);
    }
    
    
    public function grilla(){
        
        
        $usuario = $this->session->userdata('usuario');
        $usuario_id = $usuario['id'];
        
        $cierre_id = $this->input->post('cierre_id');
        
                
        $this->load->model('Lebac_model');
        $this->Lebac_model->usuario_id = $usuario_id;
        $this->Lebac_model->cierre_id = $cierre_id;
        $resultado = $this->Lebac_model->grilla();
        
        echo json_encode($resultado);

        
    }
    
    public function enviarOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Lebac_model');
        $this->Lebac_model->ordenes = $ordenes;
        $resultado = $this->Lebac_model->enviarOrdenes();
        echo json_encode($resultado);
    }
    
    public function anularOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Lebac_model');
        $this->Lebac_model->ordenes = $ordenes;
        $resultado = $this->Lebac_model->anularOrdenes();
        echo json_encode($resultado);        
    }
    
    public function cierreEditar(){
        $data['id'] = $this->input->post('id');
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('lebac/cierreEditar', $data);
        $this->load->view('template/pie');
    }
    
    public function getCierre(){
        $cierre_id = $this->input->post('cierre_id');
        $this->load->model('Lebac_model');
        $this->Lebac_model->cierre_id = $cierre_id;
        $resultado = $this->Lebac_model->getCierre();
        echo json_encode($resultado);
    }
    
    public function getPlazos(){
        $this->load->model('Lebac_model');
        $this->Lebac_model->cierre_id = $this->input->post('cierre_id');
        $this->Lebac_model->moneda = $this->input->post('moneda');
        $resultado = $this->Lebac_model->getPlazos();
        echo json_encode($resultado);
    }
    
    public function getMonedas(){
        $this->load->model('Lebac_model');
        $this->Lebac_model->cierre_id = $this->input->post('cierre_id');
        $resultado = $this->Lebac_model->getMonedas();
        echo json_encode($resultado);
    }
    
    public function saveCierre(){
        $cierre_id = $this->input->post('cierre_id');
        $fechaHora = $this->input->post('fechahora');
        $plazos = $this->input->post('plazos');
        $plazosBorrar = $this->input->post('plazosBorrar');
        $instrumento = $this->input->post('instrumento');
        $pausarCierre = $this->input->post('pausarCierre');
        $this->load->model('Lebac_model');
        $this->Lebac_model->cierre_id = $cierre_id;
        $this->Lebac_model->fechahora = $fechaHora;
        $this->Lebac_model->plazos = $plazos;
        $this->Lebac_model->plazosBorrar = $plazosBorrar;
        $this->Lebac_model->instrumento = $instrumento;
        $this->Lebac_model->pausarCierre = $pausarCierre;
        $cierre = $this->Lebac_model->saveCierre();
        echo json_encode($cierre);
    }
    
    public function delCierre(){
        $cierre_id = $this->input->post('id');
        $this->load->model('Lebac_model');
        $this->Lebac_model->cierre_id = $cierre_id;
        $this->Lebac_model->delCierre();
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
        $table = "(select * from cierre order by fechahora desc) as cierre";
        $fields = array('id','fechahora');
        $datos = $this->grilla_model->datosGrilla($table, $fields, $pagenum, $pagesize, 
                $filterscount, $filtervalue, $filtercondition, $filterdatafield, 
                $filteroperator, $sortdatafield, $sortorder);
        echo json_encode($datos);
    }

    public function cierre(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('lebac/cierreGrilla');
        $this->load->view('template/pie');
    }
    
    public function getCierreActual(){
        $this->load->model('Lebac_model');
        $cierreActual = $this->Lebac_model->getCierreActual();
        echo json_encode($cierreActual);
    }
    
    public function getPrecioMinimoPlazo(){
        
        $tramo = $this->input->post('tramo');
        $plazo = $this->input->post('plazo');   
        $cierre = $this->input->post('cierre');        
        
        $this->load->model('Lebac_model');
        
        $this->Lebac_model->tramo = $tramo;
        $this->Lebac_model->plazo = $plazo;
        $this->Lebac_model->cierre = $cierre;
        
        $minimo = $this->Lebac_model->getPrecioMinimoPlazo();
        echo json_encode($minimo);
    }
    
    

    public function procesar(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('lebac/procesar');
        $this->load->view('template/pie');
    }
    
    public function procesarGrilla(){
        
        $cierre_id = $this->input->post('cierre_id');
        $this->load->model('Lebac_model');
        /*
        if ($cierre_id > 0){
            $this->Lebac_model->actualizarPosicionMonetaria();
        }
         * 
         */
        $this->Lebac_model->cierre_id = $cierre_id;
        $resultado = $this->Lebac_model->procesarGrilla();
        
        echo json_encode($resultado);
    }
    
    public function getOrdenes(){
        $cierre_id = $this->input->post('cierre_id');
        $numComitente = $this->input->post('numComitente');
        $this->load->model('Lebac_model');
        $this->Lebac_model->cierre_id = $cierre_id;
        $this->Lebac_model->numComitente = $numComitente;
        $resultado = $this->Lebac_model->getOrdenes();
        
        echo json_encode($resultado);
    }    
    public function getCierres(){
        $this->load->model('Lebac_model');
        $cierres = $this->Lebac_model->getCierres();
        echo json_encode($cierres);
    }
    
    public function grillaResumen(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Lebac_model');
        $this->Lebac_model->ordenes = $ordenes;
        $resultado = $this->Lebac_model->grillaResumen();
        echo json_encode($resultado);
    }
    
    public function previewSantander(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Lebac_model');
        $this->Lebac_model->ordenes = $ordenes;
        $resultado = $this->Lebac_model->previewSantander();
        echo json_encode($resultado);
    }
    
    public function enviarSantander(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Lebac_model');
        $this->Lebac_model->ordenes = $ordenes;
        $resultado = $this->Lebac_model->enviarSantander();
        echo json_encode($resultado);
    }
    
    public function previewMercado(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Lebac_model');
        $this->Lebac_model->ordenes = $ordenes;
        $resultado = $this->Lebac_model->previewMercado();
        echo json_encode($resultado);
    }

    public function enviarMercado(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Lebac_model');
        $this->Lebac_model->ordenes = $ordenes;
        $resultado = $this->Lebac_model->enviarMercado();
        echo json_encode($resultado);
    }
    
    public function previewTxt(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Lebac_model');
        $this->Lebac_model->ordenes = $ordenes;
        $resultado = $this->Lebac_model->previewTxt();
        echo json_encode($resultado);
    }

    public function generarTxt(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Lebac_model');
        $this->Lebac_model->ordenes = $ordenes;
        $resultado = $this->Lebac_model->generarTxt();
        echo json_encode($resultado);
    }



    
    public function grabarExcel(){
        
        $archivo = $this->input->post('file');
        $cierre = $this->input->post('cierre');

        $this->load->model('Lebac_model');
        $this->Lebac_model->archivo = $archivo;
        $this->Lebac_model->cierre = $cierre;
        
        $resultado = $this->Lebac_model->grabarExcel();
        echo json_encode($resultado);
        
    }
    
    
    
    
    
    
    
    
    
    
}
