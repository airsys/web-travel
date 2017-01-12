<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
 
 
  function ppobxml($msisdn='', $oprcode='', $perintah='', $trxid=''){
		date_default_timezone_set('Asia/Jakarta');
		if($msisdn!='') $str_msisdn = "<msisdn>$msisdn</msisdn>"; else {$str_msisdn=''; $msisdn='0000';};
		if($oprcode!='') $str_oprcode = "<oprcode>".strtoupper($oprcode)."</oprcode>"; else $str_oprcode='';
		if($perintah!='') $str_perintah = "<perintah>$perintah</perintah>"; else $str_perintah='';
		if($trxid!='') $str_trxid = "<ref_trxid>$trxid</ref_trxid>"; else $str_trxid='';
		
		$time = date("His");
		$userid = '62CBI962';
		$pwd = '764292';

		$A = substr($msisdn, -4) . $time;
		$B = substr($userid,0,4) . substr($pwd,0,6);

		function xor_string($string, $key) {
		    for($i = 0; $i < strlen($string); $i++)
		        $string[$i] = ($string[$i] ^ $key[$i % strlen($key)]);
		    return $string;
		}
		function xml_array($str){
			$xml = simplexml_load_string($str, "SimpleXMLElement", LIBXML_NOCDATA);
			$json = json_encode($xml);
			$array = json_decode($json,TRUE);
			return $array;
		}

		$sign = base64_encode(xor_string($A,$B));

		$curl = curl_init();

		$req = "<?xml version=\"1.0\" ?><datacell>$str_perintah $str_oprcode<userid>$userid</userid><time>$time</time>$str_msisdn $str_trxid<sgn>$sign</sgn></datacell>";
//echo $req;die();
		curl_setopt_array($curl, array(
		  CURLOPT_PORT => "7711",
		  CURLOPT_URL => "http://117.104.201.18:7711/PT.CelebesWisataIndonesia.php",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => $req,
		  CURLOPT_HTTPHEADER => array(
		    "cache-control: no-cache",
		    "content-type: application/xml",
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  return array('resultcode'=>1, 'message'=>$err);
		} else {
		  //header('Content-Type: application/xml');
		  //print $req;
		  //print $response;
		  return xml_array($response);
		}
	}
	
	function cekSaldoPpob(){
		$CI =& get_instance();
		$CI->load->helper('ppob');
		$data = ppobxml('','','saldo','');
		return filter_var($data['message'], FILTER_SANITIZE_NUMBER_INT);	
	}