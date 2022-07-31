<?php

class Envioautorizacion_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }
    
//    public $id;
//    public $estado;
    
    public function getEnviarAutorizacion(){

        
        
        // Si entra por aprobar
        // Si entra por control
        // Si entra por checkeo automático
        $respuesta = array();
        
//        $tipo = 'tareaAutomatica';
        
//        if($this->input->post('tipo')){
//            $tipo = $this->input->post('tipo');
//        }
        
//        if($tipo == 'check' || $tipo == 'tareaAutomatica'){
//            $this->load->model('Parking_model');        
//            $ordenes = $this->Parking_model->comprobarOMS();
//        }else{
            $ordenes = $this->ordenes;
//        }        
        
//        $cierre;
//        if($this->input->post('cierre')){
//            $cierre = $this->input->post('cierre');
//        }
                
        $cierre = $this->cierre;
        
        $tipo = $this->tipo;
        
            
        $esDDJJ = null;
                       
        $count = 0;
        $id;
        
        
        foreach ($ordenes as $orden){
                        
            $count++;

            
            
            $ordenId = $orden['id'];
                        
            $numComitente = $orden['numComitente'];
            
//            if($tipo == 'check' || $tipo == 'tareaAutomatica'){
//                if($orden['id']){
//                    $id = $orden['id'];
//                }
//            }
                        
            
            if( ($tipo == 'aprobar') || ($tipo == 'control')){
                $this->load->model('Parking_model');
                $this->Parking_model->numComitente = $orden['numComitente'];
                $this->Parking_model->cierre = $cierre['id'];
                $fechaAceptacion = $this->Parking_model->getFechaAceptacion();

//                print_r($fechaAceptacion); die;
                if(isset($fechaAceptacion['fecha'])){
                    $esDDJJ = ($fechaAceptacion['fecha']);
                }
            }
            
//            if( ($tipo == 'check' || $tipo == 'tareaAutomatica') ){
//                $orden = $orden['numComitente'];
//            }
            
//            $esDDJJ = '2020-07-27';
                        
            if( ($tipo == 'aprobar'  && $esDDJJ != null) || ($tipo == 'control') || ($tipo == 'check') || ($tipo == 'tareaAutomatica') ){
                
                               
                ini_set('display_startup_errors', 1); 
                error_reporting(E_ALL);
                ini_set('display_errors', 1);

                $url = "https://oms.allaria.com.ar/generic-oauth-core/oauth/token";
                $curl = curl_init($url);

                curl_setopt_array($curl, array(
                  CURLOPT_URL => $url,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_SSL_VERIFYHOST => 0,
                  CURLOPT_SSL_VERIFYPEER => 0,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "POST",
                  CURLOPT_POSTFIELDS => "grant_type=password&username=icools&password=Melina27",
                  CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/x-www-form-urlencoded",
                    "Authorization: Basic YWRtaW5pc3RyYWRvcjoyNURlTWF5bw=="
                  ),
                ));

                $response = curl_exec($curl);
                $response = json_decode(curl_exec($curl));

                
                
                
                $status = curl_getinfo($curl);
                
               
                                

                curl_close($curl);      

                $curl = curl_init();

                curl_setopt_array($curl, array(
                  CURLOPT_URL => "https://oms.allaria.com.ar/vanoms-be-core/rest/api/bo/account-validation",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_SSL_VERIFYHOST => 0,
                  CURLOPT_SSL_VERIFYPEER => 0,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "POST",
                  CURLOPT_POSTFIELDS =>"{\n    \"legalEntity\": \"ALLARIA\",\n    \"accountNumber\": \"{$numComitente}\",\n    \"position\": \"N\",\n    \"tradingLimit\": \"N\",\n    \"validateSettlementType\": \"N\",\n    \"allowDollarSellOperation\": \"Y\"\n}\n\n",
                  CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json",
                    "Authorization: Bearer $response->access_token",
                    "Cookie: JSESSIONID=B9AE6044AE006BFCD94FE39F8F982509"
                  ),
                ));

                $response = json_decode(curl_exec($curl));

                curl_close($curl);

                $response = (array)$response;                    
                
                if($response['status'] == 'received'){
                    $respuesta[$count]["response"] = 'confirmado';
                }else{
                    $respuesta[$count]["response"] = 'Hubo un error al contactar con OMS';
                }     
                
                
//                if($tipo == 'tareaAutomatica' && $response['status'] == 'received'){
//                    $this->load->model('Parking_model');
//                    $this->Parking_model->ordenes = (array)$id;
//                    $res = $this->Parking_model->aprobarOrdenesOMS();
//                }
                

                if($tipo == 'control'){
                    $respuesta[$count]["message"] = ' para comitente: '. $ordenId;
                }else{
                    $respuesta[$count]["message"] = 'ID '. $ordenId . ": Parking aprobado y DDJJ recibida";
                }
                
                
//                if($tipo == 'check' || $tipo == 'tareaAutomatica'){
//                    if($id){
//                    $respuesta[$count]["id"] = $id;
//                    }else{
//                        $respuesta[$count]["id"] = 0;
//                    }
//                }
                
                
            }else{
                
                $respuesta[$count]["response"] =  'ID '. $ordenId . ": Parking aprobado. Env&iacute;o OMS no se realiz&oacute;";
                $respuesta[$count]["message"] = ", DDJJ pendiente";

//                if($tipo == 'check' || $tipo == 'tareaAutomatica'){
//                    if($orden['id']){
//                        $respuesta[$count]["id"] = $id;
//                    }else{
//                        $respuesta[$count]["id"] = 0;
//                    }
//                }
            }
            
            $fecha = (string) date('Ymd');
            $app_id = uniqid();//give each process a unique ID for differentiation
            $log_name = "test".$fecha.".txt";
            $log = fopen('/var/www/ordenes/application/downloads/'.$log_name,'a');

            $fechaHora = (string) date('Ymdhis');
            $log_line = join(array($fechaHora . ' ' . json_encode($respuesta), chr(13).chr(10) ) );
            fwrite($log, $log_line."\n");
            fclose($log);
            $contenido = file_get_contents("/var/www/ordenes/application/downloads/" . $log_name);
            
            
        }
        
//        echo json_encode(array('resultado'=>$respuesta));
        return $respuesta;
        
        
        
        
        
        
        
    }
}