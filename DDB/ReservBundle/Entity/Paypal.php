<?php
namespace DDB\ReservBundle\Entity;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Paypal
 *
 * @author Demoniakillah
 */
class Paypal {
    
    private $user;
    private $pwd;
    private $signature;
    private $endpoint;
    public $errors = array();
    
    function __construct($user, $pwd, $signature, $endpoint) {
        $this->user = $user;
        $this->pwd = $pwd;
        $this->signature = $signature;
        $this->endpoint = $endpoint;
    }

    
    public function request($method,$params){
        $params = array_merge($params,array(
            'METHOD'=>$method,
            'VERSION'=>'121.0',
            'USER'=>  $this->user,
            'SIGNATURE'=>  $this->signature,
            'PWD'=>  $this->pwd
        ));
        $params = http_build_query($params);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->endpoint,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_VERBOSE => 1
        ));
        $response = curl_exec($curl);
        $responseArray = array();
        parse_str($response,$responseArray);
        if(curl_errno($curl)){
            $this->errors = curl_error($curl);
            curl_close($curl);
            return false;
        }
        else{
            if($responseArray['ACK']=='Success'){
                curl_close($curl);
                return $responseArray;
            }
            else{
                $this->errors = $responseArray;
                curl_close($curl);
                return false;
            }
        }
    }
    
}
