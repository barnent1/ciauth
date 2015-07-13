$(document).ready(function () {

    $('.sortable').nestedSortable({
        handle: 'div',
        items: 'li',
        toleranceElement: '> div'
    });

    $('#add_menu_item').on('click', function (e) {
        e.preventDefault();
        var list_count = $(".sortable li").length + 1;
        var list_html = "<li id='List_" + list_count + "'><div class='ui-sortable-handle'>New Item</div></li>";
        $('.sortable').append(list_html);
    });

    $('#save_menu_item').on('click', function (e) {
        e.preventDefault();
        var nav_items = $('ol.sortable').nestedSortable('toArray', {startDepthCount: 0});
        var data = [];
        $.each(nav_items, function (key, value) {

            console.log(value);
        });

    });

    $('.menu-drag').on('dblclick', 'div', function () {
        menu_name = $(this).text();
        var to_append = "<input type='text' value='" + menu_name + "'>";
        $(this).text("");
        $(to_append).appendTo(this).focus();
    });

    $('.menu-drag').on('focusout', 'div > input', function () {
        var $this = $(this);
        console.log($this);
        if ($this.val() != "") {
            $this.parent().text($this.val());
            $this.remove();
        }else{
            $this.parent().text(menu_name);
            $this.remove();
        }
    });

});