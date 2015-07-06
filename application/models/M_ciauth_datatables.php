<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_ciauth_datatables extends CI_model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function get_data($sql){
        $query = $this->db->query($sql);
       
        if ($query->result()) {
            $result = $query->result();
            return $result;
        } else {
            return false;
        }
    }
}

