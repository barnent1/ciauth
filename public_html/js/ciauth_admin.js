$(document).ready(function () {

    $('.sortable').nestedSortable({
        handle: 'div',
        items: 'li',
        toleranceElement: '> div'
    });

    $('#add_menu_item').on('click', function (e) {
        e.preventDefault();
        var list_count = $(".sortable li").length + 1;
        var list_html = "<li id='list_" + list_count + "'><div class='ui-sortable-handle'>New Item | /new_item</div></li>";
        $('.sortable').append(list_html);
    });

    $('#save_menu_item').on('click', function (e) {
        e.preventDefault();
        var nav_items = $('ol.sortable').nestedSortable('toArray', {startDepthCount: 0});
        var data = [];
        var count = 0;
        $.each(nav_items, function (key, value) {
            if (value['item_id'] != null) {
                var list_element = ".menu-drag #list_" + count;
                var menu_item = $(list_element).find('div').text();
                var menu_parts = menu_item.split("|");
                var menu_name = menu_parts[0];
                var menu_action = menu_parts[1];
                var item = {
                    'id': count,
                    'name': menu_name.trim(),
                    'anchor': menu_action.trim(),
                    'parent': value["parent_id"],
                    'permissions': ''
                }
                data.push(item);
            }
            count++;
        });
        
        var ajax_url = "/update_nav_ajax";
        $.ajax({url: ajax_url,
            data: {menu: data},
            type: 'POST',
            success: function (data) {
                if (data == "ERROR") {
                    swal("ERROR!", "There was an error trying to update your menu please notify the administrtor.", "error")
                }else{
                    window.location = "/nav_admin";
                }
            }
        });

    });

    $('.menu-drag').on('dblclick', 'div', function () {
        menu_name = $(this).text();

        var to_append = "<input type='text' name='menu_name' value='" + menu_name + "'>";

        $(this).text("");
        $(to_append).appendTo(this).focus();
    });

    $('.menu-drag').on('focusout', 'div > input', function () {
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