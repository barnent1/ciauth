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

        if (!$this->is_logged_in()) {
            return false;
        } else {
            $user_data = $this->CI->m_ciauth->get_user_data();
            return $user_data;
        }
    }

    /*
     * Function: is_logged_in
     * If a user is logged in return true otherwise return false.
     */

    public function is_logged_in() {
        return ($this->CI->session->userdata("user_id") != "") ? true : false;
    }

    /*
     * Function: is_admin
     * If a user is admin in return true otherwise return false.
     */

    public function is_admin() {
        return ($this->CI->session->userdata("admin") == "Y") ? true : false;
    }

    /*
     * Function: login
     * This function logs in a user with either the username or email. Required
     * parameters are login_value and password. This function updates the
     * database with last login and sets the session.
     */

    public function login($login_value, $password, $rememberme = null) {
        $this->CI->session->unset_userdata("user_id");
        $data = array(
            "login_value" => $login_value,
            "password" => $password,
            "rememberme" => $rememberme
        );

        $login_status = $this->CI->m_ciauth->login($data);

        return $login_status;
    }

    /*
     * Function: get_login_form
     * This function creates a login form that can be displayed on a page.
     */

    public function get_login_form() {

        $login_form = "<div class=\"container\">";
        $attributes = array('class' => 'form-signin');
        $login_form .= form_open('', $attributes);
        $login_form .= "<h2 class=\"form-signin-heading\">Please sign in</h2>";

        # login value field
        $options = array(
            'name' => 'login_value',
            'type' => 'email',
            'id' => 'login_value',
            'class' => 'form-control',
            'placeholder' => 'Username or Email Address',
            'size' => '25'
        );

        $login_form .= form_error('login_value');
        $login_form .= form_input($options);

        # password field
        $options = array(
            'name' => 'password',
            'id' => 'password',
            'class' => 'form-control',
            'placeholder' => 'Password',
            'size' => '25'
        );

        $login_form .= form_error('password');
        $login_form .= form_password($options);

        $login_form .= "<div id='message'></div>";

        $login_form .= "<div class='form-signin'>";
        $options = array(
            'name' => 'keep_logged_in',
            'id' => 'keep_logged_in',
            'value' => 'accept',
            'checked' => TRUE,
        );

        $login_form .= form_checkbox($options);
        $login_form .= form_label('Remember Me: ', 'keep_logged_in');
        $login_form .= "</div>";

        $options = array(
            'name' => 'submit',
            'id' => 'login_button',
            'class' => 'btn btn-lg btn-primary btn-block',
            'value' => 'submit',
            'type' => 'submit',
            'content' => 'Sign In'
        );

        $login_form .= form_button($options);
        $login_form .= form_close();
        $login_form .= "<div class=\"form-signin\"><a href='forgot_password'>Forgot your password?</a> or <a href='register'>Register</a></div>";
        $login_form .= "</div>";

        return $login_form;
    }

    /*
     * Function: get_modal_login_template
     * This function creates a modal popup login form.
     */

    function get_modal_login_template() {
        $modal_login_template = "<div class=\"modal fade bs-modal-sm\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">";
        $modal_login_template .= "<div class=\"modal-dialog modal-sm\">";
        $modal_login_template .= "<div class=\"ciauth-modal-content\">";
        $modal_login_template .= $this->get_login_form();
        $modal_login_template .= "</div>";
        $modal_login_template .= "</div>";
        $modal_login_template .= "</div>";
        return $modal_login_template;
    }

    /*
     * Function: set_ciauth_session
     * Parameters: user_id, ipaddress
     * 
     * This function stores a session hash in the ciauth_sessions table. It 
     * is then used with the remember me function so that users that close 
     * the browser will still be logged in when they come back to the site.
     * 
     * Our keys are only good for 24 hours and they are super encrypted.
     * 
     */

    function set_ciauth_session($user_id, $ipaddress) {
        $key = "";
        $inputs = array_merge(range('z', 'a'), range(0, 9), range('A', 'Z'));

        for ($i = 0; $i < 64; $i++) {
            $key .= $inputs{mt_rand(0, 61)};
        }

        $key = hash('sha256', $key);

        # Combine the userid with the ipaddress to make our cookie string
        $cookie_string = $user_id . ":" . $ipaddress;

        # Encrypt our cookie
        $cookie = $this->encrypt($cookie_string, $key);

        $data = array(
            "user_id" => $user_id,
            "ip_address" => $ipaddress,
            "data" => $cookie,
            "rnd_key" => $key
        );

        # Store our key and cookie in our ciauth_sessions table.
        $this->CI->m_ciauth->store_ciauth_session($user_id, $data);

        # Set the cookie and only allow it for 24 hours.
        setcookie('rememberme', $cookie, time() + (86400 * 30), "/");
    }

    /*
     * Function: encrypt
     * Parameters: pure_string, encryption_key
     * 
     * Encypts the pure_string using the encryption key. We use this with 
     * our ciauth sessions so that no one can guess our key. 
     */

    function encrypt($string, $key) {
        $encrypt_method = "AES-256-CBC";
        $iv = substr(hash('sha256', 'lkj53459uu09fsdajl'), 0, 16);
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
        return $output;
    }

    /*
     * Function: decrypt
     * Parameters: encrypted_string, encryption_key
     * 
     * Decrypts the encrypted_string using the encryption key. We store the 
     * key in the database and it is only good for 24 hours. 
     */

    function decrypt($string, $key) {
        $encrypt_method = "AES-256-CBC";
        $iv = substr(hash('sha256', 'lkj53459uu09fsdajl'), 0, 16);
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        return $output;
    }

    function remember_me() {
        $cookie = $this->CI->input->cookie('rememberme', TRUE);
        if ($cookie) {
            $results = $this->CI->m_ciauth->get_session_from_cookie($cookie);
            foreach ($results AS $result) {
                $user_id = $result->user_id;
                $ip_address = $result->ip_address;
                $rnd_key = $result->rnd_key;
                $data = $result->data;
            }

            # We have to verify that this user is valid.
            $string = $this->decrypt($data, $rnd_key);
            $cookie_data = explode(":", $string);

            if ($user_id == $cookie_data[0] && $this->CI->input->ip_address() == $ip_address) {
                # This user checks out lets see if they want us to keep them logged in.
                $user_data = $this->CI->m_ciauth->check_keep_logged_in($user_id);
                if ($user_data->remember_me == 'Y') {
                    $this->CI->session->set_userdata("user_id", $user_id);
                    $this->set_ciauth_session($user_id, $ipaddress);
                }
            }
        }
    }

    /*
     * Function: get_registration_form
     * This function creates a registration form that can be displayed on a page.
     */

    public function get_registration_form() {

        $registration_form = "<div class=\"container\">";
        $attributes = array('class' => 'form-register');
        $registration_form .= form_open('', $attributes);
        $registration_form .= "<h2 class=\"form-register-heading\">Please Register</h2>";

        # email value field
        $options = array(
            'name' => 'email_value',
            'type' => 'email',
            'id' => 'email_value',
            'class' => 'form-control',
            'placeholder' => 'Email Address',
            'size' => '25'
        );

        $registration_form .= form_error('email_value');
        $registration_form .= form_input($options);

        # username value field
        $options = array(
            'name' => 'username_value',
            'type' => 'text',
            'id' => 'username_value',
            'class' => 'form-control',
            'placeholder' => 'User Name',
            'size' => '25'
        );

        $registration_form .= form_error('username_value');
        $registration_form .= form_input($options);

        # password field
        $options = array(
            'name' => 'password',
            'id' => 'password',
            'class' => 'form-control',
            'placeholder' => 'Password',
            'size' => '25'
        );

        $registration_form .= form_error('password');
        $registration_form .= form_password($options);
        
        # confirm password field
        $options = array(
            'name' => 'confirm-password',
            'id' => 'confirm-password',
            'class' => 'form-control',
            'placeholder' => 'Confirm Password',
            'size' => '25'
        );

        $registration_form .= form_error('confirm-password');
        $registration_form .= form_password($options);
        $registration_form .= "<div class=\"g-recaptcha\" data-sitekey=\"" . $this->CI->config->item('recaptcha_sitekey') . "\"></div>";

        $registration_form .= "<div id='message'></div>";
        $registration_form .= "<p>&nbsp;</p>";

        $options = array(
            'name' => 'submit',
            'id' => 'login_button',
            'class' => 'btn btn-lg btn-primary btn-block',
            'value' => 'submit',
            'type' => 'submit',
            'content' => 'Register'
        );

        $registration_form .= form_button($options);
        $registration_form .= form_close();
        $registration_form .= "</div>";

        return $registration_form;
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

    function register($query_data) {

        $password = password_hash($query_data['password'], PASSWORD_DEFAULT);

        //ensure the email is unique
        if ($this->check_user_exists($query_data['email'], $query_data['username'])) {
            $data = array(
                "username" => $query_data['username'],
                "email" => $query_data['email'],
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
