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
        $this->load->library('ciauth');
    }

    /*
     * Function: get_user_data
     * This function  
     */

    public function get_user_data() {
        $query = $this->db->get_where("ciauth_user_accounts", array("user_id" => $this->session->userdata("user_id")));
        return $query->row();
    }

    public function check_keep_logged_in($user_id) {
        $query = $this->db->get_where("ciauth_user_accounts", array("user_id" => $user_id));
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

        $this->db->select('user_id, password, admin');
        $this->db->where("username", $data["login_value"]);
        $this->db->or_where("email", $data["login_value"]);
        $query = $this->db->get('ciauth_user_accounts');
        $result = $query->result();

        if (!empty($result)) {

            foreach ($result as $row) {
                $password_hash = $row->password;
                $user_id = $row->user_id;
                $admin = $row->admin;
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
                    "last_login" => $last_login,
                    "remember_me" => $data["rememberme"]
                );

                $this->db->update("ciauth_user_accounts", $data);

                $ipaddress = $this->input->ip_address();
                $this->session->set_userdata("user_id", $user_id);
                $this->session->set_userdata("admin", $admin);
                $this->ciauth->set_ciauth_session($user_id, $ipaddress);

                return true;
            }
        } else {
            return false;
        }
    }

    public function temp_login($data) {
        
        /*
         * First we get the hashed password to use to compare with the supplied
         * password.
         */

        $this->db->select('user_id, password, admin');
        $this->db->where("email", $data->email);
        $query = $this->db->get('ciauth_user_accounts');
        $result = $query->result();

        if (!empty($result)) {

            foreach ($result as $row) {
                $password_hash = $row->password;
                $user_id = $row->user_id;
                $admin = $row->admin;
            }


            $last_login = date("Y-m-d H-i-s");

            $data = array(
                "last_login" => $last_login
            );

            $this->db->update("ciauth_user_accounts", $data);

            $ipaddress = $this->input->ip_address();
            $this->session->set_userdata("user_id", $user_id);
            $this->session->set_userdata("admin", $admin);
            $this->ciauth->set_ciauth_session($user_id, $ipaddress);

            return true;
        } else {
            return false;
        }
    }

    /*
     * Function check_email
     * This function checks to see if the username or email exists. Returns
     * true if the results returns 0, otherwise it returns false
     */

    public function check_email($email) {
        $this->db->where("email", $email);
        $this->db->from('ciauth_user_accounts');
        $user_count = $this->db->count_all_results();

        if ($user_count > 0) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Function store_token
     * This function is used for password resets and store a token for the 
     * url sent in an email.
     */

    public function store_token($email, $token) {

        $data = array(
            'token' => $token,
            'email' => $email,
            'tstamp' => time()
        );
        $this->db->insert('ciauth_user_token', $data);
    }

    /*
     * Function get_token
     * This function is used for get the token and check for it's validity.
     * returns the users email is valid false if not.
     */

    public function get_token($token) {
        $this->db->where("token", $token);
        $query = $this->db->get('ciauth_user_token');

        $result = $query->result();

        if (!empty($result)) {
            $this->db->where("token", $token);
            $this->db->delete('ciauth_user_token');
            return $result[0];
        } else {
            return FALSE;
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

        if ($user_count > 0) {
            return false;
        } else {
            return true;
        }
    }

    /*
     * Function add_user_account
     * This function adds a new user account to the database
     */

    public function add_user_account($data) {
        $this->db->insert("ciauth_user_accounts", $data);
    }

    /*
     * Function store_ciauth_session
     * This function adds a ciauth session to the database. It also removes
     * all other sessions for this user.
     */

    public function store_ciauth_session($user_id, $data) {
        $this->db->delete('ciauth_sessions', array('user_id' => $user_id));
        $this->db->insert("ciauth_sessions", $data);
    }

    public function get_session_from_cookie($data) {
        $this->db->select('user_id, ip_address, data, rnd_key');
        $this->db->where("data", $data);
        $query = $this->db->get('ciauth_sessions');
        $result = $query->result();

        if (!empty($result)) {
            return $result;
        }
    }

}
