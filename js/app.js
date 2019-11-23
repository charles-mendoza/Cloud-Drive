var files = [];

$(function() {
    // adjust page alignments
    if ($('.file-col').length > 0) {
        $('#btnEmptyTrash').removeClass('btn-disabled');
        $('#btnEmptyTrash').addClass('btn-danger');
        $('footer').css('bottom','auto');
        files = $('.file-col');
    } else {
        $(this).addClass('login-page');
        $('#sectionsNav').addClass('fixed-top');
    }
    $('footer').removeClass('d-none');

    // upload progress bar
    var bar = $('.progress-bar');
    $('#form-upload').ajaxForm({
        beforeSend: function() {
            var percentVal = '0%';
            bar.width(percentVal);
            $('#modal-upload').modal('toggle');
        },
        uploadProgress: function(event, position, total, percentComplete) {
            var percentVal = percentComplete + '%';
            bar.width(percentVal);
        },
        complete: function(xhr) {
        }
    });
});

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
        var file = $('#'+invokedOn+'-col');
        var fileId = invokedOn.replace("file-", '');
        // var msg = "You selected the menu item '" + selectedMenu.text() + "' on the value '" + invokedOn + "'";
        // console.log(msg);
        // console.log(file, fileId);
        switch (selectedMenu.text()) {
            case "Download":
                var name = $('#file-'+fileId+'-name').val();
                var ext = $('#file-'+fileId+'-ext').val();
                var file = name+ext;
                $('#download').attr('href', "uploads/"+file);
                $('#download').prop('download', file);
                break;
            case "Rename":
                $('#newName').attr('value', $('#file-'+fileId+'-name').val());
                $('#rename-id').attr('value', fileId);
                break;
            case "Restore":
                doFileAction('restore', fileId);
                file.remove();
                updateFileCols();
                break;
            case "Delete":
                doFileAction('delete', fileId);
                file.remove();
                updateFileCols();
                break;
        }
    }
});

function updateFileCols() {
    $('.file-blank-col').remove();
    var col = $('.file-col').length;
    var rem = (6 - (col % 6)) % 6;
    for (var i = 0; i < rem; ++i) {
        $('.container .row').append('<div class="file-blank-col"></div>');
    }
}

function doFileAction(action, file, rename=false) {
    var form = $('#file-action-form');
    var post_url = form.attr("action");
    var request_method = form.attr("method");
    $('#file-action').attr('value', action);
    $('#file-id').attr('value', file);
    $('#file-action-form').ajaxSubmit({
        url: post_url,
        type: request_method,
        success: function(data) {
            if (rename) {
                $('#file-'+file+'-name').attr('value', data);
                data += $('#file-'+file+'-ext').val();
                renameFile($('#rename-id').val(), data);
            }
        }
    });
}

$("#newName").keyup(function(event) {
    if (event.keyCode === 13) {
        $("#btnRename").click();
    }
});

$('#btnRename').on('click', function(e) {
    e.preventDefault();
    $('#file-rename').attr('value', $('#newName').val());
    doFileAction('rename', $('#rename-id').val(), true);
    $('#modal-rename').modal('toggle');
});

function renameFile(id, name) {
    name = name.length > 13 ? name.substr(0, 10)+'...' : name;
    $('#file-'+id+'-col p').html(name);
}

$('#btnEmptyTrash').on('click', function() {
    $('#file-action').attr('value', 'empty_trash');
    $('#file-action-form').submit();
});

$('#search').on('input', function() {
    if ($(this).val().length > 0) {
        for (var i = 0; i < files.length; ++i) {
            var name = $(files[i]).find('input[id*="-name"]').val();
            var ext = $(files[i]).find('input[id*="-ext"]').val();
            var file = name+ext;
            if (file.search($(this).val()) == -1) {
                $('#'+$(files[i]).attr('id')).remove();
            }
        }
    } else {
        for (var i = 0; i < files.length; ++i) {
            if ($('#'+$(files[i]).attr('id')).length == 0) {
                $('.container .row').append($(files[i]));
            }
        }
    }
    updateFileCols();
});

$('#form-upload').on('change', function() {
    $('#form-upload').submit();
});