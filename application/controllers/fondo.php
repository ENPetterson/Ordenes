<?php
class Fondo extends MY_AuthController {
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('fondo/grilla');
        $this->load->view('fondo/pie');
        $this->load->view('template/pie');
    }
    
    public function editar(){
        $datos['id'] = $this->input->post('id');
        $datos['origen'] = $this->session->userdata['usuario']['nombre'] . " " . $this->session->userdata['usuario']['apellido'];
        $usuarioNombre = $this->session->userdata['usuario']['nombre'] . " " . $this->session->userdata['usuario']['apellido'];  
        $datos['usuario'] = $usuarioNombre;

        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('fondo/editar', $datos);
        $this->load->view('template/pie');
    }
    
    public function saveOrden(){
        $this->load->model('Fondo_model');     
        $this->Fondo_model->id = $this->input->post('id');
        $this->Fondo_model->fechaConcertacion = $this->input->post('fechaConcertacion');
        $this->Fondo_model->operador = $this->input->post('operador');
        $this->Fondo_model->operacion = $this->input->post('operacion');
        $this->Fondo_model->nombreFondo = $this->input->post('nombreFondo');
        $this->Fondo_model->fondo = $this->input->post('fondo');
        $this->Fondo_model->numComitente = $this->input->post('numComitente');
        $this->Fondo_model->nombreComitente = $this->input->post('nombreComitente');
        $this->Fondo_model->rescate = $this->input->post('rescate');
        $this->Fondo_model->importe = $this->input->post('importe');
        $this->Fondo_model->esAcdi = $this->input->post('esAcdi');
        $this->Fondo_model->noEsAcdiTipo = $this->input->post('noEsAcdiTipo');
        $this->Fondo_model->destinoRescate = $this->input->post('destinoRescate');
        $this->Fondo_model->totalCuotapartes = $this->input->post('totalCuotapartes');
        $this->Fondo_model->saldoMonetario = $this->input->post('saldoMonetario');
        $this->Fondo_model->saldoMonetarioDolar = $this->input->post('saldoMonetarioDolar');
        $this->Fondo_model->saldoMonetarioMep = $this->input->post('saldoMonetarioMep');
        $this->Fondo_model->origenFondos = $this->input->post('origenFondos');
        $this->Fondo_model->moneda = $this->input->post('moneda');
        $this->Fondo_model->observaciones = $this->input->post('observaciones');
        $this->Fondo_model->saldoAcdi = $this->input->post('saldoAcdi');
        $this->Fondo_model->saldoColocadorSimple = $this->input->post('saldoColocadorSimple');        
        $orden = $this->Fondo_model->saveOrden();
        echo json_encode($orden);
    }
    
    
    public function getOrden(){
        $this->load->model('Fondo_model');
        $this->Fondo_model->id = $this->input->post('id');
        $orden = $this->Fondo_model->getOrden();
        echo json_encode($orden);
    }
    
    public function delOrden(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Fondo_model');
        $this->Fondo_model->ordenes = $ordenes;
        $this->Fondo_model->delOrden();
        echo json_encode(array('resultado'=>'Ordenes borradas exitosamente'));
    }
    
    public function grilla(){
        
        
        $usuario = $this->session->userdata('usuario');
        $usuario_id = $usuario['id'];
        
        $cierrefondo_id = $this->input->post('cierrefondo_id');
                
        $this->load->model('Fondo_model');
        $this->Fondo_model->usuario_id = $usuario_id;
        $this->Fondo_model->cierrefondo_id = $cierrefondo_id;
        $resultado = $this->Fondo_model->grilla();
        
        echo json_encode($resultado);

        
    }
    
    public function enviarOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Fondo_model');
        $this->Fondo_model->ordenes = $ordenes;
        $resultado = $this->Fondo_model->enviarOrdenes();
        echo json_encode($resultado);
    }
    
    public function anularOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Fondo_model');
        $this->Fondo_model->ordenes = $ordenes;
        $resultado = $this->Fondo_model->anularOrdenes();
        echo json_encode($resultado);        
    }
    
    public function procesarOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Fondo_model');
        $this->Fondo_model->ordenes = $ordenes;
        $resultado = $this->Fondo_model->procesarOrdenes();
        echo json_encode($resultado);        
    }
    
    public function cierreEditar(){
        $data['id'] = $this->input->post('id');
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('fondo/cierreEditar', $data);
        $this->load->view('template/pie');
    }
    
    public function getCierre(){
        $cierrefondo_id = $this->input->post('cierrefondo_id');
        $this->load->model('Fondo_model');
        $this->Fondo_model->cierrefondo_id = $cierrefondo_id;
        $resultado = $this->Fondo_model->getCierre();
        echo json_encode($resultado);
    }
    
    public function saveCierre(){
        $cierrefondo_id = $this->input->post('cierrefondo_id');
        $fechaHora = $this->input->post('fechahora');
        $plazos = $this->input->post('plazos');
        $minimos = $this->input->post('minimos');
        $this->load->model('Fondo_model');
        $this->Fondo_model->cierrefondo_id = $cierrefondo_id;
        $this->Fondo_model->fechahora = $fechaHora;
        $this->Fondo_model->plazos = $plazos;
        $this->Fondo_model->minimos = $minimos;
//        $this->Fondo_model->colocacionDolares = $this->input->post('colocacionDolares');
        /*
        $this->Fondo_model->colocacionLebacsNov = $this->input->post('colocacionLebacsNov');
        $this->Fondo_model->colocacionLebacsDic = $this->input->post('colocacionLebacsDic');

        $this->Fondo_model->colocacionPesos = $this->input->post('colocacionPesos');
        $this->Fondo_model->colocacionA2J9 = $this->input->post('colocacionA2J9');
         * 
         */
        
        $cierre = $this->Fondo_model->saveCierre();
        echo json_encode($cierre);
    }
    
    public function delCierre(){
        $cierrefondo_id = $this->input->post('id');
        $this->load->model('Fondo_model');
        $this->Fondo_model->cierrefondo_id = $cierrefondo_id;
        $this->Fondo_model->delCierre();
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
        $table = "cierrefondo";
        $fields = array('id','fechahora','plazos');
        $datos = $this->grilla_model->datosGrilla($table, $fields, $pagenum, $pagesize, 
                $filterscount, $filtervalue, $filtercondition, $filterdatafield, 
                $filteroperator, $sortdatafield, $sortorder);
        echo json_encode($datos);
    }

    public function cierre(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('fondo/cierreGrilla');
        $this->load->view('template/pie');
    }
    
    public function getCierreActual(){
        $this->load->model('Fondo_model');
        $cierreActual = $this->Fondo_model->getCierreActual();
        echo json_encode($cierreActual);
    }

    public function procesar(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('fondo/procesar');
        $this->load->view('template/pie');
    }
    
    public function procesarGrilla(){
        
        $cierrefondo_id = $this->input->post('cierrefondo_id');
        
        $this->load->model('Fondo_model');
        $this->Fondo_model->cierrefondo_id = $cierrefondo_id;
        $resultado = $this->Fondo_model->procesarGrilla();
        
        echo json_encode($resultado);
    }
    
    public function getCierres(){
        $this->load->model('Fondo_model');
        $cierres = $this->Fondo_model->getCierres();
        echo json_encode($cierres);
    }
    
    public function getOrdenesFondos(){        
        $cierre_id = $this->input->post('cierre_id');
        $numComitente = $this->input->post('numComitente');
        $this->load->model('Fondo_model');
        $this->Fondo_model->cierre_id = $cierre_id;
        $this->Fondo_model->numComitente = $numComitente;
        $resultado = $this->Fondo_model->getOrdenesFondos();
        
        echo json_encode($resultado);
    } 
    
    public function grillaResumen(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Fondo_model');
        $this->Fondo_model->ordenes = $ordenes;
        $resultado = $this->Fondo_model->grillaResumen();
        echo json_encode($resultado);
    }
    
    
    public function previewMercado(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Fondo_model');
        $this->Fondo_model->ordenes = $ordenes;
        $resultado = $this->Fondo_model->previewMercado();
        
        echo json_encode($resultado);
    }

    public function enviarMercado(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Fondo_model');
        $this->Fondo_model->ordenes = $ordenes;
        $resultado = $this->Fondo_model->enviarMercado();
        echo json_encode($resultado);
    }
    
}
