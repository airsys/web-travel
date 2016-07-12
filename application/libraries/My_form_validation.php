<?php
class My_form_validation extends CI_Form_validation {

    public function error_array() {
        return $this->_error_array;
    }

}