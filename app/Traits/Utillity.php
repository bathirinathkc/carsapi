<?php 

namespace app\Traits;

use Illuminate\Support\Facades\Request;
use Config;
use Log;

trait Utillity {

    public function getReponse($status, $code, $message, $result = array('*')) 
    {

        $response['status']  = $status;
        $response['code']    = $code;
        $response['message'] = $message;
        $response['data']    = $result;    

        return $response;
    }

}