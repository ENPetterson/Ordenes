<?php
class EnviarMails extends CI_Controller{
    public function index(){
        $this->load->model('Lebac_model');
        $this->Lebac_model->enviarMailsCierre();
        $this->load->model('Letes_model');
        $this->Letes_model->enviarMailsCierre();
        $this->load->model('Bono_model');
        $this->Bono_model->enviarMailsCierre();
        $this->load->model('Cupon_model');
        $this->Cupon_model->enviarMailsCierre();
        $this->load->model('LetesPesos_model');
        $this->LetesPesos_model->enviarMailsCierre();
        $this->load->model('Minuta_model');
        $this->Minuta_model->enviarMailsCierre();
    }
}