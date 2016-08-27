<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
 
 
  function listDataOption($tabel, $key, $display, $val, $condition='') {
      $items = array();
      $string = "";
      $CI =& get_instance();  
      $query=$CI->db->query("Select $key, $display FROM $tabel $condition");  
      if ($query->num_rows() > 0) {
          foreach($query->result() as $data) {
              $items[$data->$key] = $data->$display; 
          }
          $query->free_result();
          foreach($items as $k => $v)   {
            if($k==$val) $string .= "<option selected value='$k'>$v</option>"; else
            $string .= "<option value='$k'>$v</option>";
          }
      } return $string;
  }
  
  function listData($tabel,$key,$value,$condition="") {
	  $items = array();
	  $CI =& get_instance();
	  /* if($orderBy) {
		  $CI->db->order_by($value,$orderBy);
	  } */
	  //$query = $CI->db->select("$name,$value")->from($table)->get();
	  
	  $query=$CI->db->query("Select $key, $value FROM $tabel $condition");
        //echo "Select $key, $value FROM $tabel $condition";
       // $query->result();
        //print_r($query);die();
       // echo '<pre>';
	  if ($query->num_rows() > 0) {
		  foreach($query->result() as $data) {
			  $items[$data->$key] = $data->$value;
             // echo $key."--".$value;
              //print_r($data->kdinstansi);
		  }
		  $query->free_result();
		  return $items;
	  }
  }
  
 function listDataCustom($tabel,$key,$select,$condition="") {
	  $items = array();
	  $CI =& get_instance();
	  
	  $query=$CI->db->query("Select $key,$select FROM $tabel $condition");
	  if ($query->num_rows() > 0) {
		  foreach($query->result() as $data) {
			  $items[$data->$key] = $data;
		  }
		  $query->free_result();
		  return $items;
	  }
  }
  
function listDataJson($tabel,$key,$value,$condition=""){
    $ci=& get_instance();
    $ci->load->helper(array('dropdown'));
    
   $dataArrayJson = array();    
   $dataArray = listData($tabel,$key,$value,$condition); 
   foreach($dataArray as $key => $val){
            $dataArrayJson[]=array('id'=>$key,'text'=>$val);            
        }
   return json_encode($dataArrayJson,JSON_NUMERIC_CHECK);
 }
 
/* End of file dropdwon_helper.php */
/* Location: ./application/helper/dropdown_helper.php 
	In controller
	public function index() {
		 $this->load->helper(array('dropdown','form'));
		 $dropdownItems = listData('country_tbl','country_id', 'country_name');
		 echo form_dropdown('dropdown',$dropdownItems,$selected = 8); }

*/