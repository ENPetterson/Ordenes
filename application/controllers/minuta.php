<?php
class Minuta extends MY_AuthController {
    public function __construct() {
        parent::__construct();
    }

    public function index(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('minuta/grilla');
        $this->load->view('minuta/pie');
        $this->load->view('template/pie');
    }

    public function editar(){
        $datos['id'] = $this->input->post('id');
        $datos['origen'] = $this->session->userdata['usuario']['nombre'] . " " . $this->session->userdata['usuario']['apellido'];
        $datos['userId'] = $this->session->userdata['usuario']['id'];
        $usuarioNombre = $this->session->userdata['usuario']['nombre'] . " " . $this->session->userdata['usuario']['apellido'];
        $datos['usuario'] = $usuarioNombre;
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('minuta/editar', $datos);
        $this->load->view('template/pie');
    }

    public function saveOrden(){

        $this->load->model('Minuta_model');


        $datos = $this->input->post('datos');


        foreach ($datos as $d){

            $this->Minuta_model->id = $d['id'];
//            $this->Minuta_model->id = $this->input->post('id');

            $this->Minuta_model->operador = $d['operador'];
            $this->Minuta_model->numRegistro = $d['numRegistro'];
            $this->Minuta_model->numComitente = $d['numComitente'];
            $this->Minuta_model->comitente = $d['comitente'];
            $this->Minuta_model->fechaLiquidacion = $d['fechaLiquidacion'];

            $this->Minuta_model->tipoOperacionBurs = $d['tipoOperacionBurs'];
            $this->Minuta_model->tipoOperacionBursDesc = $d['tipoOperacionBursDesc'];
            $this->Minuta_model->codEspecie = $d['codEspecie'];
            $this->Minuta_model->especieAbreviatura = $d['especieAbreviatura'];
            $this->Minuta_model->codMoneda = $d['codMoneda'];
            $this->Minuta_model->monedaDescripcion = $d['monedaDescripcion'];
            $this->Minuta_model->cantidad = $d['cantidad'];

            $this->Minuta_model->esComitenteCorregido = $d['esComitenteCorregido'];
            $this->Minuta_model->numComitenteCorregido = $d['numComitenteCorregido'];
            $this->Minuta_model->comitenteCorregido = $d['comitenteCorregido'];

            $this->Minuta_model->esCantidadCorregido = $d['esCantidadCorregido'];
            $this->Minuta_model->cantidadCorregido = $d['cantidadCorregido'];

            $this->Minuta_model->esArancelCorregido = $d['esArancelCorregido'];
            $this->Minuta_model->arancelCorregido = $d['arancelCorregido'];

            $this->Minuta_model->observaciones = $d['observaciones'];


            $orden = $this->Minuta_model->saveOrden();
        }

        echo json_encode($orden);
    }

    public function getBoleto(){
        $this->load->model('Minuta_model');
//        $this->Minuta_model->codBoleto = $this->input->post('codBoleto');
        $this->Minuta_model->numBoleto = $this->input->post('numBoleto');
        $boleto = $this->Minuta_model->getBoleto();
        echo json_encode($boleto);
    }

    public function getOrden(){
        $this->load->model('Minuta_model');
        $this->Minuta_model->id = $this->input->post('id');
        $orden = $this->Minuta_model->getOrden();
        echo json_encode($orden);
    }

    public function delOrden(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Minuta_model');
        $this->Minuta_model->ordenes = $ordenes;
        $this->Minuta_model->delOrden();
        echo json_encode(array('resultado'=>'Ordenes borradas exitosamente'));
    }



    public function enviarOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Minuta_model');
        $this->Minuta_model->ordenes = $ordenes;
        $resultado = $this->Minuta_model->enviarOrdenes();
        echo json_encode($resultado);
    }

    public function aprobarOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $usuario = $this->session->userdata['usuario']['id'];

        $this->load->model('Minuta_model');
        $this->Minuta_model->ordenes = $ordenes;
        $this->Minuta_model->usuario = $usuario;

        $resultado = $this->Minuta_model->aprobarOrdenes();
        echo json_encode($resultado);
    }

    public function anularOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Minuta_model');
        $this->Minuta_model->ordenes = $ordenes;
        $resultado = $this->Minuta_model->anularOrdenes();
        echo json_encode($resultado);
    }

    public function procesarOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $usuario = $this->session->userdata['usuario']['id'];

        $this->load->model('Minuta_model');
        $this->Minuta_model->ordenes = $ordenes;
        $this->Minuta_model->usuario = $usuario;

        $resultado = $this->Minuta_model->procesarOrdenes();
        echo json_encode($resultado);
    }

    public function controlarOrdenes(){
        $ordenes = $this->input->post('ordenes');
        $usuario = $this->session->userdata['usuario']['id'];

        $this->load->model('Minuta_model');
        $this->Minuta_model->ordenes = $ordenes;
        $this->Minuta_model->usuario = $usuario;

        $resultado = $this->Minuta_model->controlarOrdenes();
        echo json_encode($resultado);
    }


    public function cierreEditar(){
        $data['id'] = $this->input->post('id');
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('minuta/cierreEditar', $data);
        $this->load->view('template/pie');
    }

    public function getCierre(){
        $cierreminuta_id = $this->input->post('cierreminuta_id');
        $this->load->model('Minuta_model');
        $this->Minuta_model->cierreminuta_id = $cierreminuta_id;
        $resultado = $this->Minuta_model->getCierre();
        echo json_encode($resultado);
    }

    public function saveCierre(){
        $cierreminuta_id = $this->input->post('cierreminuta_id');
        $fechaHora = $this->input->post('fechahora');
        $plazos = $this->input->post('plazos');
        $minimos = $this->input->post('minimos');
        $this->load->model('Minuta_model');
        $this->Minuta_model->cierreminuta_id = $cierreminuta_id;
        $this->Minuta_model->fechahora = $fechaHora;
        $this->Minuta_model->plazos = $plazos;
        $this->Minuta_model->minimos = $minimos;

        $cierre = $this->Minuta_model->saveCierre();
        echo json_encode($cierre);
    }

    public function delCierre(){
        $cierreminuta_id = $this->input->post('id');
        $this->load->model('Minuta_model');
        $this->Minuta_model->cierreminuta_id = $cierreminuta_id;
        $this->Minuta_model->delCierre();
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
        $table = "cierreminuta";
        $fields = array('id','fechahora','plazos');
        $datos = $this->grilla_model->datosGrilla($table, $fields, $pagenum, $pagesize,
                $filterscount, $filtervalue, $filtercondition, $filterdatafield,
                $filteroperator, $sortdatafield, $sortorder);
        echo json_encode($datos);
    }

    public function cierre(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('minuta/cierreGrilla');
        $this->load->view('template/pie');
    }

    public function getCierreActual(){
        $this->load->model('Minuta_model');
        $cierreActual = $this->Minuta_model->getCierreActual();
        echo json_encode($cierreActual);
    }

    public function procesar(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('minuta/procesar');
        $this->load->view('template/pie');
    }

    public function aprobar(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('minuta/aprobar');
        $this->load->view('template/pie');
    }

    public function control(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('minuta/control');
        $this->load->view('template/pie');
    }

    public function grilla(){
        $usuario = $this->session->userdata('usuario');
        $usuario_id = $usuario['id'];
        $cierreminuta_id = $this->input->post('cierreminuta_id');
        $this->load->model('Minuta_model');
        $this->Minuta_model->usuario_id = $usuario_id;
        $this->Minuta_model->cierreminuta_id = $cierreminuta_id;
        $resultado = $this->Minuta_model->grilla();

        echo json_encode($resultado);
    }

    public function procesarGrilla(){
        $cierreminuta_id = $this->input->post('cierreminuta_id');
        $this->load->model('Minuta_model');
        $this->Minuta_model->cierreminuta_id = $cierreminuta_id;
        $resultado = $this->Minuta_model->procesarGrilla();

        echo json_encode($resultado);
    }

    public function aprobarGrilla(){
        $cierreminuta_id = $this->input->post('cierreminuta_id');
        $this->load->model('Minuta_model');
        $this->Minuta_model->cierreminuta_id = $cierreminuta_id;
        $resultado = $this->Minuta_model->aprobarGrilla();

        echo json_encode($resultado);
    }

    public function controlGrilla(){
        $cierreminuta_id = $this->input->post('cierreminuta_id');
        $this->load->model('Minuta_model');
        $this->Minuta_model->cierreminuta_id = $cierreminuta_id;
        $resultado = $this->Minuta_model->controlGrilla();

        echo json_encode($resultado);
    }

    public function getCierres(){
        $this->load->model('Minuta_model');
        $cierres = $this->Minuta_model->getCierres();
        echo json_encode($cierres);
    }

    public function grillaResumen(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Minuta_model');
        $this->Minuta_model->ordenes = $ordenes;
        $resultado = $this->Minuta_model->grillaResumen();
        echo json_encode($resultado);
    }


    public function previewMercado(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Minuta_model');
        $this->Minuta_model->ordenes = $ordenes;
        $resultado = $this->Minuta_model->previewMercado();

        echo json_encode($resultado);
    }

    public function enviarMercado(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Minuta_model');
        $this->Minuta_model->ordenes = $ordenes;
        $resultado = $this->Minuta_model->enviarMercado();
        echo json_encode($resultado);
    }

    public function enviarMailsCierreMinuta(){
        $ordenes = $this->input->post('ordenes');
        $this->load->model('Minuta_model');
        $this->Minuta_model->ordenes = $ordenes;
        $resultado = $this->Minuta_model->enviarMailsCierreMinuta();
        echo json_encode($resultado);
    }
    
}
