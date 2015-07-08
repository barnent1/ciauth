$(document).ready(function () {

    $('.sortable').nestedSortable({
        handle: 'div',
        items: 'li',
        toleranceElement: '> div'
    });

    $('#add_menu_item').on('click', function (e) {
        e.preventDefault();
        var list_count = $(".sortable li").length + 1;
        var list_html = "<li id='List_" + list_count + "'><div>New Item</div></li>";
        $('.sortable').append(list_html);
    });

    $('#save_menu_item').on('click', function (e) {
        e.preventDefault();
        arraied = $('ol.sortable').nestedSortable('toArray', {startDepthCount: 0});
        $.each(arraied, function(key, value){
            console.log(value);
        });
 
    });

});