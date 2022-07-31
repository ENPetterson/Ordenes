<?php
class Canjelocaltardio extends MY_AuthController {
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('canjelocaltardio/grilla');
        $this->load->view('canjelocaltardio/pie');
        $this->load->view('template/pie');
    }
    
    public function editar(){
        $datos['id'] = $this->input->post('id');
        $datos['origen'] = $this->input->post('origen');
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('canjelocaltardio/editar', $datos);
        $this->load->view('template/pie');
    }
    
    public function saveOrden(){
        $this->load->model('Canjelocaltardio_model');
        $this->Canjelocaltardio_model->id = $this->input->post('id');
        $this->Canjelocaltardio_model->numComitente = $this->input->post('numComitente');
        
//        $this->Canjelocaltardio_model->comision = $this->input->post('comision');
        $this->Canjelocaltardio_model->cantidad = $this->input->post('cantidad');
        $this->Canjelocaltardio_model->plazo = $this->input->post('plazo');
        $this->Canjelocaltardio_model->arancel = $this->input->post('arancel');

        $this->Canjelocaltardio_model->bono = $this->input->post('bono');
       
        $this->Canjelocaltardio_model->bonoNombre = $this->input->post('bonoNombre');
        $this->Canjelocaltardio_model->tipo = $this->input->post('tipo');
//        $this->Canjelocaltardio_model->cantidadACrecer = $this->input->post('cantidadACrecer');
//        $this->Canjelocaltardio_model->precio = $this->input->post('precio');
//        $this->Canjelocaltardio_model->segundaParte = $this->input->post('segundaParte');
//        $this->Canjelocaltardio_model->cantidadAcrecerSegunda = $this->input->post('cantidadAcrecerSegunda');
        $this->Canjelocaltardio_model->comitente = $this->input->post('comitente');
        $this->Canjelocaltardio_model->tipoPersona = $this->input->post('tipoPersona');
        $this->Canjelocaltardio_model->oficial = $this->input->post('oficial');
        $this->Canjelocaltardio_model->cuit = $this->input->post('cuit');
        $this->Canjelocaltardio_model->posicion = $this->input->post('posicion');
        $this->Canjelocaltardio_model->estaConfirmado = $this->input->post('estaConfirmado');
        $orden = $this->Canjelocaltardio_model->saveOrden();
        echo json_encode($orden);
    }
    
    public function comprobarEstadoCierre(){
        $cierre = $this->input->post('cierre');
        $this->load->model('Canjelocaltardio_model');
        $this->Canjelocaltardio_model->cierre = $cierre;
        $resultado = $this->Canjelocaltardio_model->comprobarEstadoCierre();
        echo json_encode($resultado);
    }
    
    
    public function getPlazos(){
        $this->load->model('Canjelocaltardio_model');
        $this->Canjelocaltardio_model->cierrecanjelocaltardio_id = $this->input->post('cierrecanjelocaltardio_id');
//        $this->Canjelocaltardio_model->moneda = $this->input->post('moneda');
        $resultado = $this->Canjelocaltardio_model->getPlazos();
        echo json_encode($resultado);
    }
    
    public function getEspecie(){
        $this->load->model('Canjelocaltardio_model');
        $this->Canjelocaltardio_model->plazo = $this->input->post('plazo');
        $resultado = $this->Canjelocaltardio_model->getEspecie();
        echo json_encode($resultado);
    }
    
    public function getPlazosEspecies(){
        $this->load->model('Canjelocaltardio_model');
        $this->Canjelocaltardio_model->cierrecanjelocaltardio_id = $this->input->post('cierrecanjelocaltardio_id');
//        $this->Canjelocaltardio_model->moneda = $this->input->post('moneda');
        $resultado = $this->Canjelocaltardio_model->getPlazosEspecies();
        echo json_encode($resultado);
    }
    
    public function getPlazosBono(){
        $this->load->model('Canjelocaltardio_model');
        $this->Canjelocaltardio_model->plazocanjelocaltardio_id = $this->input->post('plazocanjelocaltardio_id');
        $resultado = $this->Canjelocaltardio_model->getPlazosBono();
        echo json_encode($resultado);
    }
    
    public function getOrden(){
        $this->load->model('Canjelocaltardio_model');
        $this->Canjelocaltardio_model->id = $this->input->post('id');
        $orden = $this->Canjelocaltardio_model->getOrden();
        echo json_encode($orden);
    }
    
    public function delOrden(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Canjelocaltardio_model');
        $this->Canjelocaltardio_model->ordenes = $ordenes;
        $this->Canjelocaltardio_model->delOrden();
        echo json_encode(array('resultado'=>'Ordenes borradas exitosamente'));
    }
    
    public function grilla(){
        
        
        $usuario = $this->session->userdata('usuario');
        $usuario_id = $usuario['id'];
        
        $cierre_id = $this->input->post('cierrecanjelocaltardio_id');
        
                
        $this->load->model('Canjelocaltardio_model');
        $this->Canjelocaltardio_model->usuario_id = $usuario_id;
        $this->Canjelocaltardio_model->cierrecanjelocaltardio_id = $cierre_id;
        $resultado = $this->Canjelocaltardio_model->grilla();
        
        echo json_encode($resultado);

        
    }
    
    public function enviarOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Canjelocaltardio_model');
        $this->Canjelocaltardio_model->ordenes = $ordenes;
        $resultado = $this->Canjelocaltardio_model->enviarOrdenes();
        echo json_encode($resultado);
    }
    
    public function anularOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Canjelocaltardio_model');
        $this->Canjelocaltardio_model->ordenes = $ordenes;
        $resultado = $this->Canjelocaltardio_model->anularOrdenes();
        echo json_encode($resultado);        
    }
    
    
    public function procesarExcelAchivoDescargado(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Canjelocaltardio_model');
        $this->Canjelocaltardio_model->ordenes = $ordenes;
        $resultado = $this->Canjelocaltardio_model->procesarExcelAchivoDescargado();
        echo json_encode($resultado);        
    }
    
    
    public function cierreEditar(){
        $data['id'] = $this->input->post('id');
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('canjelocaltardio/cierreEditar', $data);
        $this->load->view('template/pie');
    }
    
    public function getCierre(){
        $cierre_id = $this->input->post('cierrecanjelocaltardio_id');
        $this->load->model('Canjelocaltardio_model');
        $this->Canjelocaltardio_model->cierrecanjelocaltardio_id = $cierre_id;
        $resultado = $this->Canjelocaltardio_model->getCierre();
        echo json_encode($resultado);
    }
    
    public function saveCierre(){
        $cierrecanjelocaltardio_id = $this->input->post('cierrecanjelocaltardio_id');
        $fechaHora = $this->input->post('fechahora');
        $plazos = $this->input->post('plazos');
        $plazosBorrar = $this->input->post('plazosBorrar');
        $pausarCierre = $this->input->post('pausarCierre');
//        $instrumento = $this->input->post('instrumento');
        $this->load->model('Canjelocaltardio_model');
        $this->Canjelocaltardio_model->cierrecanjelocaltardio_id = $cierrecanjelocaltardio_id;
        $this->Canjelocaltardio_model->fechahora = $fechaHora;
        $this->Canjelocaltardio_model->plazos = $plazos;
        $this->Canjelocaltardio_model->plazosBorrar = $plazosBorrar;
        $this->Canjelocaltardio_model->pausarCierre = $pausarCierre;
//        $this->Canjelocaltardio_model->instrumento = $instrumento;
        $cierre = $this->Canjelocaltardio_model->saveCierre();
        echo json_encode($cierre);
    }
    
    public function delCierre(){
        $cierrecanjelocaltardio_id = $this->input->post('id');
        $this->load->model('Canjelocaltardio_model');
        $this->Canjelocaltardio_model->cierrecanjelocaltardio_id = $cierrecanjelocaltardio_id;
        $this->Canjelocaltardio_model->delCierre();
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
        $table = "(select * from cierrecanjelocaltardio order by fechahora desc) as cierre";
        $fields = array('id','fechahora');
        $datos = $this->grilla_model->datosGrilla($table, $fields, $pagenum, $pagesize, 
                $filterscount, $filtervalue, $filtercondition, $filterdatafield, 
                $filteroperator, $sortdatafield, $sortorder);
        echo json_encode($datos);
    }

    public function cierre(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('canjelocaltardio/cierreGrilla');
        $this->load->view('template/pie');
    }
    
    public function getCierreActual(){
        $this->load->model('Canjelocaltardio_model');
        $cierreActual = $this->Canjelocaltardio_model->getCierreActual();
        echo json_encode($cierreActual);
    }

    public function procesar(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('canjelocaltardio/procesar');
        $this->load->view('template/pie');
    }
    
    public function procesarGrilla(){
        
        $cierre_id = $this->input->post('cierrecanjelocaltardio_id');
        
        $this->load->model('Canjelocaltardio_model');
        $this->Canjelocaltardio_model->cierrecanjelocaltardio_id = $cierre_id;
        $resultado = $this->Canjelocaltardio_model->procesarGrilla();
        
        echo json_encode($resultado);
    }
    
    public function getCierres(){
        $this->load->model('Canjelocaltardio_model');
        $cierres = $this->Canjelocaltardio_model->getCierres();
        echo json_encode($cierres);
    }
    
    public function grillaResumen(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Canjelocaltardio_model');
        $this->Canjelocaltardio_model->ordenes = $ordenes;
        $resultado = $this->Canjelocaltardio_model->grillaResumen();
        echo json_encode($resultado);
    }
    
    public function previewSantander(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Canjelocaltardio_model');
        $this->Canjelocaltardio_model->ordenes = $ordenes;
        $resultado = $this->Canjelocaltardio_model->previewSantander();
        echo json_encode($resultado);
    }
    
    public function enviarSantander(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Canjelocaltardio_model');
        $this->Canjelocaltardio_model->ordenes = $ordenes;
        $resultado = $this->Canjelocaltardio_model->enviarSantander();
        echo json_encode($resultado);
    }
    
    public function previewMercado(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Canjelocaltardio_model');
        $this->Canjelocaltardio_model->ordenes = $ordenes;
        $resultado = $this->Canjelocaltardio_model->previewMercado();
        echo json_encode($resultado);
    }

    public function enviarMercado(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Canjelocaltardio_model');
        $this->Canjelocaltardio_model->ordenes = $ordenes;
        $resultado = $this->Canjelocaltardio_model->enviarMercado();
        echo json_encode($resultado);
    }
    
    public function previewArchivo(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Canjelocaltardio_model');
        $this->Canjelocaltardio_model->ordenes = $ordenes;
        $resultado = $this->Canjelocaltardio_model->previewArchivo();
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
        $this->load->model('Canjelocaltardio_model');
        $this->Canjelocaltardio_model->ordenes = $ordenes;
        $resultado = $this->Canjelocaltardio_model->enviarArchivo();
        echo json_encode($resultado);
    }
    
    
    public function procesarExcel(){
        
        $archivo = $this->input->post('file');
        $cierre = $this->input->post('cierre');
        
        $this->load->model('Canjelocaltardio_model');
        $this->Canjelocaltardio_model->archivo = $archivo;
        $this->Canjelocaltardio_model->cierre = $cierre;
        
        $resultado = $this->Canjelocaltardio_model->procesarExcel();
        echo json_encode($resultado);
        
    }
    
    public function grabarExcel(){
        
        $archivo = $this->input->post('file');
        $cierre = $this->input->post('cierre');

        
        $this->load->model('Canjelocaltardio_model');
        $this->Canjelocaltardio_model->archivo = $archivo;
        $this->Canjelocaltardio_model->cierre = $cierre;
        
        $resultado = $this->Canjelocaltardio_model->grabarExcel();
        echo json_encode($resultado);
        
    }
    
}
