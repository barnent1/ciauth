<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * Name: ciauth
 * File: ciauth.php
 * Path: libraries/Ciauth.php
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

class Ciauth {

    protected $CI;

    /*
     * Constructor loads model and session library.
     */

    public function __construct() {

        $this->CI = & get_instance();

        /*
         * Load the database and session libraries.
         */

        $this->CI->load->model("m_ciauth");
        $this->CI->load->library("session");
    }

    /*
     * Function: get_user_data
     * If a user is logged in the function gets the user data otherwise it
     * returns false.
     */

    public function get_user_data() {

        if (!$this->logged_in()) {
            return false;
        } else {
            $user_data = $this->CI->m_ciauth->get_user_data();
            return $user_data;
        }
    }

    /*
     * Function: is_logged_in
     * If a user is logged in return ture otherwise return false.
     */

    public function is_logged_in() {
        return ($this->CI->session->userdata("user_id")) ? true : false;
    }

    /*
     * Function: login
     * This function logs in a user with either the username or email. Required
     * parameters are login_value and password. This function updates the
     * database with last login and sets the session.
     */

    public function login($login_value, $password) {

        $data = array(
            "login_value" => $login_value,
            "password" => $password
        );

        $this->CI->m_ciauth->login($data);
    }

    /*
     * Function: logout
     * This function simply unsets the session data.
     */
    
    function logout() {
        $this->CI->session->unset_userdata("user_id");
    }

    /*
     * Function: register
     * This function creates a user account by inserting data into the 
     * ciauth_user_accounts table.
     */
    
    function register($email, $username, $password) {

        //ensure the email is unique
        if ($this->check_user_exists($email, $username)) {
            $data = array(
                "username" => $username,
                "email" => $email,
                "password" => $password
            );
            
            $this->CI->m_ciauth->add_user_account($data);

            return true;
        }

        return false;
    }

    /*
     * Function: check_user_exists
     * Check to see if a user has regisitered already. If so then we return
     * false otherwise we return true.
     */
    
    function check_user_exists($email, $username) {
        $user_exists = $this->CI->m_ciauth->can_register($email, $username);
        return $user_exists;
    }

}
