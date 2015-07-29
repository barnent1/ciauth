<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class C_ciauth_admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("M_ciauth_nav");
    }
    
    public function nav_admin(){
        /*
         * We can set the meta description, meta author, and title of each
         * page using the varibles. This is to give SEO value to our pages.
         */

        $meta_description = 'Ciauth - Authorization, Navigation, and Template libraries for CodeIgniter.';
        $meta_author = 'Glen Barnhardt, CEO | Barnhardt Enterprises, Inc.';
        $data = array();
        $data['title'] = "CIAUTH | Menu Builder";
        $data['meta_description'] = $meta_description;
        $data['meta_author'] = $meta_author;

        /*
         * Build the navigation
         * We grab values from the database for our navigation. These can
         * be changed in our admin interface under navigation.
         */

        $nav = new ciauth_nav();
        $nav->db_fields = array('id' => 'id', 'parent' => 'parent');

        $nav_elements = $this->M_ciauth_nav->get_menus();
        $nav_menu = $nav->walk($nav_elements, 10);
        
        $nav_admin = new ciauth_nav_admin();
        $nav_admin->db_fields = array('id' => 'id', 'parent' => 'parent');
        
        $nav_admin_elements = $this->M_ciauth_nav->get_menus();
        $nav_admin_menu = $nav_admin->walk($nav_admin_elements, 10);
        
        $data['nav_menu'] = $nav_menu;
        $data['nav_admin_menu'] = $nav_admin_menu;
        
        /*
         * load our V_template and the ciauth basic 
         */

        $this->ciauth_template->load('V_template','ciauth/admin/V_nav_admin', $data);
    }
    
    public function update_nav_ajax(){
        if($this->input->post('menu')){
            $data = $this->input->post('menu');
            if($this->M_ciauth_nav->update_menus($data)){
                echo "SUCCESS";
            }else{
                echo "ERROR";
            }
        }else{
            echo "ERROR";
        }
        
    }
    
}

