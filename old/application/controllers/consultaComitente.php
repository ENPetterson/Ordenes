<?php
class ConsultaComitente extends MY_AuthController {
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $this->load->view('template/encabezado');
        $this->load->view('template/menu');
        $this->load->view('lebac/consultaComitente');
        $this->load->view('template/pie');
    }
        
}
