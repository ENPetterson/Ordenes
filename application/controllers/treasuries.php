<?php
class Treasuries extends MY_AuthController {
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('treasuries/grilla');
        $this->load->view('treasuries/pie');
        $this->load->view('template/pie');
    }
    
    public function editar(){
        $datos['id'] = $this->input->post('id');
        $datos['origen'] = $this->session->userdata['usuario']['nombre'] . " " . $this->session->userdata['usuario']['apellido'];
        $usuarioNombre = $this->session->userdata['usuario']['nombre'] . " " . $this->session->userdata['usuario']['apellido'];  
        $datos['usuario'] = $usuarioNombre;

        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('treasuries/editar', $datos);
        $this->load->view('template/pie');
    }
    
    public function aprobar(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('treasuries/aprobar');
        $this->load->view('template/pie');
    }
    
    public function control(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('treasuries/control');
        $this->load->view('template/pie');
    }
    
    
    public function saveOrden(){
        
        
        
        $this->load->model('Treasuries_model');
        $this->Treasuries_model->id = $this->input->post('id');
        $this->Treasuries_model->operador = $this->input->post('operador');
        $this->Treasuries_model->tipoOperacion = $this->input->post('tipoOperacion');
        $this->Treasuries_model->esPrecioComitente = $this->input->post('esPrecioComitente');
        $this->Treasuries_model->precioComitente = $this->input->post('precioComitente');
        $this->Treasuries_model->esArancel = $this->input->post('esArancel');
        $this->Treasuries_model->arancel = $this->input->post('arancel');
        $this->Treasuries_model->garantia = $this->input->post('garantia');
        $this->Treasuries_model->precioCartera = $this->input->post('precioCartera');
        $this->Treasuries_model->numComitente = $this->input->post('numComitente');
        $this->Treasuries_model->especie = $this->input->post('especie');
        $this->Treasuries_model->plazo = $this->input->post('plazo');
        $this->Treasuries_model->moneda = $this->input->post('moneda');
        $this->Treasuries_model->cantidad = $this->input->post('cantidad');
        $this->Treasuries_model->codigo = $this->input->post('codigo');
        $this->Treasuries_model->brutoCliente = $this->input->post('brutoCliente');
        $this->Treasuries_model->observaciones = $this->input->post('observaciones');
        $this->Treasuries_model->numComitenteContraparte = $this->input->post('numComitenteContraparte');
        $orden = $this->Treasuries_model->saveOrden();
        echo json_encode($orden);
    }
    
    
    public function getOrden(){
        $this->load->model('Treasuries_model');
        $this->Treasuries_model->id = $this->input->post('id');
        $orden = $this->Treasuries_model->getOrden();
        echo json_encode($orden);
    }
    
    public function delOrden(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Treasuries_model');
        $this->Treasuries_model->ordenes = $ordenes;
        $this->Treasuries_model->delOrden();
        echo json_encode(array('resultado'=>'Ordenes borradas exitosamente'));
    }
    
    public function grilla(){
        
        
        $usuario = $this->session->userdata('usuario');
        $usuario_id = $usuario['id'];
        
        $cierretreasuries_id = $this->input->post('cierretreasuries_id');
                
        $this->load->model('Treasuries_model');
        $this->Treasuries_model->usuario_id = $usuario_id;
        $this->Treasuries_model->cierretreasuries_id = $cierretreasuries_id;
        $resultado = $this->Treasuries_model->grilla();
        
        echo json_encode($resultado);

        
    }
    
    public function enviarOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Treasuries_model');
        $this->Treasuries_model->ordenes = $ordenes;
        $resultado = $this->Treasuries_model->enviarOrdenes();
        echo json_encode($resultado);
    }
    
    public function anularOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Treasuries_model');
        $this->Treasuries_model->ordenes = $ordenes;
        $resultado = $this->Treasuries_model->anularOrdenes();
        echo json_encode($resultado);        
    }
    
    public function aprobarOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $usuario = $this->session->userdata['usuario']['id'];  

        $this->load->model('Treasuries_model');
        $this->Treasuries_model->ordenes = $ordenes;
        $this->Treasuries_model->usuario = $usuario;
        
        $resultado = $this->Treasuries_model->aprobarOrdenes();
        echo json_encode($resultado);
    }    
    
    
    public function procesarOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $usuario = $this->session->userdata['usuario']['id'];  

        $this->load->model('Treasuries_model');
        $this->Treasuries_model->ordenes = $ordenes;
        $this->Treasuries_model->usuario = $usuario;
        
        $resultado = $this->Treasuries_model->procesarOrdenes();
        echo json_encode($resultado);     
    }
    
    public function cierreEditar(){
        $data['id'] = $this->input->post('id');
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('treasuries/cierreEditar', $data);
        $this->load->view('template/pie');
    }
    
    public function getCierre(){
        $cierretreasuries_id = $this->input->post('cierretreasuries_id');
        $this->load->model('Treasuries_model');
        $this->Treasuries_model->cierretreasuries_id = $cierretreasuries_id;
        $resultado = $this->Treasuries_model->getCierre();
        echo json_encode($resultado);
    }
    
    public function saveCierre(){
        $cierretreasuries_id = $this->input->post('cierretreasuries_id');
        $fechaHora = $this->input->post('fechahora');
        $plazos = $this->input->post('plazos');
        $minimos = $this->input->post('minimos');
        $this->load->model('Treasuries_model');
        $this->Treasuries_model->cierretreasuries_id = $cierretreasuries_id;
        $this->Treasuries_model->fechahora = $fechaHora;
        $this->Treasuries_model->plazos = $plazos;
        $this->Treasuries_model->minimos = $minimos;
//        $this->Treasuries_model->colocacionDolares = $this->input->post('colocacionDolares');
        /*
        $this->Treasuries_model->colocacionLebacsNov = $this->input->post('colocacionLebacsNov');
        $this->Treasuries_model->colocacionLebacsDic = $this->input->post('colocacionLebacsDic');

        $this->Treasuries_model->colocacionPesos = $this->input->post('colocacionPesos');
        $this->Treasuries_model->colocacionA2J9 = $this->input->post('colocacionA2J9');
         * 
         */
        
        $cierre = $this->Treasuries_model->saveCierre();
        echo json_encode($cierre);
    }
    
    public function delCierre(){
        $cierretreasuries_id = $this->input->post('id');
        $this->load->model('Treasuries_model');
        $this->Treasuries_model->cierretreasuries_id = $cierretreasuries_id;
        $this->Treasuries_model->delCierre();
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
        $table = "cierretreasuries";
        $fields = array('id','fechahora','plazos');
        $datos = $this->grilla_model->datosGrilla($table, $fields, $pagenum, $pagesize, 
                $filterscount, $filtervalue, $filtercondition, $filterdatafield, 
                $filteroperator, $sortdatafield, $sortorder);
        echo json_encode($datos);
    }

    
    public function aprobarGrilla(){
        $cierretreasuries_id = $this->input->post('cierretreasuries_id');
        $this->load->model('Treasuries_model');
        $this->Treasuries_model->cierretreasuries_id = $cierretreasuries_id;
        $resultado = $this->Treasuries_model->aprobarGrilla();
        
        echo json_encode($resultado);
    }
    
    public function cierre(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('treasuries/cierreGrilla');
        $this->load->view('template/pie');
    }
    
    public function getCierreActual(){
        $this->load->model('Treasuries_model');
        $cierreActual = $this->Treasuries_model->getCierreActual();
        echo json_encode($cierreActual);
    }

    public function procesar(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('treasuries/procesar');
        $this->load->view('template/pie');
    }
    
    public function controlGrilla(){
        $cierretreasuries_id = $this->input->post('cierretreasuries_id');
        $this->load->model('Treasuries_model');
        $this->Treasuries_model->cierretreasuries_id = $cierretreasuries_id;
        $resultado = $this->Treasuries_model->controlGrilla();
        
        echo json_encode($resultado);
    }
    
    public function procesarGrilla(){
        
        $cierretreasuries_id = $this->input->post('cierretreasuries_id');
        
        $this->load->model('Treasuries_model');
        $this->Treasuries_model->cierretreasuries_id = $cierretreasuries_id;
        $resultado = $this->Treasuries_model->procesarGrilla();
        
        echo json_encode($resultado);
    }

    public function controlarOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $usuario = $this->session->userdata['usuario']['id'];  

        $this->load->model('Treasuries_model');
        $this->Treasuries_model->ordenes = $ordenes;
        $this->Treasuries_model->usuario = $usuario;
        
        $resultado = $this->Treasuries_model->controlarOrdenes();
        echo json_encode($resultado);     
    }
    
    public function procesarQuinto(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('treasuries/procesarQuinto');
        $this->load->view('template/pie');
    }

    public function procesarQuintoGrilla(){
        
        $cierretreasuries_id = $this->input->post('cierretreasuries_id');
        
        $this->load->model('Treasuries_model');
        $this->Treasuries_model->cierretreasuries_id = $cierretreasuries_id;
        $resultado = $this->Treasuries_model->procesarQuintoGrilla();
        
        echo json_encode($resultado);
    }
    
    public function getCierres(){
        $this->load->model('Treasuries_model');
        $cierres = $this->Treasuries_model->getCierres();
        echo json_encode($cierres);
    }
    
    public function grillaResumen(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Treasuries_model');
        $this->Treasuries_model->ordenes = $ordenes;
        $resultado = $this->Treasuries_model->grillaResumen();
        echo json_encode($resultado);
    }
    
    
    public function previewMercado(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Treasuries_model');
        $this->Treasuries_model->ordenes = $ordenes;
        $resultado = $this->Treasuries_model->previewMercado();
        
        echo json_encode($resultado);
    }

    public function enviarMercado(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Treasuries_model');
        $this->Treasuries_model->ordenes = $ordenes;
        $resultado = $this->Treasuries_model->enviarMercado();
        echo json_encode($resultado);
    }
    
}
