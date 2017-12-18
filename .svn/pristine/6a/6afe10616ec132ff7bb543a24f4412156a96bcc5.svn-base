<?php
class Esco extends MY_AuthController {
    public function __construct() {
        parent::__construct();
    }
    
    public function getComitente(){
        $numComitente = $this->input->post('numComitente');
        $this->load->model('Esco_model');
        $this->Esco_model->numComitente = $numComitente;
        $comitente = $this->Esco_model->getComitente();
        echo json_encode($comitente);
    }
    
    public function getPosicionMonetaria(){
        $this->load->model('Esco_model');
        $posicion = $this->Esco_model->getPosicionMonetaria();
        echo json_encode($posicion);
    }
    
    public function getPosicionLebacs($instrumento){
        $this->load->model('Esco_model');
        //$instrumento = $this->input->post('instrumento');
        $this->Esco_model->instrumento = $instrumento;
        $posiciones = $this->Esco_model->getPosicionLebacs();
        echo json_encode($posiciones);
    }
    
    public function getInstrumento(){
        $this->load->model('Esco_model');
        $this->Esco_model->fecha = $this->input->post('fecha');
        $instrumento = $this->Esco_model->getInstrumento();
        echo json_encode($instrumento);
    }
    
    public function existeInstrumento(){
        $this->load->model('Esco_model');
        $instrumento = $this->input->post('instrumento');
        $this->Esco_model->instrumento = $instrumento;
        $resultado = $this->Esco_model->existeInstrumento();
        echo json_encode($resultado);
        
    }
}
