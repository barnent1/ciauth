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

        $login_form = form_open('');

        # login value field
        $options = array(
            'name' => 'login_value',
            'type' => 'name',
            'id' => 'login_value',
            'class' => 'form_field',
            'size' => '30'
        );


        $login_form .= form_error('login_value');
        $login_form .= form_label('Username or Email: ', 'login_value');
        $login_form .= form_input($options);

        # password field
        $options = array(
            'name' => 'password',
            'id' => 'password',
            'class' => 'form_field',
            'size' => '20'
        );

        $login_form .= form_error('password');
        $login_form .= form_label('Password: ', 'password');
        $login_form .= form_password($options);
        $login_form .= "<p><a href='forgot_password'>Forgot your password?</a></p>";

        $login_form .= "<div id='message'></div>";

        $login_form .= "<div id='lower'>";
        $options = array(
            'name' => 'keep_logged_in',
            'id' => 'keep_logged_in',
            'value' => 'accept',
            'checked' => TRUE,
        );

        $login_form .= form_label('Keep me logged in: ', 'keep_logged_in');
        $login_form .= form_checkbox($options);

        $options = array(
            'name' => 'submit',
            'id' => 'login_button',
            'value' => 'Login',
            'class' => 'login_button'
        );

        $login_form .= form_submit($options);
        $login_form .= form_close();
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

    function get_registration_form() {
        $registration_form = form_open('');
        # firstname value field
        $options = array(
            'name' => 'firstname',
            'id' => 'firstname',
            'class' => 'form_field',
            'size' => '30'
        );
        $registration_form .= form_error('firstname');
        $registration_form .= form_label('First Name: ', 'firstname');
        $registration_form .= form_input($options);

        # lastname value field
        $options = array(
            'name' => 'lastname',
            'id' => 'lastname',
            'class' => 'form_field',
            'size' => '30'
        );
        $registration_form .= form_error('lastname');
        $registration_form .= form_label('Last Name: ', 'lastname');
        $registration_form .= form_input($options);

        # email value field
        $options = array(
            'name' => 'email',
            'id' => 'email',
            'class' => 'form_field',
            'size' => '30'
        );
        $registration_form .= form_error('email');
        $registration_form .= form_label('Email: ', 'email');
        $registration_form .= form_input($options);

        # address1 value field
        $options = array(
            'name' => 'address1',
            'id' => 'address1',
            'class' => 'form_field',
            'size' => '30'
        );
        $registration_form .= form_error('address1');
        $registration_form .= form_label('Address: ', 'address1');
        $registration_form .= form_input($options);

        # address2 value field
        $options = array(
            'name' => 'address2',
            'id' => 'address2',
            'class' => 'form_field',
            'size' => '30'
        );
        $registration_form .= form_error('address2');
        $registration_form .= form_label('Apt/Suite/PO: ', 'address2');
        $registration_form .= form_input($options);

        # city value field
        $options = array(
            'name' => 'city',
            'id' => 'city',
            'class' => 'form_field',
            'size' => '30'
        );
        $registration_form .= form_error('city');
        $registration_form .= form_label('City: ', 'city');
        $registration_form .= form_input($options);

        # state value field

        $options = array(
            'AL' => 'Alabama',
            'AK' => 'Alaska',
            'AZ' => 'Arizona',
            'AR' => 'Arkansas',
            'CA' => 'California',
            'CD' => 'Colorado',
            'CT' => 'Connecticut',
            'DE' => 'Delaware',
            'DC' => 'District Of Columbia',
            'FL' => 'Florida',
            'GA' => 'Georgia',
            'HI' => 'Hawaii',
            'ID' => 'Idaho',
            'IL' => 'Illinois',
            'IN' => 'Indiana',
            'IA' => 'Iowa',
            'KS' => 'Kansas',
            'KY' => 'Kentucky',
            'LA' => 'Louisiana',
            'ME' => 'Maine',
            'MD' => 'Maryland',
            'MA' => 'Massachusetts',
            'MI' => 'Michigan',
            'MN' => 'Minnesota',
            'MS' => 'Mississippi',
            'MO' => 'Missouri',
            'MT' => 'Montana',
            'NE' => 'Nebraska',
            'NV' => 'Nevada',
            'NH' => 'New Hampshire',
            'NJ' => 'New Jersey',
            'NM' => 'New Mexico',
            'NY' => 'New York',
            'NC' => 'North Carolina',
            'ND' => 'North Dakota',
            'OH' => 'Ohio',
            'OK' => 'Oklahoma',
            'OR' => 'Oregon',
            'PA' => 'Pennsylvania',
            'RI' => 'Rhode Island',
            'SC' => 'South Carolina',
            'SD' => 'South Dakota',
            'TN' => 'Tennessee',
            'TX' => 'Texas',
            'UT' => 'Utah',
            'VT' => 'Vermont',
            'VA' => 'Virginia',
            'WA' => 'Washington',
            'WV' => 'West Virginia',
            'WI' => 'Wisconsin',
            'WY' => 'Wyoming'
        );

        $extra = array(
            'name' => 'state',
            'id' => 'state',
        );
        $registration_form .= form_error('state');
        $registration_form .= form_label('State: ', 'state');
        $registration_form .= form_dropdown('state', $options, '', $extra);
        
        # zipcode value field
        $options = array(
            'name' => 'zipcode',
            'id' => 'zipcode',
            'class' => 'form_field',
            'size' => '30'
        );
        $registration_form .= form_error('zipcode');
        $registration_form .= form_label('Zipcode: ', 'zipcode');
        $registration_form .= form_input($options);

        # username value field
        /* $options = array(
          'name' => 'username',
          'id' => 'username',
          'class' => 'form_field',
          'size' => '30'
          ); */
        //$registration_form .= form_error('username');
        //$registration_form .= form_label('Username: ', 'username');
        //$registration_form .= form_input($options);
        # password field
        $options = array(
            'name' => 'password',
            'id' => 'password',
            'class' => 'form_field',
            'size' => '20'
        );

        $registration_form .= form_error('password');
        $registration_form .= form_label('Password: ', 'password');
        $registration_form .= form_password($options);

        # conf password field
        $options = array(
            'name' => 'conf_password',
            'id' => 'conf_password',
            'class' => 'form_field',
            'size' => '20'
        );

        $registration_form .= form_error('conf_password');
        $registration_form .= form_label('Confirm Password: ', 'conf_password');
        $registration_form .= form_password($options);

        $registration_form .= "<div id='message'></div>";
        $registration_form .= "<div id='lower'>";

        $options = array(
            'name' => 'submit',
            'id' => 'button',
            'value' => 'Register',
            'class' => 'button'
        );

        $registration_form .= form_submit($options);
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
                "firstname" => $query_data['firstname'],
                "lastname" => $query_data['lastname'],
                "address1" => $query_data['address1'],
                "address2" => $query_data['address2'],
                "city" => $query_data['city'],
                "state" => $query_data['state'],
                "zipcode" => $query_data['zipcode'],
                "username" => $query_data['email'],
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
