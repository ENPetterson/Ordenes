<?php
class Consultaordenes extends MY_AuthController {
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('consultaordenes/grilla');
        $this->load->view('template/pie');
    }
    
    public function getComitentes(){
                
        $this->load->model('Consultaordenes_model');
        $result = $this->Consultaordenes_model->getComitentes();
        echo json_encode($result);
    }
    
    public function getConsultaOrdenes(){
                
        $numComitente = $this->input->post('numComitente');   
        
//        print_r($numComitente); die;
        $this->load->model('Consultaordenes_model');
        $this->Consultaordenes_model->numComitente = $numComitente;
        $result = $this->Consultaordenes_model->getConsultaOrdenes();
        echo json_encode($result);
    }
        
}
