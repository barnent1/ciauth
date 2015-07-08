<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * Name: ciauth
 * File: V_nav_admin.php
 * Path: views/ciauth/admin/V_nav_admin.php
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

foreach ($nav_elements AS $element) {
    
}
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

<!-- Latest compiled and minified jQuery UI -->
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script src="http://www.ciauth.com/js/jquery.mjs.nestedSortable.js"></script>
<script src="http://www.ciauth.com/js/ciauth_admin.js"></script>
<style type="text/css">

    a, a:visited {
        color: #4183C4;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }

    .placeholder {
        outline: 1px dashed #4183C4;
        /*-webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        margin: -1px;*/
    }

    .mjs-nestedSortable-error {
        background: #fbe3e4;
        border-color: transparent;
    }

    ol {
        margin: 0;
        padding: 0;
        padding-left: 30px;
    }

    ol.sortable, ol.sortable ol {
        margin: 0 0 0 25px;
        padding: 0;
        list-style-type: none;
    }

    ol.sortable {
        margin: 4em 0;
    }

    .sortable li {
        margin: 5px 0 0 0;
        padding: 0;
    }

    .sortable li div  {
        border: 1px solid #d4d4d4;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        border-color: #D4D4D4 #D4D4D4 #BCBCBC;
        padding: 6px;
        margin: 0;
        cursor: move;
        background: #f6f6f6;
        background: -moz-linear-gradient(top,  #ffffff 0%, #f6f6f6 47%, #ededed 100%);
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(47%,#f6f6f6), color-stop(100%,#ededed));
        background: -webkit-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
        background: -o-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
        background: -ms-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
        background: linear-gradient(to bottom,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#ededed',GradientType=0 );
    }

    .sortable li.mjs-nestedSortable-branch div {
        background: -moz-linear-gradient(top,  #ffffff 0%, #f6f6f6 47%, #f0ece9 100%);
        background: -webkit-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#f0ece9 100%);

    }

    .sortable li.mjs-nestedSortable-leaf div {
        background: -moz-linear-gradient(top,  #ffffff 0%, #f6f6f6 47%, #bcccbc 100%);
        background: -webkit-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#bcccbc 100%);

    }

    li.mjs-nestedSortable-collapsed.mjs-nestedSortable-hovering div {
        border-color: #999;
        background: #fafafa;
    }

    .disclose {
        cursor: pointer;
        width: 10px;
        display: none;
    }

    .sortable li.mjs-nestedSortable-collapsed > ol {
        display: none;
    }

    .sortable li.mjs-nestedSortable-branch > div > .disclose {
        display: inline-block;
    }

    .sortable li.mjs-nestedSortable-collapsed > div > .disclose > span:before {
        content: '+ ';
    }

    .sortable li.mjs-nestedSortable-expanded > div > .disclose > span:before {
        content: '- ';
    }

    p, ol, ul, pre, form {
        margin-top: 0;
        margin-bottom: 1em;
    }

    dl {
        margin: 0;
    }

</style>

<div class="container">
<a href="" id="add_menu_item">Add Menu Item</a>
<a href="" id="save_menu_item">Save Menu</a>

<ol class="sortable">
    <li id="list_1"><div>Home</div></li>
    <li id="list_2">
        <div>About</div>
        <ol>
            <li id="list_3"><div>Support</div></li>
            <li id="list_4"><div>Help</div></li>
        </ol>
    </li>
    <li id="list_5"><div>More Information</div></li>
</ol>
</div>