<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * Name: ciauth
 * File: ciauth.php
 * Path: controllers/ciauth.php
 * Author: Glen Barnhardt
 * Company: Barnhardt Enterprises, Inc.
 * Email: glen@barnhardtenterprises.com
 * SiteURL: http://www.ciauth.com
 * GitHub URL: https://github.com/barnent1/ciauth.git
 *
 * Copyright 2015 Barnhardt Enterprises, Inc.
 *
 * Licensed under GNU GPL v3.0 (See LICENSE) http://www.gnu.org/copyleft/gpl.html
 * 
 * Description: CodeIgniter Login Authorization Library. Created specifically
 * for PHP 5.5 and Codeigniter 3.0+
 * 
 * Requirements: PHP 5.5 or later and Codeigniter 3.0+
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

class C_ciauth extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->helper('form', 'url');
        $this->load->library('ciauth', 'form_validation');
        
        if(!$this->ciauth->is_logged_in()){
            $this->login();
        }
    }

    /*
     * Function: login
     * Creates the login form to display
     */

    public function login() {
        $data = array();
        $login_form = form_open('', 'class="ciauth_login_form" id="ciauth_login_form"');

        # login value field
        $options = array(
            'name' => 'login_value',
            'id' => 'login_value',
            'class' => 'form_field',
            'size' => '90'
        );
        $login_form .= form_error('login_value');
        $login_form .= form_label('Username or Email: ', 'login_value');
        $login_form .= form_input($data);

        # password field
        $options = array(
            'name' => 'password',
            'id' => 'password',
            'class' => 'form_field',
            'size' => '20'
        );

        $login_form .= form_error('password');
        $login_form .= form_label('Password: ', 'password');
        $login_form .= form_password($data);

        $login_form .= form_submit('submit', 'Login');
        $login_form .= form_close();

        $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required', array('required' => 'You must provide a %s.'));
        

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('v_login', $data);
        } else {
            if(!$this->ciauth->login($login_value, $password)){
                $data['ciauth_error'] = "The username/email or password was not found";
                $this->load->view('v_login', $data);
            }else{
                
            }
            
        }
        
    }

}
