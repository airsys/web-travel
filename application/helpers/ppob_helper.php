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

		if ( ! function_exists('xor_string'))
		{
			function xor_string($string, $key) {
			    for($i = 0; $i < strlen($string); $i++)
			        $string[$i] = ($string[$i] ^ $key[$i % strlen($key)]);
			    return $string;
			}
		}
		
		if ( ! function_exists('xml_array'))
		{
			function xml_array($str){
				$xml = simplexml_load_string($str, "SimpleXMLElement", LIBXML_NOCDATA);
				$json = json_encode($xml);
				$array = json_decode($json,TRUE);
				return $array;
			}
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
		//$err = FALSE;

		curl_close($curl);

		if ($err) {
		  return array('resultcode'=>1, 'message'=>$err);
		} else {
		  //header('Content-Type: application/xml');
		  //print $req;
		  //print $response;
		  /*$response = '<?xml version="1.0"?><datacell><resultcode>0</resultcode><message>XL5 No: 087825668660 sudah diterima dan sdg diproses. SN Kami :293599141. Harga: 6000. Saldo: Rp 88000.</message><trxid>293599141</trxid><ref_trxid>1484581242</ref_trxid></datacell>';*/
		  /*{"resultcode":"0","message":"XL5 No: 087825668660 sudah diterima dan sdg diproses. SN Kami :293599141. Harga: 6000. Saldo: Rp 88000.","trxid":"293599141","ref_trxid":"1484581242"}*/
		  return xml_array($response);
		}
	}
	
	function cekSaldoPpob(){
		$CI =& get_instance();
		$CI->load->helper('ppob');
		$data = ppobxml('','','saldo',RandomString());
		return filter_var($data['message'], FILTER_SANITIZE_NUMBER_INT);	
	}
	
	if ( ! function_exists('GetBetween')){
		function GetBetween($var1="",$var2="",$pool){
			$temp1 = strpos($pool,$var1)+strlen($var1);
			$result = substr($pool,$temp1,strlen($pool));
			$dd=strpos($result,$var2);
			if($dd == 0){
				$dd = strlen($result);
			}
			return substr($result,0,$dd);
		}
	}

		
	if ( ! function_exists('xml2array'))
		{
			function xml2array($str){
				$xml = simplexml_load_string($str, "SimpleXMLElement", LIBXML_NOCDATA);
				$json = json_encode($xml);
				$array = json_decode($json,TRUE);
				return $array;
			}
		}