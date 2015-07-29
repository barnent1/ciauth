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
?>
<link rel="stylesheet" href="http://www.ciauth.com/css/ciauth_navigation.css">

<div class="container">
    <h3>CIAUTH | Admin | Navigation Builder</h3>

    <div class="container menu-drag">
        <button type="button" id="add_menu_item" class="btn btn-primary">Add Menu Item</button>
        <button type="button" id="save_menu_item" class="btn btn-primary">Save Menu</button>
        <button type="button" id="delete_menu_items" class="btn btn-primary">Delete Menu Items</button>
        <ol class="sortable">
            <?php echo $nav_admin_menu; ?>
        </ol>
    </div>
    <div class="row">
        <div class="jumbotron">
            <h3>How to Use:</h3>
            <h4>Ordering Menu Items</h4>
            <p>Drag and Drop to order and organize your menu items. Dragging to the right indents and creates parent child menus.</p> 
            <h4>Adding Menu Items</h4>
            <p>Click the Add Menu Item button to add a new item. Double-click on an item to set the name and anchor properties.</p>
            <h4>Deleting Menu Items</h4>
            <p>Click on a check-box to mark a menu item and it's children for deletion. It is best to re-arrange the menu so that items you don't want deleted are not in the children of any checked item.</p>
            <h4>Saving the Menu</h4>
            <p>Click on the save button to save your menu when your satisfied.</p>
        </div>
    </div>
</div>