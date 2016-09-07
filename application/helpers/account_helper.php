<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
 
 
  function saldo() {
      $CI =& get_instance();
      $CI->load->model('m_payment');
      $saldo=$CI->m_payment->get_saldo();
      return $saldo;
  }
  