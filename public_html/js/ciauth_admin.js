$(document).ready(function () {
    /*
     * We use the nested sorted plugin for the admin navigation builder.
     */
    $('.sortable').nestedSortable({
        handle: 'form',
        items: 'li',
        toleranceElement: '> form',
        maxLevels: 6
    });

    /*
     * Add a menu item to the navigation builder.
     */
    $('#add_menu_item').on('click', function (e) {
        e.preventDefault();
        menu_list = [];
        var list_count = 0;
        $('ol.sortable').find('li').each(function () {
            menu_list.push($(this).attr('id'));
        });
        if (typeof menu_list !== 'undefined' && menu_list.length > 0) {
            list_count = Math.max.apply(Math, menu_list);
        }

        list_count++;
        var list_html = "<li id='" + list_count + "'><form action='' class='form-inline ui-sortable-handle'><div class='form-group'><input type='checkbox'></div><div class='form-group' id='dd-menu-item-" + list_count + "'>Menu Name</div><div class='form-group' id='dd-menu-anchor-" + list_count + "'>/menu_anchor</div></form></li>";
        $('.sortable').append(list_html);
    });
    
    /*
     * Save the menu. This calls recursive functions for up to 6 levels
     */

    $('#save_menu_item').on('click', function (e) {
        e.preventDefault();
        saveMenu();
    });

    /*
     * function: saveMenu
     * Saves all items in the menu.
     */
    function saveMenu(){
        data = [];
        parent = 0;
        order = 0;

        $('ol.sortable').children("li").each(function () {
            processMenuItem($(this), parent);
        });
        
        var ajax_url = "/update_nav_ajax";
        $.ajax({url: ajax_url,
            data: {menu: data},
            type: 'POST',
            success: function (data) {
                if (data == "ERROR") {
                    swal("ERROR!", "There was an error trying to update your menu please notify the administrtor.", "error")
                } else {
                    window.location = "/nav_admin";
                }
            }
        });
    }
    
    /*
     * function processMenuItem()
     * @param {object} node
     * @param {value} parent
     */
    
    function processMenuItem(node, parent) {
        var id = node.attr('id');
        var menu_name = node.find('#dd-menu-item-' + id);
        var menu_anchor = node.find('#dd-menu-anchor-' + id);

        var retVal = {
            "id": id,
            "order": order,
            "name": menu_name.text(),
            "anchor": menu_anchor.text(),
            "parent": parent,
            "permissions": ""
        };

        data.push(retVal);
        order++;

        /*
         * Process the menu node children
         */

        node.find("ol:first").children("li").each(function () {
            parent = id;
            processMenuItemChildren($(this), parent);
        });

        return;
    }
    
    /*
     * function processMenuItemChildren()
     * @param {object} node
     * @param {value} parent
     */

    function processMenuItemChildren(node, parent) {
        var id = node.attr('id');
        var menu_name = node.find('#dd-menu-item-' + id);
        var menu_anchor = node.find('#dd-menu-anchor-' + id);

        var retVal = {
            "id": id,
            "order": order,
            "name": menu_name.text(),
            "anchor": menu_anchor.text(),
            "parent": parent,
            "permissions": ""
        };

        data.push(retVal);
        order++;

        node.find("ol:first").children("li").each(function () {
            parent = id;
            processMenuItemLevel2Children($(this), parent);
        });

        return;
    }
    
    /*
     * function processMenuItemLevel2Children()
     * @param {object} node
     * @param {value} parent
     */

    function processMenuItemLevel2Children(node, parent) {
        var id = node.attr('id');
        var menu_name = node.find('#dd-menu-item-' + id);
        var menu_anchor = node.find('#dd-menu-anchor-' + id);

        var retVal = {
            "id": id,
            "order": order,
            "name": menu_name.text(),
            "anchor": menu_anchor.text(),
            "parent": parent,
            "permissions": ""
        };

        data.push(retVal);
        order++;


        node.find("ol:first").children("li").each(function () {
            parent = id;
            processMenuItemLevel3Children($(this), parent);
        });

        return;
    }

    /*
     * function processMenuItemLevel3Children()
     * @param {object} node
     * @param {value} parent
     */
    function processMenuItemLevel3Children(node, parent) {
        var id = node.attr('id');
        var menu_name = node.find('#dd-menu-item-' + id);
        var menu_anchor = node.find('#dd-menu-anchor-' + id);

        var retVal = {
            "id": id,
            "order": order,
            "name": menu_name.text(),
            "anchor": menu_anchor.text(),
            "parent": parent,
            "permissions": ""
        };

        data.push(retVal);
        order++;

        node.find("ol:first").children("li").each(function () {
            parent = id;
            processMenuItemLevel4Children($(this), parent);
        });

        return;
    }

    /*
     * function processMenuItemLevel4Children()
     * @param {object} node
     * @param {value} parent
     */
    function processMenuItemLevel4Children(node, parent) {
        var id = node.attr('id');
        var menu_name = node.find('#dd-menu-item-' + id);
        var menu_anchor = node.find('#dd-menu-anchor-' + id);

        var retVal = {
            "id": id,
            "order": order,
            "name": menu_name.text(),
            "anchor": menu_anchor.text(),
            "parent": parent,
            "permissions": ""
        };

        data.push(retVal);
        order++;

        node.find("ol:first").children("li").each(function () {
            parent = id;
            processMenuItemLevel5Children($(this), parent);
        });

        return;
    }

    /*
     * function processMenuItemLevel5Children()
     * @param {object} node
     * @param {value} parent
     */
    function processMenuItemLevel5Children(node, parent) {
        var id = node.attr('id');
        var menu_name = node.find('#dd-menu-item-' + id);
        var menu_anchor = node.find('#dd-menu-anchor-' + id);

        var retVal = {
            "id": id,
            "order": order,
            "name": menu_name.text(),
            "anchor": menu_anchor.text(),
            "parent": parent,
            "permissions": ""
        };

        data.push(retVal);
        order++;

        return;
    }

    /*
     * Delete Checked Menu Items. This will delete all children items if a
     * parent is checked.
     */
    $('#delete_menu_items').on('click', function (e) {
        e.preventDefault();
        $('ol.sortable').find("li").each(function () {
            $(this).find("input:first").each(function (){
                if($(this).prop("checked")){
                    var id = $(this).val();
                    console.log(id);
                    $('ol.sortable').find('#' + id).remove();
                }
            });
        });
    });

    /*
     * Double Click on Menu Name and Menu Anchor to change the name and anchor.
     */
    $('.menu-drag').on('dblclick', 'div', function () {

        if ($(this).find('input[type=checkbox]').length == 0) {
            menu_name = $(this).text();

            var to_append = "<input type='text' name='menu_name' value='" + menu_name + "'>";

            $(this).text("");
            $(to_append).appendTo(this).focus();
        }
    });

    /*
     * If return Key is pressed complete the menu item edit.
     */
    $('.menu-drag').on('keydown', 'div > input[type=text]', function (e) {
        if (e.keyCode == 13 || e.which == 9) {
            var $this = $(this);

            if ($this.val() != "") {
                $this.parent().text($this.val());
                $this.remove();
            } else {
                $this.parent().text(menu_name);
                $this.remove();
            }
        }
    });

    /*
     * If the focus is moved outside of the name or anchor complete the item.
     */
    $('.menu-drag').on('focusout', 'div > input[type=text]', function (e) {
        var $this = $(this);

        if ($this.val() != "") {
            $this.parent().text($this.val());
            $this.remove();
        } else {
            $this.parent().text(menu_name);
            $this.remove();
        }
    });

});