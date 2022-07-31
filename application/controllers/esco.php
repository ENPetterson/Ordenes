<?php
class Esco extends MY_AuthController {
    public function __construct() {
        parent::__construct();
    }

    public function grilla(){
        $usuario = $this->session->userdata('usuario');
        $usuario_id = $usuario['id'];
        $cierretesoreria_id = $this->input->post('cierretesoreria_id');
        $this->load->model('Esco_model');
        $this->Esco_model->usuario_id = $usuario_id;
        $this->Esco_model->cierretesoreria_id = $cierretesoreria_id;
        $resultado = $this->Esco_model->grilla();

        echo json_encode($resultado);
    }
    
    public function grillaSaldosDisponibles(){
        $usuario = $this->session->userdata('usuario');
        $usuario_id = $usuario['id'];
        $cierretesoreria_id = $this->input->post('cierretesoreria_id');
        $this->load->model('Esco_model');
        $this->Esco_model->usuario_id = $usuario_id;
        $this->Esco_model->cierretesoreria_id = $cierretesoreria_id;
        $resultado = $this->Esco_model->grillaSaldosDisponibles();

        echo json_encode($resultado);
    }

    
    public function getComitente(){
        $numComitente = $this->input->post('numComitente');
        $this->load->model('Esco_model');
        $this->Esco_model->numComitente = $numComitente;
        $comitente = $this->Esco_model->getComitente();
        echo json_encode($comitente);
      }    
      
    public function getPosicion(){
        $numComitente = $this->input->post('numComitente');
        $especie = $this->input->post('especie');
        $this->load->model('Esco_model');
        $this->Esco_model->numComitente = $numComitente;
        $this->Esco_model->especie = $especie;
        $posicion = $this->Esco_model->getPosicion();
        echo json_encode($posicion);
    }   
      
          
    public function getEspecie(){
        $codEspecie = $this->input->post('codEspecie');
        $this->load->model('Esco_model');
        $this->Esco_model->codEspecie = $codEspecie;
        $comitente = $this->Esco_model->getEspecie();
        echo json_encode($comitente);
    }  
    
    
    public function getEspecieDescripcion(){
        $especie = $this->input->post('especie');
        $especie = strtoupper($especie);
        $this->load->model('Esco_model');
        $this->Esco_model->especie = $especie;
        $especieDesc = $this->Esco_model->getEspecieDescripcion();
        echo json_encode($especieDesc);
    } 
    // public function getMinuta(){
    //     $numRegistro = $this->input->post('numRegistro');       
    //     $this->load->model('Esco_model');
    //     $this->Esco_model->numRegistro = $numRegistro;        
    //     $minuta = $this->Esco_model->getMinuta();
    //     echo json_encode($minuta);
    // }
    
    public function getMinuta(){
        $numRegistro = $this->input->post('numRegistro');
        $numComitente = $this->input->post('numComitente');   
        $this->load->model('Esco_model');
        $this->Esco_model->numRegistro = $numRegistro;
        $this->Esco_model->numComitente = $numComitente;
        $minuta = $this->Esco_model->getMinuta();
        echo json_encode($minuta);
    }
    
    public function validarMinutaRegistroBoleto(){
        $numRegistro = $this->input->post('numRegistro');       
        $this->load->model('Esco_model');
        $this->Esco_model->numRegistro = $numRegistro;        
        $minuta = $this->Esco_model->validarMinutaRegistroBoleto();
        echo json_encode($minuta);
    }
    
    public function validarMinutaComitenteBoleto(){
        $numComitente = $this->input->post('numComitente');       
        $this->load->model('Esco_model');
        $this->Esco_model->numComitente = $numComitente;        
        $minuta = $this->Esco_model->validarMinutaComitenteBoleto();
        echo json_encode($minuta);
    }
    
      
    public function getBoletoAnulado(){
        $numBoleto = $this->input->post('numBoleto');
        $this->load->model('Esco_model');
        $this->Esco_model->numBoleto = $numBoleto;
        $boleto = $this->Esco_model->getBoletoAnulado();
        echo json_encode($boleto);
    }
      
    
    public function getBoleto(){
        $numBoleto = $this->input->post('numBoleto');
        $this->load->model('Esco_model');
        $this->Esco_model->numBoleto = $numBoleto;
        $boleto = $this->Esco_model->getBoleto();
        echo json_encode($boleto);
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


    public function getFondo(){
        /* Posicion Fondos */
        $this->load->model('Esco_model');
        $this->Esco_model->fondo = $this->input->post('fondo');
        $posicion = $this->Esco_model->getFondo();
        echo json_encode($posicion);
    }

    public function getFondos(){
        /* Posicion Fondos */
        $this->load->model('Esco_model');
        $posicion = $this->Esco_model->getFondos();
        echo json_encode($posicion);
    }

    public function getDisponible(){
//        $simbMoneda = '$';
//        $simbMoneda = $this->input->post('simbMoneda');
        $numComitente = $this->input->post('numComitente');
        $this->load->model('Esco_model');
//        $this->Esco_model->simbMoneda = $simbMoneda;
        $this->Esco_model->numComitente = $numComitente;
        $resultado = $this->Esco_model->getDisponible();

        echo json_encode($resultado);
    }

    public function getValorCuota(){
        $fondo = $this->input->post('fondo');
        $this->load->model('Esco_model');
        $this->Esco_model->fondo = $fondo;
        $resultado = $this->Esco_model->getValorCuota();

        echo json_encode($resultado);
    }

    public function getPosicionFondoNumeroComitente(){
        /* Posicion Fondos */
        $this->load->model('Esco_model');

        $this->Esco_model->numComitente = $this->input->post('numComitente');
        $this->Esco_model->fondo = $this->input->post('fondo');

        $posicion = $this->Esco_model->getPosicionFondoNumeroComitente();
        echo json_encode($posicion);
    }

    public function getPosicionFondos(){
        /* Posicion Fondos */
        $this->load->model('Esco_model');
        $this->Esco_model->numComitente = $this->input->post('numComitente');
        $posicion = $this->Esco_model->getPosicionFondos();
        echo json_encode($posicion);
    }
}
