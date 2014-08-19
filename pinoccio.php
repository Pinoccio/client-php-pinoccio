<?php namespace pinoccio;

class Pinoccio{

  public $api = "https://api.pinocc.io";
  public $token = false;
  public $lastResult;

  public function __construct($token = false, $api = false){
    if($api) $this->api = $api;
    if($token) $this->token = $token;
    else $this->token = $this->config("token");
  }

  public function login($user,$password){
    $res = $this->rest("post","/v1/login",array("email"=>$user,"password"=>$password));
    if(isset($res['data'])){
      $this->config("token",$res['data']['token']);
      $this->token = $res['data']['token'];
    }
    return $res;
  }

  public function rest($method,$url,$params = array()){
    $s = curl_init(); 

    $method = strtoupper($method);

    if(!isset($params['token']) && $this->token) $params['token'] = $this->token;

 
    $params = http_build_query($params);
 
    //curl_setopt($s,CURLOPT_HTTPHEADER,array()); 
    curl_setopt($s,CURLOPT_TIMEOUT,30000); 
    curl_setopt($s,CURLOPT_MAXREDIRS,3); 
    curl_setopt($s,CURLOPT_RETURNTRANSFER,true); 
    curl_setopt($s,CURLOPT_FOLLOWLOCATION,true); 
    curl_setopt($s,CURLOPT_USERAGENT,"pinoccio php api client v0.0.0");

    if($method == "POST") { 
      curl_setopt($s,CURLOPT_POST,true); 
      curl_setopt($s,CURLOPT_POSTFIELDS,$params); 
    } else {

      if($method !== "GET") {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
      }
      $url .= "?".$params;
    }

    // make slashing the start optional like other libs.
    if(strpos($url,'/') !== 0) $url = "/".$url;

    curl_setopt($s,CURLOPT_URL,$this->api.$url);  

    $res = curl_exec($s);

    // for debugging.
    $this->lastResult = $res;

    $lines = explode("\n",trim($res));
    $out = array();
    foreach($lines as $k=>$l) {
      $out[] = json_decode($l,true);
    }

    if(count($out) == 1) $out = $out[0];
    return $out;
  }

  public function config($key,$value){
    $dir = sys_get_temp_dir();
    $config = $dir.DIRECTORY_SEPARATOR."pinoccio.json";
    $data = array();
    if(file_exists($config)){
      $data = json_decode(file_get_contents($config),true);
    }

    if(isset($key)){
      if(count(func_get_args()) > 1){
        $data[$key] = $value;
        file_put_contents($config,json_encode($data));
      } else {
        return isset($data[$key])?$data[$key]:null;
      }
    }

    return $data;
  }
  
  //public function stream(){
  // todo support streaming end points.
  //}
}







