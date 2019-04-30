<?php
class Bono extends MY_AuthController {
    public function __construct() {
        parent::__construct();
    }
    //Test
    public function index(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('bono/grilla');
        $this->load->view('bono/pie');
        $this->load->view('template/pie');
    }
    
    public function editar(){
        $datos['id'] = $this->input->post('id');
        $datos['origen'] = $this->input->post('origen');
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('bono/editar', $datos);
        $this->load->view('template/pie');
    }
    
    public function saveOrden(){
        $this->load->model('Bono_model');
        $this->Bono_model->id = $this->input->post('id');
        $this->Bono_model->tramo = $this->input->post('tramo');
        $this->Bono_model->numComitente = $this->input->post('numComitente');
        $this->Bono_model->tipoInversor = $this->input->post('tipoInversor');
        $this->Bono_model->moneda = $this->input->post('moneda');
        $this->Bono_model->cable = $this->input->post('cable');
        /*
        $this->Bono_model->plazo = $this->input->post('plazo');
         * 
         */
        $this->Bono_model->comision = $this->input->post('comision');
        $this->Bono_model->cantidad = $this->input->post('cantidad');
        $this->Bono_model->precio = $this->input->post('precio');
        $this->Bono_model->comitente = $this->input->post('comitente');
        $this->Bono_model->tipoPersona = $this->input->post('tipoPersona');
        $this->Bono_model->oficial = $this->input->post('oficial');
        $this->Bono_model->cuit = $this->input->post('cuit');
        $orden = $this->Bono_model->saveOrden();
        echo json_encode($orden);
    }
    
    public function getOrden(){
        $this->load->model('Bono_model');
        $this->Bono_model->id = $this->input->post('id');
        $orden = $this->Bono_model->getOrden();
        echo json_encode($orden);
    }
    
    public function delOrden(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Bono_model');
        $this->Bono_model->ordenes = $ordenes;
        $this->Bono_model->delOrden();
        echo json_encode(array('resultado'=>'Ordenes borradas exitosamente'));
    }
    
    public function grilla(){
        
        
        $usuario = $this->session->userdata('usuario');
        $usuario_id = $usuario['id'];
        
        $cierre_id = $this->input->post('cierrebono_id');
        
                
        $this->load->model('Bono_model');
        $this->Bono_model->usuario_id = $usuario_id;
        $this->Bono_model->cierrebono_id = $cierre_id;
        $resultado = $this->Bono_model->grilla();
        
        echo json_encode($resultado);

        
    }
    
    public function enviarOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Bono_model');
        $this->Bono_model->ordenes = $ordenes;
        $resultado = $this->Bono_model->enviarOrdenes();
        echo json_encode($resultado);
    }
    
    public function anularOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Bono_model');
        $this->Bono_model->ordenes = $ordenes;
        $resultado = $this->Bono_model->anularOrdenes();
        echo json_encode($resultado);        
    }
    
    public function cierreEditar(){
        $data['id'] = $this->input->post('id');
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('bono/cierreEditar', $data);
        $this->load->view('template/pie');
    }
    
    public function getCierre(){
        $cierre_id = $this->input->post('cierrebono_id');
        $this->load->model('Bono_model');
        $this->Bono_model->cierrebono_id = $cierre_id;
        $resultado = $this->Bono_model->getCierre();
        echo json_encode($resultado);
    }
    
    public function getPlazos(){
        $this->load->model('Bono_model');
        $this->Bono_model->cierrebono_id = $this->input->post('cierrebono_id');
        $this->Bono_model->moneda = $this->input->post('moneda');
        $resultado = $this->Bono_model->getPlazos();
        echo json_encode($resultado);
    }
    
    public function getMonedas(){
        $this->load->model('Bono_model');
        $this->Bono_model->cierrebono_id = $this->input->post('cierrebono_id');
        $resultado = $this->Bono_model->getMonedas();
        echo json_encode($resultado);
    }
    
    public function saveCierre(){
        $cierre_id = $this->input->post('cierrebono_id');
        $fechaHora = $this->input->post('fechahora');
        $plazos = $this->input->post('plazos');
        $plazosBorrar = $this->input->post('plazosBorrar');
        $this->load->model('Bono_model');
        $this->Bono_model->cierrebono_id = $cierre_id;
        $this->Bono_model->fechahora = $fechaHora;
        $this->Bono_model->plazos = $plazos;
        $this->Bono_model->plazosBorrar = $plazosBorrar;
        $cierre = $this->Bono_model->saveCierre();
        echo json_encode($cierre);
    }
    
    public function delCierre(){
        $cierre_id = $this->input->post('id');
        $this->load->model('Bono_model');
        $this->Bono_model->cierrebono_id = $cierre_id;
        $this->Bono_model->delCierre();
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
        $table = "(select * from cierrebono order by fechahora desc) as cierre";
        $fields = array('id','fechahora');
        $datos = $this->grilla_model->datosGrilla($table, $fields, $pagenum, $pagesize, 
                $filterscount, $filtervalue, $filtercondition, $filterdatafield, 
                $filteroperator, $sortdatafield, $sortorder);
        echo json_encode($datos);
    }

    public function cierre(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('bono/cierreGrilla');
        $this->load->view('template/pie');
    }
    
    public function getCierreActual(){
        $this->load->model('Bono_model');
        $cierreActual = $this->Bono_model->getCierreActual();
        echo json_encode($cierreActual);
    }

    public function procesar(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('bono/procesar');
        $this->load->view('template/pie');
    }
    
    public function procesarGrilla(){
        
        $cierre_id = $this->input->post('cierrebono_id');
        
        $this->load->model('Bono_model');
        $this->Bono_model->cierrebono_id = $cierre_id;
        $resultado = $this->Bono_model->procesarGrilla();
        
        echo json_encode($resultado);
    }
    
    public function getCierres(){
        $this->load->model('Bono_model');
        $cierres = $this->Bono_model->getCierres();
        echo json_encode($cierres);
    }
    
    public function grillaResumen(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Bono_model');
        $this->Bono_model->ordenes = $ordenes;
        $resultado = $this->Bono_model->grillaResumen();
        echo json_encode($resultado);
    }
    
    public function previewSantander(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Bono_model');
        $this->Bono_model->ordenes = $ordenes;
        $resultado = $this->Bono_model->previewSantander();
        echo json_encode($resultado);
    }
    
    public function enviarSantander(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Bono_model');
        $this->Bono_model->ordenes = $ordenes;
        $resultado = $this->Bono_model->enviarSantander();
        echo json_encode($resultado);
    }
    
    public function previewMercado(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Bono_model');
        $this->Bono_model->ordenes = $ordenes;
        $resultado = $this->Bono_model->previewMercado();
        echo json_encode($resultado);
    }

    public function enviarMercado(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Bono_model');
        $this->Bono_model->ordenes = $ordenes;
        $resultado = $this->Bono_model->enviarMercado();
        echo json_encode($resultado);
    }
    
}
