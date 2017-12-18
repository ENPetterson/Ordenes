<?php

class Estadoorden_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }
    
    public $id;
    public $estado;
    
    public function saveEstadoOrden(){
        $estadoOrden = R::load('estadoorden', $this->id);
        $estadoOrden->estado = $this->estado;
        $this->id = R::store($estadoOrden);
        return $estadoOrden->export();
    }
}