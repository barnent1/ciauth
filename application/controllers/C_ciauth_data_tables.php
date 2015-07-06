<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class C_ciauth_data_tables extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("M_ds_swamp_cards");
    }
    
    public function user_swamp_cards_draft_ajax() {
        
        $this->load->library('ciauth_datatables');

        $table = 'ds_swampcards';
        $primaryKey = 'id';

        $columns = array(
            array('db' => 'a.id', 'dt' => 0),
            array('db' => 'a.date', 'dt' => 1),
            array('db' => 'a.recipient', 'dt' => 2),
            array('db' => 'a.inmate_id', 'dt' => 3),
            array('db' => 'b.name', 'dt' => 4),
            array('db' => 'a.status', 'dt' => 5),
            array('db' => '', 'dt' => 6, 'actions' => 'Edit')
        );
        
        $join_data = array(
            'join_table' => 'ds_institutions',
            'main_table_column' => 'institution',
            'join_table_column' => 'id'
        );
        $user_data = $this->ciauth->get_user_data();
        $where = array(
            array('column' => 'user_id', 'operator' => '=', 'value' => $user_data->user_id),
            array('column' => 'status', 'operator' => '=', 'value' => 'D')
        );

        echo json_encode(
                $this->ciauth_datatables->simple($_GET, $table, $join_data, $where, $primaryKey, $columns)
        );
    }
    
    public function user_swamp_cards_paid_ajax() {
        
        $this->load->library('ciauth_datatables');

        $table = 'ds_swampcards';
        $primaryKey = 'id';

        $columns = array(
            array('db' => 'a.id', 'dt' => 0),
            array('db' => 'a.date', 'dt' => 1),
            array('db' => 'a.recipient', 'dt' => 2),
            array('db' => 'a.inmate_id', 'dt' => 3),
            array('db' => 'b.name', 'dt' => 4),
            array('db' => 'a.status', 'dt' => 5),
            array('db' => '', 'dt' => 6, 'actions' => 'Edit')
        );
        
        $join_data = array(
            'join_table' => 'ds_institutions',
            'main_table_column' => 'institution',
            'join_table_column' => 'id'
        );
        $user_data = $this->ciauth->get_user_data();
        $where = array(
            array('column' => 'user_id', 'operator' => '=', 'value' => $user_data->user_id),
            array('column' => 'status', 'operator' => '!=', 'value' => 'D')
        );

        echo json_encode(
                $this->ciauth_datatables->simple($_GET, $table, $join_data, $where, $primaryKey, $columns)
        );
    }

    public function swamp_cards_ajax() {
        
        $this->load->library('ciauth_datatables');

        $table = 'ds_swampcards';
        $primaryKey = 'id';

        $columns = array(
            array('db' => 'a.id', 'dt' => 0),
            array('db' => 'a.date', 'dt' => 1),
            array('db' => 'a.recipient', 'dt' => 2),
            array('db' => 'a.inmate_id', 'dt' => 3),
            array('db' => 'b.name', 'dt' => 4),
            array('db' => 'a.status', 'dt' => 5),
            array('db' => '', 'dt' => 6, 'actions' => 'Edit')
        );
        
        $join_data = array(
            'join_table' => 'ds_institutions',
            'main_table_column' => 'institution',
            'join_table_column' => 'id'
        );
        
        $where = '';

        echo json_encode(
                $this->ciauth_datatables->simple($_GET, $table, $join_data, $where, $primaryKey, $columns)
        );
    }
    
    public function remove_swamp_card_ajax(){
        $id = $this->input->post('id');
        if($this->M_ds_swamp_cards->remove_swamp_card($id)){
            echo "Success";
        }else{
            echo "Fail";
        }
    }
    
    public function pages_ajax() {
        
        $this->load->library('ciauth_datatables');

        $table = 'ds_content';
        $primaryKey = 'id';

        $columns = array(
            array('db' => 'id', 'dt' => 0),
            array('db' => 'title', 'dt' => 1),
            array('db' => 'seo_url_segment', 'dt' => 2),
            array('db' => '', 'dt' => 3, 'actions' => 'Edit')
        );
        
        $join = "";
        
        $where = array(
            array('column' => 'type', 'operator' => '=', 'value' => 'page')
        );
        

        echo json_encode(
                $this->ciauth_datatables->simple($_GET, $table, $join, $where, $primaryKey, $columns)
        );
    }
    
    public function remove_content_ajax(){
        $id = $this->input->post('id');
        if($this->M_ds_swamp_cards->remove_content($id)){
            echo "Success";
        }else{
            echo "Fail";
        }
    }
    
    public function posts_ajax() {
        
        $this->load->library('ciauth_datatables');

        $table = 'ds_content';
        $primaryKey = 'id';

        $columns = array(
            array('db' => 'id', 'dt' => 0),
            array('db' => 'title', 'dt' => 1),
            array('db' => 'seo_url_segment', 'dt' => 2),
            array('db' => '', 'dt' => 3, 'actions' => 'Edit')
        );
        
        $join = "";
        
        $where = array(
            array('column' => 'type', 'operator' => '=', 'value' => 'post')
        );
        

        echo json_encode(
                $this->ciauth_datatables->simple($_GET, $table, $join, $where, $primaryKey, $columns)
        );
    }
    
    public function institutions_ajax() {
        
        $this->load->library('ciauth_datatables');

        $table = 'ds_institutions';
        $primaryKey = 'id';

        $columns = array(
            array('db' => 'id', 'dt' => 0),
            array('db' => 'name', 'dt' => 1),
            array('db' => 'state', 'dt' => 2),
            array('db' => '', 'dt' => 3, 'actions' => 'Edit')
        );
        
        $join = "";
        
        $where = "";
        
        echo json_encode(
                $this->ciauth_datatables->simple($_GET, $table, $join, $where, $primaryKey, $columns)
        );
    }
    
    public function remove_institution_ajax(){
        $id = $this->input->post('id');
        if($this->M_ds_swamp_cards->remove_institution($id)){
            echo "Success";
        }else{
            echo "Fail";
        }
    }
    
    public function users_ajax() {
        
        $this->load->library('ciauth_datatables');

        $table = 'ciauth_user_accounts';
        $primaryKey = 'user_id';

        $columns = array(
            array('db' => 'user_id', 'dt' => 0),
            array('db' => 'email', 'dt' => 1),
            array('db' => 'username', 'dt' => 2),
            array('db' => 'admin', 'dt' => 3),
            array('db' => '', 'dt' => 4, 'actions' => 'Edit')
        );
        
        $join = "";
        
        $where = "";
        
        echo json_encode(
                $this->ciauth_datatables->simple($_GET, $table, $join, $where, $primaryKey, $columns)
        );
    }
    
    public function remove_user_ajax(){
        $id = $this->input->post('id');
        if($this->M_ds_swamp_cards->remove_user($id)){
            echo "Success";
        }else{
            echo "Fail";
        }
    }

}
