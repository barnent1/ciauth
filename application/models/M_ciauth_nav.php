<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * Name: M_ciauth_navigation
 * File: M_ciauth_navigation.php
 * Path: models/M_ciauth_navigation.php
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

class M_ciauth_nav extends CI_model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_parent_menus() {
        $sql = "SELECT * FROM `ciauth_navigation` WHERE `parent` IS NULL";
        $query = $this->db->query($sql);
        if ($query->result()) {
            $result = $query->result();
            return $result;
        } else {
            return false;
        }
    }

    public function get_menus() {
        $sql = "SELECT * FROM `ciauth_navigation`";
        $query = $this->db->query($sql);
        if ($query->result()) {
            $result = $query->result();
            return $result;
        } else {
            return false;
        }
    }

    public function get_submenus($menu_name) {
        $sql = "SELECT * FROM `ciauth_navigation` WHERE `parent` = '" . $menu_name . "'";
        $query = $this->db->query($sql);
        if ($query->result()) {
            $result = $query->result();
            return $result;
        } else {
            return false;
        }
    }

    public function update_menus($data) {
        if ($this->db->empty_table('ciauth_navigation')) {
            if (!$this->db->insert_batch('ciauth_navigation', $data)) {
                return false;
            }else{
                return true;
            }
        } else {
            return false;
        }
    }

}
