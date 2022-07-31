<?php
class Canjelocal extends MY_AuthController {
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('canjelocal/grilla');
        $this->load->view('canjelocal/pie');
        $this->load->view('template/pie');
    }
    
    public function editar(){
        $datos['id'] = $this->input->post('id');
        $datos['origen'] = $this->input->post('origen');
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('canjelocal/editar', $datos);
        $this->load->view('template/pie');
    }
    
    public function saveOrden(){
        $this->load->model('Canjelocal_model');
        $this->Canjelocal_model->id = $this->input->post('id');
        $this->Canjelocal_model->numComitente = $this->input->post('numComitente');
        
//        $this->Canjelocal_model->comision = $this->input->post('comision');
        $this->Canjelocal_model->cantidad = $this->input->post('cantidad');
        $this->Canjelocal_model->plazo = $this->input->post('plazo');
        $this->Canjelocal_model->arancel = $this->input->post('arancel');

        $this->Canjelocal_model->bono = $this->input->post('bono');
       
        $this->Canjelocal_model->bonoNombre = $this->input->post('bonoNombre');
        $this->Canjelocal_model->tipo = $this->input->post('tipo');
//        $this->Canjelocal_model->cantidadACrecer = $this->input->post('cantidadACrecer');
//        $this->Canjelocal_model->precio = $this->input->post('precio');
//        $this->Canjelocal_model->segundaParte = $this->input->post('segundaParte');
//        $this->Canjelocal_model->cantidadAcrecerSegunda = $this->input->post('cantidadAcrecerSegunda');
        $this->Canjelocal_model->comitente = $this->input->post('comitente');
        $this->Canjelocal_model->tipoPersona = $this->input->post('tipoPersona');
        $this->Canjelocal_model->oficial = $this->input->post('oficial');
        $this->Canjelocal_model->cuit = $this->input->post('cuit');
        $this->Canjelocal_model->posicion = $this->input->post('posicion');
        $this->Canjelocal_model->estaConfirmado = $this->input->post('estaConfirmado');
        $orden = $this->Canjelocal_model->saveOrden();
        echo json_encode($orden);
    }
    
    public function comprobarEstadoCierre(){
        $cierre = $this->input->post('cierre');
        $this->load->model('Canjelocal_model');
        $this->Canjelocal_model->cierre = $cierre;
        $resultado = $this->Canjelocal_model->comprobarEstadoCierre();
        echo json_encode($resultado);
    }
    
    
    public function getPlazos(){
        $this->load->model('Canjelocal_model');
        $this->Canjelocal_model->cierrecanjelocal_id = $this->input->post('cierrecanjelocal_id');
//        $this->Canjelocal_model->moneda = $this->input->post('moneda');
        $resultado = $this->Canjelocal_model->getPlazos();
        echo json_encode($resultado);
    }
    
    public function getEspecie(){
        $this->load->model('Canjelocal_model');
        $this->Canjelocal_model->plazo = $this->input->post('plazo');
        $resultado = $this->Canjelocal_model->getEspecie();
        echo json_encode($resultado);
    }
    
    public function getPlazosEspecies(){
        $this->load->model('Canjelocal_model');
        $this->Canjelocal_model->cierrecanjelocal_id = $this->input->post('cierrecanjelocal_id');
//        $this->Canjelocal_model->moneda = $this->input->post('moneda');
        $resultado = $this->Canjelocal_model->getPlazosEspecies();
        echo json_encode($resultado);
    }
    
    public function getPlazosBono(){
        $this->load->model('Canjelocal_model');
        $this->Canjelocal_model->plazocanjelocal_id = $this->input->post('plazocanjelocal_id');
        $resultado = $this->Canjelocal_model->getPlazosBono();
        echo json_encode($resultado);
    }
    
    public function getOrden(){
        $this->load->model('Canjelocal_model');
        $this->Canjelocal_model->id = $this->input->post('id');
        $orden = $this->Canjelocal_model->getOrden();
        echo json_encode($orden);
    }
    
    public function delOrden(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Canjelocal_model');
        $this->Canjelocal_model->ordenes = $ordenes;
        $this->Canjelocal_model->delOrden();
        echo json_encode(array('resultado'=>'Ordenes borradas exitosamente'));
    }
    
    public function grilla(){
        
        
        $usuario = $this->session->userdata('usuario');
        $usuario_id = $usuario['id'];
        
        $cierre_id = $this->input->post('cierrecanjelocal_id');
        
                
        $this->load->model('Canjelocal_model');
        $this->Canjelocal_model->usuario_id = $usuario_id;
        $this->Canjelocal_model->cierrecanjelocal_id = $cierre_id;
        $resultado = $this->Canjelocal_model->grilla();
        
        echo json_encode($resultado);

        
    }
    
    public function enviarOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Canjelocal_model');
        $this->Canjelocal_model->ordenes = $ordenes;
        $resultado = $this->Canjelocal_model->enviarOrdenes();
        echo json_encode($resultado);
    }
    
    public function anularOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Canjelocal_model');
        $this->Canjelocal_model->ordenes = $ordenes;
        $resultado = $this->Canjelocal_model->anularOrdenes();
        echo json_encode($resultado);        
    }
    
    public function cierreEditar(){
        $data['id'] = $this->input->post('id');
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('canjelocal/cierreEditar', $data);
        $this->load->view('template/pie');
    }
    
    public function getCierre(){
        $cierre_id = $this->input->post('cierrecanjelocal_id');
        $this->load->model('Canjelocal_model');
        $this->Canjelocal_model->cierrecanjelocal_id = $cierre_id;
        $resultado = $this->Canjelocal_model->getCierre();
        echo json_encode($resultado);
    }
    
    public function saveCierre(){
        $cierrecanjelocal_id = $this->input->post('cierrecanjelocal_id');
        $fechaHora = $this->input->post('fechahora');
        $plazos = $this->input->post('plazos');
        $plazosBorrar = $this->input->post('plazosBorrar');
        $pausarCierre = $this->input->post('pausarCierre');
//        $instrumento = $this->input->post('instrumento');
        $this->load->model('Canjelocal_model');
        $this->Canjelocal_model->cierrecanjelocal_id = $cierrecanjelocal_id;
        $this->Canjelocal_model->fechahora = $fechaHora;
        $this->Canjelocal_model->plazos = $plazos;
        $this->Canjelocal_model->plazosBorrar = $plazosBorrar;
        $this->Canjelocal_model->pausarCierre = $pausarCierre;
//        $this->Canjelocal_model->instrumento = $instrumento;
        $cierre = $this->Canjelocal_model->saveCierre();
        echo json_encode($cierre);
    }
    
    public function delCierre(){
        $cierrecanjelocal_id = $this->input->post('id');
        $this->load->model('Canjelocal_model');
        $this->Canjelocal_model->cierrecanjelocal_id = $cierrecanjelocal_id;
        $this->Canjelocal_model->delCierre();
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
        $table = "(select * from cierrecanjelocal order by fechahora desc) as cierre";
        $fields = array('id','fechahora');
        $datos = $this->grilla_model->datosGrilla($table, $fields, $pagenum, $pagesize, 
                $filterscount, $filtervalue, $filtercondition, $filterdatafield, 
                $filteroperator, $sortdatafield, $sortorder);
        echo json_encode($datos);
    }

    public function cierre(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('canjelocal/cierreGrilla');
        $this->load->view('template/pie');
    }
    
    public function getCierreActual(){
        $this->load->model('Canjelocal_model');
        $cierreActual = $this->Canjelocal_model->getCierreActual();
        echo json_encode($cierreActual);
    }

    public function procesar(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('canjelocal/procesar');
        $this->load->view('template/pie');
    }
    
    public function procesarGrilla(){
        
        $cierre_id = $this->input->post('cierrecanjelocal_id');
        
        $this->load->model('Canjelocal_model');
        $this->Canjelocal_model->cierrecanjelocal_id = $cierre_id;
        $resultado = $this->Canjelocal_model->procesarGrilla();
        
        echo json_encode($resultado);
    }
    
    public function getCierres(){
        $this->load->model('Canjelocal_model');
        $cierres = $this->Canjelocal_model->getCierres();
        echo json_encode($cierres);
    }
    
    public function grillaResumen(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Canjelocal_model');
        $this->Canjelocal_model->ordenes = $ordenes;
        $resultado = $this->Canjelocal_model->grillaResumen();
        echo json_encode($resultado);
    }
    
    public function previewSantander(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Canjelocal_model');
        $this->Canjelocal_model->ordenes = $ordenes;
        $resultado = $this->Canjelocal_model->previewSantander();
        echo json_encode($resultado);
    }
    
    public function enviarSantander(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Canjelocal_model');
        $this->Canjelocal_model->ordenes = $ordenes;
        $resultado = $this->Canjelocal_model->enviarSantander();
        echo json_encode($resultado);
    }
    
    public function previewMercado(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Canjelocal_model');
        $this->Canjelocal_model->ordenes = $ordenes;
        $resultado = $this->Canjelocal_model->previewMercado();
        echo json_encode($resultado);
    }

    public function enviarMercado(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Canjelocal_model');
        $this->Canjelocal_model->ordenes = $ordenes;
        $resultado = $this->Canjelocal_model->enviarMercado();
        echo json_encode($resultado);
    }
    
    public function previewArchivo(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Canjelocal_model');
        $this->Canjelocal_model->ordenes = $ordenes;
        $resultado = $this->Canjelocal_model->previewArchivo();
        echo json_encode($resultado);
    }
    
    public function getDescargarAchivo(){
        $logName = $this->input->post('archivo');
        $this->load->helper('download');
        $contenido = file_get_contents("/var/www/ordenes/application/downloads/" . $logName);
        force_download($logName, $contenido);
    }

    public function enviarArchivo(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Canjelocal_model');
        $this->Canjelocal_model->ordenes = $ordenes;
        $resultado = $this->Canjelocal_model->enviarArchivo();
        echo json_encode($resultado);
    }
    
    
    public function procesarExcel(){
        
        $archivo = $this->input->post('file');
        $cierre = $this->input->post('cierre');
        
        $this->load->model('Canjelocal_model');
        $this->Canjelocal_model->archivo = $archivo;
        $this->Canjelocal_model->cierre = $cierre;
        
        $resultado = $this->Canjelocal_model->procesarExcel();
        echo json_encode($resultado);
        
    }
    
    public function grabarExcel(){
        
        $archivo = $this->input->post('file');
        $cierre = $this->input->post('cierre');

        
        $this->load->model('Canjelocal_model');
        $this->Canjelocal_model->archivo = $archivo;
        $this->Canjelocal_model->cierre = $cierre;
        
        $resultado = $this->Canjelocal_model->grabarExcel();
        echo json_encode($resultado);
        
    }
    
}
