<?php
class TTE {
    private $url = 'http://10.128.150.43/api/';
    private $username = 'protkonsmy';
    private $password = 'tteapimy@2023';
    
    // public $nik = '0803202100007062';
    // public $pwd = 'Hantek1234.!';
    public $nik = '';
    public $pwd = '';

    private $response="";
    public $errorMessage="";
    public $isError=FALSE;

	public function __construct(){
        $this->nik = getSession('tte_nik');
        $this->pwd = getSession('tte_pwd');
	}

    private function chInit($t,$timeout=0){
        $this->response="";
        $this->errorMessage="";
        $this->isError=FALSE;

        $ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$this->url.$t);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$this->username:$this->password");
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

    public function cekStatus($nik,$timeout=0){ return $this->sendGet("user/status/$nik",$timeout); }
    public function signPDF($data,$timeout=0){ 
        foreach(['nik'=>$this->nik,'passphrase'=>$this->pwd] as $k => $v){
            if(empty($data[$k])){ $data[$k] = $v; }
        }
        if(!empty($data['file'])){
            if (function_exists('curl_file_create')) { // php 5.5+
                $cFile = curl_file_create($data['file'],'application/pdf');
            } else { // 
                $cFile = '@' . realpath($data['file']);
            }
            $data['file'] = $cFile;
        }
        $d = $this->sendPost("sign/pdf",$data)->getObject();
        if(empty($d)&&!$this->isError){
            return $this->savePdf();
        }else{
            $this->isError = TRUE;
            $this->errorMessage = @$d->error;
            return NULL;
        }
        
    }
    private function savePdf(){
        $data = $this->response;
        $path = FCPATH.'assets/';
        $file_path = 'pdf_tte/'.uuid().'.pdf';
        if(file_put_contents($path.$file_path,$data)){
            return $file_path;
        }else{
            return "";
        }
    }

    public function verifyPDF($file,$timeout=0){
        $file = FCPATH.'assets/'.$file;
        if (function_exists('curl_file_create')) { // php 5.5+
            $cFile = curl_file_create($file,'application/pdf');
        } else { // 
            $cFile = '@' . realpath($file);
        }
        $this->sendPost("sign/verify",['signed_file'=>$cFile],$timeout);
        if($this->isError){
            return (Object) ['status_code'=>500,'message'=>$this->message];
        }else{
            $d = $this->getObject();
            if(empty($d)){
                return (Object) ['status_code'=>500,'message'=>$this->response];
            }else{
                return $d;
            }
        }
    }
}
