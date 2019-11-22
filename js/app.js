(function ($, window) {

    $.fn.contextMenu = function (settings) {

        return this.each(function () {

            // open context menu
            $(this).on("contextmenu", function (e) {
                // return native menu if pressing control
                if (e.ctrlKey) return;
                
                //open menu
                $(settings.menuSelector)
                    .data("invokedOn", $(e.target))
                    .show()
                    .css({
                        left: getMenuPosition(e.pageX, 'width'),
                        top: getMenuPosition(e.pageY, 'height')
                    })
                    .addClass('show')
                    .off('click')
                    .on('click', function (e) {
                        $("#context-menu").removeClass("show").hide();
                
                        var $invokedOn = $(this).data("invokedOn").attr('id');
                        var $selectedMenu = $(e.target);
                        
                        settings.menuSelected.call(this, $invokedOn, $selectedMenu);
                });
                
                return false;
            });

            //make sure menu closes on any click
            $(document).click(function () {
                $(settings.menuSelector).hide();
            });
        });
        
        function getMenuPosition(mouse, direction) {
            var win = $(window)[direction]();
            var page = $(document)[direction]();
            var menu = $(settings.menuSelector)[direction]();
            
            // opening menu would pass the side of the page
            if (mouse + menu > page && menu < mouse) {
                return mouse - menu;
            } 
            return mouse;
        }    

    };
})(jQuery, window);

$('.container .file-col').contextMenu({
    menuSelector: "#context-menu",
    menuSelected: function (invokedOn, selectedMenu) {
        //var msg = "You selected the menu item '" + selectedMenu.text() + "' on the value '" + invokedOn + "'";
        //console.log(msg);
        var file = $('#'+invokedOn+'-col');
        var fileId = invokedOn.replace("file-", '');
        switch (selectedMenu.text()) {
            case "Download":
                break;
            case "Rename":
                // TODO:
                // show edit name input of file
                // set #file-action-form .new_name
                // rename file in db
                doFileAction('rename', fileId);
                break;
            case "Delete":
                // TODO:
                // set in_trash to 1 in db
                // count files in row then replace with blank to complete row
                doFileAction('delete', fileId);
                file.remove();
                $('.container .row').append('<div class="file-blank-col"></div>');
                break;
            case "Restore":
                // TODO:
                // set in_trash to 0 in db
                // count files in row then replace with blank to complete row
                doFileAction('restore', restore);
                $('.container .row').append('<div class="file-blank-col"></div>');
                break;
        }
    }
});

function doFileAction(action, file) {
    var form = $('#file-action-form');
    form.find('.action').val(action);
    form.find('.file').val(file);
    form.ajaxSubmit({url: 'action.php', type: 'post'});
}

$('#form-upload').on('change', function() {
    $('#form-upload').submit();
});

$(function() {
    //var bar = $('.bar');
    //var percent = $('.percent');
    //var status = $('#status');

    $('#form-upload').ajaxForm({
        beforeSend: function() {
            //status.empty();
            var percentVal = '0%';
            //bar.width(percentVal);
            //percent.html(percentVal);
        },
        uploadProgress: function(event, position, total, percentComplete) {
            var percentVal = percentComplete + '%';
            //bar.width(percentVal);
            //percent.html(percentVal);
            console.log(percentVal);
        },
        complete: function(xhr) {
            //status.html(xhr.responseText);
            console.log(xhr.responseText);
        }
    });
});