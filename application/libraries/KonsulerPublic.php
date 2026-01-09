<?php
class KonsulerPublic {
    private $url = 'https://konsuler.sgstes.my.id/api/';

    private $response="";
    public $errorMessage="";
    public $isError=FALSE;

	public function __construct(){
	}

    private function chInit($t,$timeout=0){
        $this->response="";
        $this->errorMessage="";
        $this->isError=FALSE;

        $ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$this->url.$t);
        // curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // curl_setopt($ch, CURLOPT_USERPWD, "$this->username:$this->password");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        if(!empty($timeout)){
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        }
		return $ch;
    }

    private function chExec($ch){
        $result=curl_exec ($ch);
		if(curl_errno($ch)){
            $this->response="";
            $this->errorMessage=curl_error($ch);
            $this->isError=TRUE;
		}else{
            $this->response=$result;
            $this->errorMessage='';
            $this->isError=FALSE;
        }
		curl_close ($ch);
        return $this;
    }
    public function sendPost($u,$post,$timeout=0){
        $ch = $this->chInit($u,$timeout);
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $this->chExec($ch);
        return $this;
    }
    public function sendGet($u,$timeout=0){
        $ch = $this->chInit($u,$timeout);
		curl_setopt($ch, CURLOPT_POST,0);
        $this->chExec($ch);
        return $this;
    }
    public function getBody(){ return $this->response; }
    public function getArray(){ 
        if($this->isError){ return ['status_code'=>500,'message'=>$this->errorMessage]; }
        $d = json_decode($this->response,TRUE);
        return (Array) (json_last_error() === JSON_ERROR_NONE)?$d:NULL;
    }
    public function getObject(){ 
        if($this->isError){ return (Object)['status_code'=>500,'message'=>$this->errorMessage]; }
        $d = json_decode($this->response);
        return (Object) (json_last_error() === JSON_ERROR_NONE)?$d:[];
    }

    function getDataPelayanan($data=[]){ return $this->sendPost('layanan-online/data',$data,30); }

    function getDataPelayananItem($id){ return $this->sendPost('layanan-online/data-item',['id'=>$id],30); }

    function tolakPelayanan($id,$alasan){ return $this->sendPost('layanan-online/data-tolak',['id'=>$id,'alasan'=>$alasan],30); }
    function terimaPelayanan($id,$ref_id){ return $this->sendPost('layanan-online/data-terima',['id'=>$id,'ref_id'=>$ref_id],30); }
    function updateFileESign($ref,$file){ return $this->sendPost('layanan-online/data-file-e-sign',['kode'=>$ref,'file'=>$file],30); }
}
