<script language="javascript">

    function getLocation() {
        return '<?php echo BASE_URL;?>includes/controllers/ajax.video.php';
    }

    function getTableId() {
        return 'table_dnd';
    }

    /*************************** Shorting Video Postion *******************************/
    $(document).ready(function () {
        $(function () {
            $(".video-sort").sortable({
                //connectWith: ".video-sort",
                start: function (event, ui) {
                    var start_pos = ui.item.index();
                    ui.item.data('start_pos', start_pos);
                },
                update: function (event, ui) {
                    var mySel = "";
                    $('div.oldsort').each(function (i) {
                        mySel = mySel + ';' + $(this).attr('csort');
                    });
                    //var start_pos = ui.item.data('start_pos');
                    var id = ui.item.context.id;
                    var end_pos = ui.item.index();
                    $.ajax({
                        type: "POST",
                        dataType: "JSON",
                        url: getLocation(),
                        data: "action=sort&id=" + id + "&toPosition=" + end_pos + "&sortIds=" + mySel,
                        success: function (data) {
                            var msg = eval(data);
                            showMessage(msg.action, msg.message);
                        }
                    });
                }
            });
        });
    });
    

    $(document).ready(function () {
        // form submisstion actions
        jQuery('#video_frm').validationEngine({
            autoHidePrompt: true,
            scroll: false,
            onValidationComplete: function (form, status) {
                if (status == true) {
                    var vurl = $("#source").val();
                    validate_url(vurl);
                    $('#btn-submit').attr('disabled', 'true');
                    var action = ($('#idValue').val() == 0) ? "action=add&" : "action=edit&";
                    var data = $('#video_frm').serialize();
                    queryString = action + data;
                    $.ajax({
                        type: "POST",
                        dataType: "JSON",
                        url: getLocation(),
                        data: queryString,
                        success: function (data) {
                            var msg = eval(data);
                            if (msg.action == 'warning') {
                                showMessage(msg.action, msg.message);
                                $('#btn-submit').removeAttr('disabled');
                                $('.formButtons').show();
                                return false
                            }
                            if (msg.action == 'success') {
                                showMessage(msg.action, msg.message);
                                setTimeout(function () {
                                    window.location.href = window.location.href;
                                }, 3000);
                            }
                            if (msg.action == 'notice') {
                                showMessage(msg.action, msg.message);
                                setTimeout(function () {
                                    window.location.href = window.location.href;
                                }, 3000);
                            }
                            if (msg.action == 'error') {
                                showMessage(msg.action, msg.message);
                                $('#buttonsP img').remove();
                                $('.formButtons').show();
                                return false;
                            }
                        }
                    });
                    return false;
                }
            }
        })
        
jQuery('#videos_frm').validationEngine({
    prettySelect: true,
    autoHidePrompt: true,
    useSuffix: "_chosen",
    scroll: true,
    onValidationComplete: function (form, status) {
        if (status === true) {
            $('.btn-submit').attr('disabled', 'true');
            var action = "action=metadata&";
            var data = $('#videos_frm').serialize();
            var queryString = action + data;

            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: getLocation(),
                data: queryString,
                success: function (data) {
                    var msg = data; // already parsed JSON
                    console.log("AJAX response:", msg);

                    var actionId = $('#idValue').attr('myaction') ?? 0;
                    console.log("actionId:", actionId);

                    if (msg && msg.action) {
                        if (msg.action === 'warning') {
                            showMessage(msg.action, msg.message);
                            $('.btn-submit').removeAttr('disabled');
                            $('.formButtons').show();
                        } else if (msg.action === 'success') {
                            showMessage(msg.action, msg.message);
                            if (actionId == 0) {
                                setTimeout(function () {
                                    window.location.href = "<?php echo ADMIN_URL?>video/list";
                                }, 3000);
                            }
                        } else if (msg.action === 'notice') {
                            showMessage(msg.action, msg.message);
                            setTimeout(function () {
                                window.location.href = "<?php echo ADMIN_URL?>video/list";
                            }, 3000);
                        } else if (msg.action === 'error') {
                            showMessage(msg.action, msg.message);
                            $('#buttonsP img').remove();
                            $('.formButtons').show();
                        }
                    } else {
                        console.warn("Invalid AJAX response:", msg);
                        $('.btn-submit').removeAttr('disabled');
                        $('.formButtons').show();
                    }
                },
                error: function(xhr, status, error){
                    console.error("AJAX error:", error, xhr.responseText);
                    $('.btn-submit').removeAttr('disabled');
                    $('.formButtons').show();
                }
            });

            return false;
        }
    }
});
 

        function validate_url(url) {
            var youtube = url.search("youtu");
            var vimeo = url.search("vimeo");
            var soundcloud = url.search("soundcloud");
            var metacafe = url.search("metacafe");
            var dailymotion = url.search("dailymotion");

            if ((youtube != -1) || (vimeo != -1) || (soundcloud != -1) || (metacafe != -1) || (dailymotion != -1)) {
                if (youtube != -1) {
                    jQuery("#url_type").val('youtube');
                }
                if (vimeo != -1) {
                    jQuery("#url_type").val('vimeo');
                }
                if (soundcloud != -1) {
                    jQuery("#url_type").val('soundcloud');
                }
                if (metacafe != -1) {
                    jQuery("#url_type").val('metacafe');
                }
                if (dailymotion != -1) {
                    jQuery("#url_type").val('dailymotion');
                }
                return true;
            } else {
                return false;
            }
        }
    });
    /*************************************** Toggle Meta tags********************************************/	
        
    function toggleMetadata(){
	$( ".metadata" ).slideToggle("slow",function(){});
}

    /*************************************** Toggle AddEdit Form ********************************************/
    function toggleAddEdit() {
        $(".addEdit").slideToggle("slow", function () {
            var icval = $("#iconcols").attr("icoval");
            newicval = (icval == 1) ? 0 : 1;
            $('#iconcols').attr({'icoval': newicval});
            if (icval == 1) {
                $("#iconcols").removeClass('icon-plus-square');
                $("#iconcols").addClass('icon-minus-square');
                $(".newtext").html('Cancel');
            } else {
                $("#iconcols").removeClass('icon-minus-square');
                $("#iconcols").addClass('icon-plus-square');
                $(".newtext").html('Add New');
                $('#video_frm')[0].reset();
            }

        });
    }

    // Edit records
    function editVideoTitle(Re) {
        var clicked = $('.vidclicked' + Re);
        $(clicked).html("");
        $('<input/>').attr({
            type: 'text',
            id: 'ne-title',
            name: 'ne-title',
            class: 'validate[required,length[0,250]] col-md-9',
            'vidId': Re
        }).appendTo($(clicked)).focus();
        $(clicked).append(' <button type="submit" id="ne-submit" class="col-md-3">Save</button>');

        $('.up-title').on("click", "#ne-submit", function (e) {
            var data = $("#ne-title");
            var id = $(data).attr("vidId");
            var title = $(data).val();
            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: getLocation(),
                data: 'action=editVideoTitle&id=' + id + '&title=' + title,
                success: function (data) {
                    var msg = eval(data);
                    if (msg.action == 'success') {
                        showMessage(msg.action, msg.message);
                        setTimeout(function () {
                            window.location.href = window.location.href;
                        }, 3000);
                    }
                    if (msg.action == 'error') {
                        showMessage(msg.action, msg.message);
                        setTimeout(function () {
                            window.location.href = window.location.href;
                        }, 3000);
                    }
                    if (msg.action == 'notice') {
                        showMessage(msg.action, msg.message);
                        setTimeout(function () {
                            window.location.href = window.location.href;
                        }, 3000);
                    }
                }
            });
        });
    }

    function editRecord(Re) {
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: getLocation(),
            data: 'action=editExistsRecord&id=' + Re,
            success: function (data) {
                var msg = eval(data);
                $("#title").val(msg.title);
                $("#source").val(msg.vsource);
                $("#url_type").val(msg.url_type);
                $("#idValue").val(msg.editId);
                $('html, body').animate({ scrollTop: $("#video_frm").offset().top - 50 }, 500);
                $("#title").focus();
            }
        });
    }

    $(function () {
        $('#video_frm')[0].reset();
        $('#btn-submit').removeAttr('disabled');
    });

    // Deleting Record
    function recordDelete(Re) {
        $('.MsgTitle').html('Do you want to delete the record ?');
        $('.pText').html('Clicking yes will be delete this record permanently. !!!');
        $('.divMessageBox').fadeIn();
        $('.MessageBoxContainer').fadeIn(1000);

        $(".botTempo").on("click", function () {
            var popAct = $(this).attr("id");
            if (popAct == 'yes') {
                $.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: getLocation(),
                    data: 'action=delete&id=' + Re,
                    success: function (data) {
                        var msg = eval(data);
                        //showMessage(msg.action,msg.message);
                        $('#' + Re).remove();
                        Re = null;
                    }
                });
            } else {
                Re = null;
            }
            $('.divMessageBox').fadeOut();
            $('.MessageBoxContainer').fadeOut(1000);
        });
    }

    $(function () {
        /*************************************** USer Video Status Toggler ******************************************/
        $('.videoStatusToggle').on('click', function () {
            var Re = $(this).attr('rowId');
            var status = $(this).attr('status');
            newStatus = (status == 1) ? 0 : 1;
            $.ajax({
                type: "POST",
                url: getLocation(),
                data: "action=toggleStatus&id=" + Re,
                success: function (msg) {
                }
            });
            $(this).attr({'status': newStatus});
            if (status == 1) {
                $('#toggleImg' + Re).removeClass("icon-check-circle-o");
                $('#toggleImg' + Re).addClass("icon-clock-os-circle-o");
            } else {
                $('#toggleImg' + Re).removeClass("icon-clock-os-circle-o");
                $('#toggleImg' + Re).addClass("icon-check-circle-o");
            }
        });
    });

    //For Video Popup on fancybox
    jQuery(document).ready(function ($) {
        $('.youtube, .vimeo, .metacafe, .dailymotion')
            .attr('rel', 'media-gallery')
            .fancybox({
                openEffect: 'none',
                closeEffect: 'none',
                prevEffect: 'none',
                nextEffect: 'none',

                arrows: false,
                helpers: {
                    media: {},
                    buttons: {}
                }
            });
    });
</script>