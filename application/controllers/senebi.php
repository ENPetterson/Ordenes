<?php
class Senebi extends MY_AuthController {
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('senebi/grilla');
        $this->load->view('senebi/pie');
        $this->load->view('template/pie');
    }
    
    public function editar(){
        $datos['id'] = $this->input->post('id');
        $datos['origen'] = $this->session->userdata['usuario']['nombre'] . " " . $this->session->userdata['usuario']['apellido'];
        $usuarioNombre = $this->session->userdata['usuario']['nombre'] . " " . $this->session->userdata['usuario']['apellido'];  
        $datos['usuario'] = $usuarioNombre;

        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('senebi/editar', $datos);
        $this->load->view('template/pie');
    }
    
    public function saveOrden(){
        $this->load->model('Senebi_model');
        $this->Senebi_model->id = $this->input->post('id');
        $this->Senebi_model->operador = $this->input->post('operador');
        $this->Senebi_model->tipoOperacion = $this->input->post('tipoOperacion');
        $this->Senebi_model->precio = $this->input->post('precio');
        $this->Senebi_model->precioContraparte = $this->input->post('precioContraparte');
        $this->Senebi_model->numeroComitente = $this->input->post('numeroComitente');
        $this->Senebi_model->especie = $this->input->post('especie');
        $this->Senebi_model->plazo = $this->input->post('plazo');
        $this->Senebi_model->moneda = $this->input->post('moneda');
        $this->Senebi_model->cantidad = $this->input->post('cantidad');
//        $this->Senebi_model->ctte = $this->input->post('ctte');
        $this->Senebi_model->brutoCliente = $this->input->post('brutoCliente');
        $this->Senebi_model->origenFondos = $this->input->post('origenFondos');
        $this->Senebi_model->deriva = $this->input->post('deriva');
        $this->Senebi_model->observaciones = $this->input->post('observaciones');
        $this->Senebi_model->ctteContraparte = $this->input->post('ctteContraparte');
        $this->Senebi_model->riesgoComitente = $this->input->post('riesgoComitente');
        $this->Senebi_model->riesgo = $this->input->post('riesgo');
        $this->Senebi_model->rangoPrecios = $this->input->post('rangoPrecios');
        $orden = $this->Senebi_model->saveOrden();
        echo json_encode($orden);
    }
    
    
    public function getOrden(){
        $this->load->model('Senebi_model');
        $this->Senebi_model->id = $this->input->post('id');
        $orden = $this->Senebi_model->getOrden();
        echo json_encode($orden);
    }
    
    public function delOrden(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Senebi_model');
        $this->Senebi_model->ordenes = $ordenes;
        $this->Senebi_model->delOrden();
        echo json_encode(array('resultado'=>'Ordenes borradas exitosamente'));
    }
    
    public function grilla(){
        
        
        $usuario = $this->session->userdata('usuario');
        $usuario_id = $usuario['id'];
        
        $cierresenebi_id = $this->input->post('cierresenebi_id');
                
        $this->load->model('Senebi_model');
        $this->Senebi_model->usuario_id = $usuario_id;
        $this->Senebi_model->cierresenebi_id = $cierresenebi_id;
        $resultado = $this->Senebi_model->grilla();
        
        echo json_encode($resultado);

        
    }
    
    public function enviarOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Senebi_model');
        $this->Senebi_model->ordenes = $ordenes;
        $resultado = $this->Senebi_model->enviarOrdenes();
        echo json_encode($resultado);
    }
    
    public function anularOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Senebi_model');
        $this->Senebi_model->ordenes = $ordenes;
        $resultado = $this->Senebi_model->anularOrdenes();
        echo json_encode($resultado);        
    }
    
    public function procesarOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Senebi_model');
        $this->Senebi_model->ordenes = $ordenes;
        $resultado = $this->Senebi_model->procesarOrdenes();
        echo json_encode($resultado);        
    }
    
    public function cierreEditar(){
        $data['id'] = $this->input->post('id');
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('senebi/cierreEditar', $data);
        $this->load->view('template/pie');
    }
    
    public function getCierre(){
        $cierresenebi_id = $this->input->post('cierresenebi_id');
        $this->load->model('Senebi_model');
        $this->Senebi_model->cierresenebi_id = $cierresenebi_id;
        $resultado = $this->Senebi_model->getCierre();
        echo json_encode($resultado);
    }
    
    public function saveCierre(){
        $cierresenebi_id = $this->input->post('cierresenebi_id');
        $fechaHora = $this->input->post('fechahora');
        $plazos = $this->input->post('plazos');
        $minimos = $this->input->post('minimos');
        $this->load->model('Senebi_model');
        $this->Senebi_model->cierresenebi_id = $cierresenebi_id;
        $this->Senebi_model->fechahora = $fechaHora;
        $this->Senebi_model->plazos = $plazos;
        $this->Senebi_model->minimos = $minimos;
//        $this->Senebi_model->colocacionDolares = $this->input->post('colocacionDolares');
        /*
        $this->Senebi_model->colocacionLebacsNov = $this->input->post('colocacionLebacsNov');
        $this->Senebi_model->colocacionLebacsDic = $this->input->post('colocacionLebacsDic');

        $this->Senebi_model->colocacionPesos = $this->input->post('colocacionPesos');
        $this->Senebi_model->colocacionA2J9 = $this->input->post('colocacionA2J9');
         * 
         */
        
        $cierre = $this->Senebi_model->saveCierre();
        echo json_encode($cierre);
    }
    
    public function delCierre(){
        $cierresenebi_id = $this->input->post('id');
        $this->load->model('Senebi_model');
        $this->Senebi_model->cierresenebi_id = $cierresenebi_id;
        $this->Senebi_model->delCierre();
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
        $table = "cierresenebi";
        $fields = array('id','fechahora','plazos');
        $datos = $this->grilla_model->datosGrilla($table, $fields, $pagenum, $pagesize, 
                $filterscount, $filtervalue, $filtercondition, $filterdatafield, 
                $filteroperator, $sortdatafield, $sortorder);
        echo json_encode($datos);
    }

    public function cierre(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('senebi/cierreGrilla');
        $this->load->view('template/pie');
    }
    
    public function getCierreActual(){
        $this->load->model('Senebi_model');
        $cierreActual = $this->Senebi_model->getCierreActual();
        echo json_encode($cierreActual);
    }

    public function procesar(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('senebi/procesar');
        $this->load->view('template/pie');
    }
    
    public function grillaGeneral(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('senebi/grillaGeneral');
        $this->load->view('template/pie');
    }
    
    public function procesarGrilla(){
        
        $cierresenebi_id = $this->input->post('cierresenebi_id');
        
        $this->load->model('Senebi_model');
        $this->Senebi_model->cierresenebi_id = $cierresenebi_id;
        $resultado = $this->Senebi_model->procesarGrilla();
        
        echo json_encode($resultado);
    }
    
    
    public function procesarGrillaGeneral(){
        
        $cierresenebi_id = $this->input->post('cierresenebi_id');
        
        $this->load->model('Senebi_model');
        $this->Senebi_model->cierresenebi_id = $cierresenebi_id;
        $resultado = $this->Senebi_model->procesarGrillaGeneral();
        
        echo json_encode($resultado);
    }

    public function procesarQuinto(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('senebi/procesarQuinto');
        $this->load->view('template/pie');
    }

    public function procesarQuintoGrilla(){
        
        $cierresenebi_id = $this->input->post('cierresenebi_id');
        
        $this->load->model('Senebi_model');
        $this->Senebi_model->cierresenebi_id = $cierresenebi_id;
        $resultado = $this->Senebi_model->procesarQuintoGrilla();
        
        echo json_encode($resultado);
    }
    
    public function getCierres(){
        $this->load->model('Senebi_model');
        $cierres = $this->Senebi_model->getCierres();
        echo json_encode($cierres);
    }
    
    public function grillaResumen(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Senebi_model');
        $this->Senebi_model->ordenes = $ordenes;
        $resultado = $this->Senebi_model->grillaResumen();
        echo json_encode($resultado);
    }
    
    
    public function previewMercado(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Senebi_model');
        $this->Senebi_model->ordenes = $ordenes;
        $resultado = $this->Senebi_model->previewMercado();
        
        echo json_encode($resultado);
    }

    public function enviarMercado(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Senebi_model');
        $this->Senebi_model->ordenes = $ordenes;
        $resultado = $this->Senebi_model->enviarMercado();
        echo json_encode($resultado);
    }
    
}
