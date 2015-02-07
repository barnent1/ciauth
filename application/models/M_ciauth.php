<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * Name: ciauth
 * File: ciauth.php
 * Path: models/M_ciauth.php
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

class M_ciauth extends CI_model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /*
     * Function: get_user_data
     * This function  
     */

    public function get_user_data() {
        $query = $this->db->get_where("ciauth_user_accounts", array("user_id" => $this->session->userdata("user_id")));
        return $query->row();
    }

    /*
     * Function: login
     * This function checks if the user exists and the given password is correct.
     * It then updates the last login and session.
     */

    public function login($data) {

        /*
         * First we get the hashed password to use to compare with the supplied
         * password.
         */

        $this->db->select('user_id, password');
        $this->db->where("username", $data["login_value"]);
        $this->db->or_where("email", $data["login_value"]);
        $query = $this->db->get('ciauth_user_accounts');

        foreach ($query->result() as $row) {
            $password_hash = $row->password;
            $user_id = $row->user_id;
        }

        /*
         * Compare the password hash and return false if not valid otherwise
         * update the last login and set the session.
         */

        if (!password_verify($data['password'], $password_hash)) {
            return false;
        } else {

            $last_login = date("Y-m-d H-i-s");

            $data = array(
                "last_login" => $last_login
            );

            $this->db->update("ciauth_user_accounts", $data);

            $this->session->set_userdata("user_id", $user_id);

            return true;
        }
    }

    /*
     * Function can_register
     * This function checks to see if the username or email exists. Returns
     * true if the results returns 0, otherwise it returns false
     */
    
    public function can_register($email, $username) {
        $this->db->where("username", $username);
        $this->db->or_where("email", $email);
        $this->db->from('ciauth_user_accounts');
        $user_count = $this->db->count_all_results();
        
        if($user_count > 0){
            return false;
        }else{
            return true;
        }
    }
    
    /*
     * Function add_user_account
     * This function adds a new user account to the database
     */
    
    public function add_user_account($data){
        $this->db->insert("ciauth_user_accounts", $data);
    }

}
