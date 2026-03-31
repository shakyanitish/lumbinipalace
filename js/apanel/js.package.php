<script language="javascript">
    function getLocation() {
        return '<?php echo BASE_URL; ?>includes/controllers/ajax.package.php';
    }

    function getTableId() {
        return 'table_dnd';
    }

    $(document).ready(function() {
        // Function to get paging information
        $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings) {
            if (!oSettings || oSettings._iDisplayStart === undefined) {
                return null; // Prevent errors if DataTable is not fully loaded
            }
            return {
                "iStart": oSettings._iDisplayStart,
                "iEnd": oSettings.fnDisplayEnd(),
                "iLength": oSettings._iDisplayLength,
                "iTotal": oSettings.fnRecordsTotal(),
                "iFilteredTotal": oSettings.fnRecordsDisplay(),
                "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
            };
        };

        // Packages datatable
        var desiredPage = localStorage.getItem("packagedbpage") ? parseInt(localStorage.getItem("packagedbpage")) : 0;
        var desiredLength = localStorage.getItem("packagedblength") ? parseInt(localStorage.getItem("packagedblength")) : 10;

        var oTable = $('#example').dataTable({
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "aLengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            "iDisplayLength": desiredLength
        }).rowReordering({
            sURL: "<?php echo BASE_URL; ?>includes/controllers/ajax.package.php?action=sort",
            fnSuccess: function(message) {
                var msg = jQuery.parseJSON(message);
                showMessage(msg.action, msg.message);
            }
        });

        $(document).on("click", "#example .fg-button", function() {
            var currentPage = oTable.fnPagingInfo().iPage;
            localStorage.setItem("packagedbpage", currentPage);
        });

        $(document).on('change', '#example_length select', function() {
            var selectedLength = $(this).val();
            localStorage.setItem("packagedblength", selectedLength);
            if (selectedLength == -1) {
                $('.fg-button').prop('disabled', true);
            } else {
                $('.fg-button').prop('disabled', false);
            }
        });

        if (oTable.fnPagingInfo() != null) {
            if (desiredLength != -1) {
                oTable.fnPageChange(desiredPage, true);
            }
        }

window.openZoomText = function(btn) {
    var $input = $(btn).siblings('input[type="text"]');
    var currentText = $input.val();
    var placeholder = $input.attr('placeholder') || 'Edit Text';
    
    if ($('#zoomTextModal').length === 0) {
        $('body').append(
            '<div id="zoomTextModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 99999; background: rgba(0,0,0,0.6);">' +
                '<div style="background: #fff; width: 50%; margin: 8% auto; padding: 20px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">' +
                    '<h3 id="zoomTextModalTitle" style="margin-top: 0; border-bottom: 1px solid #ddd; padding-bottom: 10px;">Edit Text</h3>' +
                    '<textarea id="zoomTextarea" class="form-control" style="width: 95%; height: 250px; font-size: 14px; padding: 10px; margin-top: 15px; resize: vertical;"></textarea>' +
                    '<div style="text-align: right; margin-top: 20px;">' +
                        '<button type="button" class="btn medium bg-green" id="saveZoomTextBtn" style="margin-right: 10px;" title="Zoom Out"><i class="glyph-icon icon-search-minus"></i> <span class="button-content">Zoom Out</span></button>' +
                        '<button type="button" class="btn medium bg-red" id="closeZoomTextBtn"><span class="button-content">Cancel</span></button>' +
                    '</div>' +
                '</div>' +
            '</div>'
        );

        $('#closeZoomTextBtn').on('click', function() {
            $('#zoomTextModal').fadeOut();
        });
    }

    $('#zoomTextModalTitle').text('Edit: ' + placeholder);
    $('#zoomTextarea').val(currentText);
    $('#zoomTextModal').fadeIn();
    $('#zoomTextarea').focus();

    $('#saveZoomTextBtn').off('click').on('click', function() {
        var newText = $('#zoomTextarea').val();
        $input.val(newText);
        $('#zoomTextModal').fadeOut();
    });
};

window.addIncludesRow = function() {
    var uniqId = new Date().getTime();
    var newRow = '<div class="mrg10B" style="background: #f9f9f9; padding: 15px; border-radius: 4px; margin-bottom: 15px;">\
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">\
            <span class="drag-handle cp" style="cursor: move;"><i class="glyph-icon icon-arrows"></i></span>\
            <input type="text" placeholder="Includes Text" class="form-control" style="flex: 1;" name="incexc_text[' + uniqId + ']">\
            <span class="btn small bg-green tooltip-button cp" title="Zoom In" onclick="openZoomText(this);" style="cursor: pointer; margin-right: 5px;"><i class="glyph-icon icon-search-plus"></i></span>\
            <span class="cp remove_includes_row" onclick="$(this).closest(\'.mrg10B\').remove();" style="cursor: pointer;"><i class="glyph-icon icon-minus-square"></i></span>\
        </div>\
        <div class="form-row">\
            <div class="form-label col-md-2">\
                <label>Link Type :</label>\
            </div>\
            <div class="form-checkbox-radio col-md-9">\
                <input type="radio" class="custom-radio" name="incexc_linktype[' + uniqId + ']" value="0" onclick="includeLinkTypeSelect(this);" checked>\
                <label>Internal Link</label>\
                <input type="radio" class="custom-radio" name="incexc_linktype[' + uniqId + ']" value="1" onclick="includeLinkTypeSelect(this);">\
                <label>External Link</label>\
            </div>\
        </div>\
        <div class="form-row">\
            <div class="form-label col-md-2">\
                <label>Link :</label>\
            </div>\
            <div class="form-input col-md-8">\
                <div class="col-md-4" style="padding-left: 0px !important;">\
                    <input placeholder="Menu Link" class="form-control incexc-url-input" type="text" name="incexc_url[' + uniqId + ']">\
                </div>\
                <div class="col-md-6" style="padding-left: 0px !important; display: block;">\
                    <select data-placeholder="Select Link Page" class="form-control chosen-select incexc-page-select">\
                        <option value=""></option>\
                        <?php echo str_replace("\n", "", Article::get_internal_link()); ?>\
                        <?php echo str_replace("\n", "", Services::get_internal_link()); ?>\
                    </select>\
                </div>\
            </div>\
        </div>\
    </div>';

    $('#add_includes_div').append(newRow);
    $('#add_includes_div .chosen-select').last().chosen();
}

window.addIncludesRow2 = function() {
    var uniqId = new Date().getTime();
    var newRow = '<div class="mrg10B" style="background: #f9f9f9; padding: 15px; border-radius: 4px; margin-bottom: 15px;">\
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">\
            <span class="drag-handle cp" style="cursor: move;"><i class="glyph-icon icon-arrows"></i></span>\
            <input type="text" placeholder="Includes Text" class="form-control" style="flex: 1;" name="incexc_text1[' + uniqId + ']">\
            <span class="btn small bg-green tooltip-button cp" title="Zoom In" onclick="openZoomText(this);" style="cursor: pointer; margin-right: 5px;"><i class="glyph-icon icon-search-plus"></i></span>\
            <span class="cp remove_includes_row" onclick="$(this).closest(\'.mrg10B\').remove();" style="cursor: pointer;"><i class="glyph-icon icon-minus-square"></i></span>\
        </div>\
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;" class="subtitle-field" ' + ($('input[name="type"]:checked').val() != 2 ? 'style="display: none;"' : '') + '>\
            <span style="visibility: hidden;"><i class="glyph-icon icon-arrows"></i></span>\
            <input type="text" placeholder="Subtitle" class="form-control" style="flex: 1;" name="incexc_subtitle1[' + uniqId + ']">\
            <span class="btn small bg-green tooltip-button cp" title="Zoom In" onclick="openZoomText(this);" style="cursor: pointer; margin-right: 5px;"><i class="glyph-icon icon-search-plus"></i></span>\
            <span style="visibility: hidden;"><i class="glyph-icon icon-minus-square"></i></span>\
        </div>\
        <div class="form-row">\
            <div class="form-label col-md-2">\
                <label>Link Type :</label>\
            </div>\
            <div class="form-checkbox-radio col-md-9">\
                <input type="radio" class="custom-radio" name="incexc_linktype1[' + uniqId + ']" value="0" onclick="includeLinkTypeSelect(this);" checked>\
                <label>Internal Link</label>\
                <input type="radio" class="custom-radio" name="incexc_linktype1[' + uniqId + ']" value="1" onclick="includeLinkTypeSelect(this);">\
                <label>External Link</label>\
            </div>\
        </div>\
        <div class="form-row">\
            <div class="form-label col-md-2">\
                <label>Link :</label>\
            </div>\
            <div class="form-input col-md-8">\
                <div class="col-md-4" style="padding-left: 0px !important;">\
                    <input placeholder="Menu Link" class="form-control incexc-url-input" type="text" name="incexc_url1[' + uniqId + ']">\
                </div>\
                <div class="col-md-6" style="padding-left: 0px !important; display: block;">\
                    <select data-placeholder="Select Link Page" class="form-control chosen-select incexc-page-select">\
                        <option value=""></option>\
                        <?php echo str_replace("\n", "", Article::get_internal_link()); ?>\
                        <?php echo str_replace("\n", "", Services::get_internal_link()); ?>\
                    </select>\
                </div>\
            </div>\
        </div>\
    </div>';

    $('#add_includes_div_2').append(newRow);
    $('#add_includes_div_2 .chosen-select').last().chosen();
}


        // Subpackages datatable
        var desiredSubPage = localStorage.getItem("subpackagedbpage") ? parseInt(localStorage.getItem("subpackagedbpage")) : 0;
        var desiredSubLength = localStorage.getItem("subpackagedblength") ? parseInt(localStorage.getItem("subpackagedblength")) : 10;

        var oSubTable = $('#subexample').dataTable({
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "aLengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            "iDisplayLength": desiredSubLength
        }).rowReordering({
            sURL: "<?php echo BASE_URL; ?>includes/controllers/ajax.package.php?action=subSort",
            fnSuccess: function(message) {
                var msg = jQuery.parseJSON(message);
                showMessage(msg.action, msg.message);
            }
        });
        
        $(document).on("click", "#subexample .fg-button", function() {
            var currentPage = oSubTable.fnPagingInfo().iPage;
            localStorage.setItem("subpackagedbpage", currentPage);
        });

        $(document).on('change', '#subexample_length select', function() {
            var selectedLength = $(this).val();
            localStorage.setItem("subpackagedblength", selectedLength);
        });

        if (oSubTable.fnPagingInfo() != null) {
            if (desiredSubLength != -1) {
                oSubTable.fnPageChange(desiredSubPage, true);
            }
        }


        // Sub-package Itinerary datatable (subexample1)
        var desiredItineraryPage = localStorage.getItem("itinerarydbpage") ? parseInt(localStorage.getItem("itinerarydbpage")) : 0;
        var desiredItineraryLength = localStorage.getItem("itinerarydblength") ? parseInt(localStorage.getItem("itinerarydblength")) : 10;

        var oItineraryTable = $('#subexample1').dataTable({
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "aLengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            "iDisplayLength": desiredItineraryLength
        }).rowReordering({
            sURL: "<?php echo BASE_URL; ?>includes/controllers/ajax.package.php?action=subiSort",
            fnSuccess: function(message) {
                var msg = jQuery.parseJSON(message);
                showMessage(msg.action, msg.message);
            }
        });

        $(document).on("click", "#subexample1 .fg-button", function() {
            var currentPage = oItineraryTable.fnPagingInfo().iPage;
            localStorage.setItem("itinerarydbpage", currentPage);
        });

        $(document).on('change', '#subexample1_length select', function() {
            var selectedLength = $(this).val();
            localStorage.setItem("itinerarydblength", selectedLength);
        });

        if (oItineraryTable.fnPagingInfo() != null) {
            if (desiredItineraryLength != -1) {
                oItineraryTable.fnPageChange(desiredItineraryPage, true);
            }
        }

        // Package Itinerary datatable (subexample2)
        var desiredPackageItineraryPage = localStorage.getItem("packageitinerarydbpage") ? parseInt(localStorage.getItem("packageitinerarydbpage")) : 0;
        var desiredPackageItineraryLength = localStorage.getItem("packageitinerarydblength") ? parseInt(localStorage.getItem("packageitinerarydblength")) : 10;

        var oPackageItineraryTable = $('#subexample2').dataTable({
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "aLengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            "iDisplayLength": desiredPackageItineraryLength
        }).rowReordering({
            sURL: "<?php echo BASE_URL; ?>includes/controllers/ajax.package.php?action=packageitSort",
            fnSuccess: function(message) {
                var msg = jQuery.parseJSON(message);
                showMessage(msg.action, msg.message);
            }
        });

        $(document).on("click", "#subexample2 .fg-button", function() {
            var currentPage = oPackageItineraryTable.fnPagingInfo().iPage;
            localStorage.setItem("packageitinerarydbpage", currentPage);
        });

        $(document).on('change', '#subexample2_length select', function() {
            var selectedLength = $(this).val();
            localStorage.setItem("packageitinerarydblength", selectedLength);
        });

        if (oPackageItineraryTable.fnPagingInfo() != null) {
            if (desiredPackageItineraryLength != -1) {
                oPackageItineraryTable.fnPageChange(desiredPackageItineraryPage, true);
            }
        }

        $('.btn-submit').on('click', function() {
            var actVal = $(this).attr('btn-action');
            $('#idValue').attr('myaction', actVal);
        });

        jQuery('#package_frm').validationEngine({
            autoHidePrompt: true,
            promptPosition: "bottomLeft",
            scroll: true,
            onValidationComplete: function(form, status) {
                if (status == true) {
                    $('.btn-submit').attr('disabled', 'true');
                    var action = ($('#idValue').val() == 0) ? "action=add&" : "action=edit&";
                    for (instance in CKEDITOR.instances)
                        CKEDITOR.instances[instance].updateElement();
                    var data = $('#package_frm').serialize();
                    queryString = action + data;
                    $.ajax({
                        type: "POST",
                        dataType: "JSON",
                        url: getLocation(),
                        data: queryString,
                        success: function(data) {
                            var msg = eval(data);
                            if (msg.action == 'warning') {
                                showMessage(msg.action, msg.message);
                                setTimeout(function() {
                                    $('.my-msg').html('');
                                }, 3000);
                                $('.btn-submit').removeAttr('disabled');
                                $('.formButtons').show();
                                return false
                            }
                            if (msg.action == 'success') {
                                showMessage(msg.action, msg.message);
                                var actionId = $('#idValue').attr('myaction');
                                if (actionId == 2)
                                    setTimeout(function() {
                                        window.location.href = "<?php echo ADMIN_URL ?>package/list";
                                    }, 3000);
                                if (actionId == 1)
                                    setTimeout(function() {
                                        window.location.href = "<?php echo ADMIN_URL ?>package/addEdit";
                                    }, 3000);
                                if (actionId == '0')
                                    setTimeout(function() {
                                        window.location.href = "";
                                    }, 3000);
                            }
                            if (msg.action == 'notice') {
                                showMessage(msg.action, msg.message);
                                setTimeout(function() {
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
        });

        jQuery('#subpackage_frm').validationEngine({
            prettySelect: true,
            autoHidePrompt: true,
            useSuffix: "_chosen",
            promptPosition: "bottomLeft",
            scroll: true,
            onValidationComplete: function(form, status) {
                if (status == true) {
                    var Re = $("#type").val();
                    $('.btn-submit').attr('disabled', 'true');
                    var action = ($('#idValue').val() == 0) ? "action=addSubpackage&" : "action=editSubpackage&";
                    for (instance in CKEDITOR.instances)
                        CKEDITOR.instances[instance].updateElement();

                    var data = $('#subpackage_frm').serialize();
                    queryString = action + data;
                    $.ajax({
                        type: "POST",
                        dataType: "JSON",
                        url: getLocation(),
                        data: queryString,
                        success: function(data) {
                            var msg = eval(data);
                            if (msg.action == 'warning') {
                                showMessage(msg.action, msg.message);
                                $('.btn-submit').removeAttr('disabled');
                                $('.formButtons').show();
                                return false
                            }
                            if (msg.action == 'success') {
                                showMessage(msg.action, msg.message);
                                var actionId = $('#idValue').attr('myaction');
                                if (actionId == 2)
                                    setTimeout(function() {
                                        window.location.href = "<?php echo ADMIN_URL ?>package/subpackagelist/" + Re;
                                    }, 3000);
                                if (actionId == 1)
                                    setTimeout(function() {
                                        window.location.href = "<?php echo ADMIN_URL ?>package/addEditsubpackage/" + Re;
                                    }, 3000);
                                if (actionId == 0)
                                    setTimeout(function() {
                                        window.location.href = "<?php echo ADMIN_URL ?>package/subpackagelist/" + Re;
                                    }, 3000);
                            }
                            if (msg.action == 'notice') {
                                showMessage(msg.action, msg.message);
                                setTimeout(function() {
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
        });

        jQuery('#subgallery_frm').validationEngine({
            autoHidePrompt: true,
            scroll: false,
            onValidationComplete: function(form, status) {
                if (status == true) {
                    $('#btn-submit').attr('disabled', 'true');
                    var action = "action=addSubPackageImage&";
                    var data = $('#subgallery_frm').serialize();
                    queryString = action + data;
                    $.ajax({
                        type: "POST",
                        dataType: "JSON",
                        url: getLocation(),
                        data: queryString,
                        success: function(data) {
                            var msg = eval(data);
                            if (msg.action == 'warning') {
                                showMessage(msg.action, msg.message);
                                $('#btn-submit').removeAttr('disabled');
                                $('.formButtons').show();
                                return false
                            }
                            if (msg.action == 'success') {
                                showMessage(msg.action, msg.message);
                                setTimeout(function() {
                                    window.location.href = window.location.href;
                                }, 3000);
                            }
                            if (msg.action == 'notice') {
                                showMessage(msg.action, msg.message);
                                setTimeout(function() {
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
        });

        $(".subImagegallery-sort").sortable({
            start: function(event, ui) {
                var start_pos = ui.item.index();
                ui.item.data('start_pos', start_pos);
            },
            update: function(event, ui) {
                var mySel = "";
                $('div.oldsort').each(function(i) {
                    mySel = mySel + ';' + $(this).attr('csort');
                });
                var id = ui.item.context.id;
                var end_pos = ui.item.index();
                $.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: getLocation(),
                    data: "action=sortSubGalley&id=" + id + "&toPosition=" + end_pos + "&sortIds=" + mySel,
                    success: function(data) {
                        var msg = eval(data);
                        showMessage(msg.action, msg.message);
                    }
                });
            }
        });

        jQuery('#itinerary_frm').validationEngine({
            prettySelect: true,
            autoHidePrompt: true,
            useSuffix: "_chosen",
            promptPosition: "bottomLeft",
            scroll: true,
            onValidationComplete: function(form, status) {
                if (status == true) {
                    var Re = $("#package_id").val();
                    $('.btn-submit').attr('disabled', 'true');
                    var action = ($('#idValue').val() == 0) ? "action=additinerary&" : "action=edititinerary&";
                    for (instance in CKEDITOR.instances)
                        CKEDITOR.instances[instance].updateElement();

                    var data = $('#itinerary_frm').serialize();
                    queryString = action + data;
                    $.ajax({
                        type: "POST",
                        dataType: "JSON",
                        url: getLocation(),
                        data: queryString,
                        success: function(data) {
                            var msg = eval(data);
                            if (msg.action == 'warning') {
                                showMessage(msg.action, msg.message);
                                $('.btn-submit').removeAttr('disabled');
                                $('.formButtons').show();
                                return false
                            }
                            if (msg.action == 'success') {
                                showMessage(msg.action, msg.message);
                                var actionId = $('#idValue').attr('myaction');
                                if (actionId == 2)
                                    setTimeout(function() {
                                        window.location.href = "<?php echo ADMIN_URL ?>package/itinerarylist/" + Re;
                                    }, 3000);
                                if (actionId == 1)
                                    setTimeout(function() {
                                        window.location.href = "<?php echo ADMIN_URL ?>package/addEdititinerary/" + Re;
                                    }, 3000);
                                if (actionId == 0)
                                    setTimeout(function() {
                                        window.location.href = "<?php echo ADMIN_URL ?>package/itinerarylist/" + Re;
                                    }, 3000);
                            }
                            if (msg.action == 'notice') {
                                showMessage(msg.action, msg.message);
                                setTimeout(function() {
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
        });

        jQuery('#itinerarypackage_frm').validationEngine({
            prettySelect: true,
            autoHidePrompt: true,
            useSuffix: "_chosen",
            promptPosition: "bottomLeft",
            scroll: true,
            onValidationComplete: function(form, status) {
                if (status == true) {
                    var Re = $("#package_id").val();
                    $('.btn-submit').attr('disabled', 'true');
                    var action = ($('#idValue').val() == 0) ? "action=additinerarypackage&" : "action=edititinerarypackage&";
                    for (instance in CKEDITOR.instances)
                        CKEDITOR.instances[instance].updateElement();

                    var data = $('#itinerarypackage_frm').serialize();
                    queryString = action + data;
                    $.ajax({
                        type: "POST",
                        dataType: "JSON",
                        url: getLocation(),
                        data: queryString,
                        success: function(data) {
                            var msg = eval(data);
                            if (msg.action == 'warning') {
                                showMessage(msg.action, msg.message);
                                $('.btn-submit').removeAttr('disabled');
                                $('.formButtons').show();
                                return false
                            }
                            if (msg.action == 'success') {
                                showMessage(msg.action, msg.message);
                                var actionId = $('#idValue').attr('myaction');
                                if (actionId == 2)
                                    setTimeout(function() {
                                        window.location.href = "<?php echo ADMIN_URL ?>package/itinerarylistpackage/" + Re;
                                    }, 3000);
                                if (actionId == 1)
                                    setTimeout(function() {
                                        window.location.href = "<?php echo ADMIN_URL ?>package/addEditItineraryPackage/" + Re;
                                    }, 3000);
                                if (actionId == 0)
                                    setTimeout(function() {
                                        window.location.href = "<?php echo ADMIN_URL ?>package/itinerarylistpackage/" + Re;
                                    }, 3000);
                            }
                            if (msg.action == 'notice') {
                                showMessage(msg.action, msg.message);
                                setTimeout(function() {
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
        });

        $('#linkPage').change(function() {
            $('#linksrc').val($(this).val());
        });

        $('.maxppl').on('change', function() {
            var selVal = $(this).val();
            if (selVal == 1) {
                $('.rmovprice1').removeClass('hide');
                $('.rmovprice2').addClass('hide');
                $('.rmovprice3').addClass('hide');
            }
            if (selVal == 2) {
                $('.rmovprice1').removeClass('hide');
                $('.rmovprice2').removeClass('hide');
                $('.rmovprice3').addClass('hide');
            }
            if (selVal == 3) {
                $('.rmovprice1').removeClass('hide');
                $('.rmovprice2').removeClass('hide');
                $('.rmovprice3').removeClass('hide');
            }
        });
        $('.maxppl').trigger('change');

        $(".character-details").keyup(function() {
            var a = 125,
                b = $(this).val().length;
            if (b >= a) $(".description-remaining").text(" you have reached the limit");
            else {
                var c = a - b;
                $(".description-remaining").text(c + " characters left")
            }
        });
        
        $('#program_date').datepicker({
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'yy-mm-dd',
        });

        $('#applySelected_btn1').on("click", function() {
            var action = $('#groupTaskField1').val();
            if (action == '0') {
                showMessage('warning', 'Please select an action!!');
                return;
            }

            var idArray = '0';
            $('.bulkCheckbox:checked').each(function() {
                idArray += "|" + $(this).attr('bulkId');
            });
            if (idArray == '0') {
                showMessage('warning', 'Please select at least one record.');
                return;
            }

            switch (action) {
                case "subitoggleStatus":
                    toggleItineraryStatus(idArray);
                    break;
                case "subidelete":
                    $('.MsgTitle').html('Do you want to delete the selected rows?');
                    $('.pText').html('Click on yes button to delete these rows permanently!');
                    $('.divMessageBox').fadeIn();
                    $('.MessageBoxContainer').fadeIn(1000);

                    $(".botTempo").off("click").on("click", function() {
                        if ($(this).attr("id") === 'yes') {
                            subdeleteSelectedRecords(idArray);
                        }
                        $('.divMessageBox').fadeOut();
                        $('.MessageBoxContainer').fadeOut(1000);
                    });
                    break;
                default:
                    showMessage('warning', 'Action not supported.');
                    break;
            }

            reStructureList(getTableId());
        });

        $('#applySelected_btn2').on("click", function() {
            var action = $('#groupTaskField2').val();
            if (action == '0') {
                showMessage('warning', 'Please select an action!!');
                return;
            }

            var idArray = '0';
            $('.bulkCheckbox:checked').each(function() {
                idArray += "|" + $(this).attr('bulkId');
            });
            if (idArray == '0') {
                showMessage('warning', 'Please select at least one record.');
                return;
            }

            switch (action) {
                case "packageitoggleStatus":
                    togglePackageItineraryStatus(idArray);
                    break;
                case "packageitdelete":
                    $('.MsgTitle').html('Do you want to delete the selected rows?');
                    $('.pText').html('Click on yes button to delete these rows permanently!');
                    $('.divMessageBox').fadeIn();
                    $('.MessageBoxContainer').fadeIn(1000);

                    $(".botTempo").off("click").on("click", function() {
                        if ($(this).attr("id") === 'yes') {
                            packageitdeleteSelectedRecords(idArray);
                        }
                        $('.divMessageBox').fadeOut();
                        $('.MessageBoxContainer').fadeOut(1000);
                    });
                    break;
                default:
                    showMessage('warning', 'Action not supported.');
                    break;
            }

            reStructureList(getTableId());
        });
        
        var initialType = <?php echo !empty($advInfo->explorelinktype) ? $advInfo->explorelinktype : 0; ?>;
        exploreLinkTypeSelect(initialType);

        $('#exploreLinkPage').change(function() {
            $('#explorelinksrc').val($(this).val());
        });

        // Type toggle logic
        $('input[name="type"]').on('change', function() {
            var val = $('input[name="type"]:checked').val();

            // Includes section: show for both Events (3) and Experiences (2)
            if (val == 2 || val == 3) {
                $('.events-and-experiences-fields').show();
                $('.events-and-experiences-fields input').prop('disabled', false);

                // Subtitle field: show ONLY for Experiences (2)
                if (val == 2) {
                    $('.subtitle-field').show();
                } else {
                    $('.subtitle-field').hide();
                }
            } else {
                $('.events-and-experiences-fields').hide();
                $('.events-and-experiences-fields input').prop('disabled', true);
            }

            // Events-only section: show only for Events (3)
            if (val == 3) {
                $('.events-only-fields').show();
                $('.events-only-fields textarea, .events-only-fields input').prop('disabled', false);
                // Initialize CKEditors for extra fields if they haven't been
                var extra_editors = ["content1", "content2", "content3", "content4", "content5"];
                for (var i in extra_editors) {
                    if (!CKEDITOR.instances[extra_editors[i]]) {
                        CKEDITOR.replace(extra_editors[i], {
                            filebrowserBrowseUrl: '<?php echo BASE_URL; ?>ckfinder/ckfinder.html'
                        });
                    }
                }
            } else {
                $('.events-only-fields').hide();
                $('.events-only-fields textarea, .events-only-fields input').prop('disabled', true);
            }
        });
        // Trigger on load
        $('input[name="type"]:checked').trigger('change');

        // Read More handlers for extra content fields
        for (var i = 1; i <= 5; i++) {
            (function(index) {
                $(document).on('click', '#readMore' + index, function() {
                    var instanceName = 'content' + (index == 1 ? '1' : index);
                    if (index == 0) instanceName = 'content'; // case for main one
                    
                    var data = CKEDITOR.instances[instanceName].getData();
                    if (data.match(/<hr id="system_readmore" style="border-style: dashed; border-color: orange;" \/>/g)) {
                        showMessage('notice', 'Action already exists.');
                    } else {
                        CKEDITOR.instances[instanceName].insertHtml('<hr id="system_readmore" style="border-style: dashed; border-color: orange;" />');
                    }
                });
            })(i);
        }
    });

    function togglePackageItineraryStatus(idArray) {
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: getLocation(),
            data: "action=packageitbulkToggleStatus&idArray=" + idArray,
            success: function(data) {
                var msg = eval(data);
                showMessage(msg.action, msg.message);
                location.reload();
            }
        });
    }

    function packageitdeleteSelectedRecords(idArray) {
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: getLocation(),
            data: "action=packageitbulkDelete&idArray=" + idArray,
            success: function(data) {
                var msg = eval(data);
                showMessage(msg.action, msg.message);
                reStructureList(getTableId());
            }
        });
    }

    function addRowss() {
        var rowNum = Math.floor((Math.random() * 999) + 1);
        var newRow = '<div class="form-row my-style" id="NewRow' + rowNum + '">';
        newRow += '<div class="form-label col-md-2"></div>';
        newRow += '<div class="form-input col-md-12">';
        newRow += '<div class="col-md-4">';
        newRow += '<input placeholder="Facility Title" type="text" name="facilityOption[]" id="facilityOption" class="validate[required]">';
        newRow += '</div>';
        newRow += '<div>';
        newRow += '<a href="javascript:void(0);" class="btn medium bg-blue tooltip-button" data-placement="right" title="Add" onclick="addRowss(this);">';
        newRow += '<i class="glyph-icon icon-plus-square"></i>';
        newRow += '</a>';
        newRow += '<a href="javascript:void(0);" class="btn medium bg-red tooltip-button" data-placement="right" title="Delete" onclick="deletenewRow(' + rowNum + ');">';
        newRow += '<i class="glyph-icon icon-minus-square"></i>';
        newRow += '</a>';
        newRow += '</div>';
        newRow += '</div>';
        newRow += '</div>';
        $('#option-field').append(newRow);
    }

    function deletenewRow(rnum) {
        $('#NewRow' + rnum).remove();
    }

    function addnewRow2() {
        var rowNum = Math.floor((Math.random() * 999) + 1);
        var newRow = '<div class="form-row my-style" id="NewRowserv' + rowNum + '">';
        newRow += '<div class="form-label col-md-2"></div>';
        newRow += '<div class="form-input col-md-12">';
        newRow += '<div class="col-md-4">';
        newRow += '<input placeholder="Service Name" type="text" name="service[]" id="service" class="validate[length[0,50]]">';
        newRow += '</div>';
        newRow += '<div>';
        newRow += '<a href="javascript:void(0);" class="btn medium bg-blue tooltip-button" data-placement="right" title="Add" onclick="addnewRow2(this);">';
        newRow += '<i class="glyph-icon icon-plus-square"></i>';
        newRow += '</a>';
        newRow += '<a href="javascript:void(0);" class="btn medium bg-red tooltip-button" data-placement="right" title="Delete" onclick="deletenewRow2(' + rowNum + ');">';
        newRow += '<i class="glyph-icon icon-minus-square"></i>';
        newRow += '</a>';
        newRow += '</div>';
        newRow += '</div>';
        newRow += '</div>';
        $('#option-field2').append(newRow);
    }

    function deletenewRow2(rnum) {
        $('#NewRowserv' + rnum).remove();
    }

    function editRecord(Re) {
        window.location.href = "<?php echo ADMIN_URL ?>package/addEdit/" + Re;
    }

    function recordDelete(Re) {
        $('.MsgTitle').html('<?php echo sprintf($GLOBALS['basic']['deleteRecord_'], "Facility") ?>');
        $('.pText').html('Click on yes button to delete this facility permanently.!!');
        $('.divMessageBox').fadeIn();
        $('.MessageBoxContainer').fadeIn(1000);
        $(".botTempo").on("click", function() {
            var popAct = $(this).attr("id");
            if (popAct == 'yes') {
                $.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: getLocation(),
                    data: 'action=delete&id=' + Re,
                    success: function(data) {
                        var msg = eval(data);
                        showMessage(msg.action, msg.message);
                        $('#' + Re).remove();
                        reStructureList(getTableId());
                    }
                });
            } else {
                Re = null;
            }
            $('.divMessageBox').fadeOut();
            $('.MessageBoxContainer').fadeOut(1000);
        });
    }

    function subreDelete(Re) {
        $('.MsgTitle').html('<?php echo sprintf($GLOBALS['basic']['deleteRecord_'], "Package") ?>');
        $('.pText').html('Click on yes button to delete this package permanently.!!');
        $('.divMessageBox').fadeIn();
        $('.MessageBoxContainer').fadeIn(1000);
        $(".botTempo").on("click", function() {
            var popAct = $(this).attr("id");
            if (popAct == 'yes') {
                $.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: getLocation(),
                    data: 'action=deleteitinerary&id=' + Re,
                    success: function(data) {
                        var msg = eval(data);
                        showMessage(msg.action, msg.message);
                        $('#' + Re).remove();
                        reStructureList(getTableId());
                    }
                });
            } else {
                Re = null;
            }
            $('.divMessageBox').fadeOut();
            $('.MessageBoxContainer').fadeOut(1000);
        });
    }

    $(document).ready(function() {
    $('.imageStatusToggle').on('click', function() {
        var Re = $(this).attr('rowId');
        var status = $(this).attr('status');
        newStatus = (status == 1) ? 0 : 1;
        $.ajax({
            type: "POST",
            url: getLocation(),
            data: "action=SubitoggleStatus&id=" + Re,
            success: function(msg) {}
        });
        $(this).attr({
            'status': newStatus
        });
        if (status == 1) {
            $('#toggleImg' + Re).removeClass("icon-check-circle-o").addClass("icon-clock-os-circle-o");
        } else {
            $('#toggleImg' + Re).removeClass("icon-clock-os-circle-o").addClass("icon-check-circle-o");
        }
    });
        });

    $('.statusItinerary').on('click', function() {
        var id = $(this).attr('moduleId');
        var status = $(this).attr('status');
        newStatus = (status == 1) ? 0 : 1;
        $.ajax({
            type: "POST",
            url: getLocation(),
            data: "action=SubitoggleStatus&id=" + id,
            success: function(msg) {}
        });
        $(this).attr({
            'status': newStatus
        });
        if (status == 1) {
            $('#imgHolder_' + id).removeClass("bg-green").addClass("bg-red");
            $(this).attr("data-original-title", "Click to Publish");
        } else {
            $('#imgHolder_' + id).removeClass("bg-red").addClass("bg-green");
            $(this).attr("data-original-title", "Click to Un-publish");
        }
    });

    $(document).on('click', '.statusItineraryPackage', function() {
        var id = $(this).attr('moduleId');
        var status = $(this).attr('status');
        newStatus = (status == 1) ? 0 : 1;
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: getLocation(),
            data: "action=statusItineraryPackage&id=" + id,
            success: function(msg) {
                var data = eval(msg);
                showMessage(data.action, 'Status updated successfully');
            }
        });
        $(this).attr({
            'status': newStatus
        });
        if (status == 1) {
            $('#imgHolder_' + id).removeClass("bg-green").addClass("bg-red");
            $(this).attr("data-original-title", "Click to Publish");
        } else {
            $('#imgHolder_' + id).removeClass("bg-red").addClass("bg-green");
            $(this).attr("data-original-title", "Click to Un-publish");
        }
    });

    function subrecordDelete(Re) {
        $('.MsgTitle').html('<?php echo sprintf($GLOBALS['basic']['deleteRecord_'], "Package") ?>');
        $('.pText').html('Click on yes button to delete this package permanently.!!');
        $('.divMessageBox').fadeIn();
        $('.MessageBoxContainer').fadeIn(1000);
        $(".botTempo").on("click", function() {
            var popAct = $(this).attr("id");
            if (popAct == 'yes') {
                $.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: getLocation(),
                    data: 'action=deletesubpackage&id=' + Re,
                    success: function(data) {
                        var msg = eval(data);
                        showMessage(msg.action, msg.message);
                        $('#' + Re).remove();
                        reStructureList(getTableId());
                    }
                });
            } else {
                Re = null;
            }
            $('.divMessageBox').fadeOut();
            $('.MessageBoxContainer').fadeOut(1000);
        });
    }

    function linkTypeSelect(Re) {
        if (Re == 0) {
            $('#linkPage_chosen').removeClass("hide");
            ($('#linksrc').val() == 'http://www.') ? $('#linksrc').val(''): null;
        } else {
            $('#linkPage_chosen').addClass("hide");
            ($('#linksrc').val() == '') ? $('#linksrc').val("http://www."): null;
        }
    }

    function toggleMetadata() {
        $(".metadata").slideToggle("slow", function() {});
    }

    function viewPackagelist() {
        window.location.href = "<?php echo ADMIN_URL ?>package/list";
    }

    function AddNewPackage() {
        window.location.href = "<?php echo ADMIN_URL ?>package/addEdit";
    }

    function viewSubpackagelist(Re) {
        window.location.href = "<?php echo ADMIN_URL ?>package/subpackagelist/" + Re;
    }

    function AddNewSubpackage(Re) {
        window.location.href = "<?php echo ADMIN_URL ?>package/addEditsubpackage/" + Re;
    }

    function editsubpackage(Pid, Re) {
        window.location.href = "<?php echo ADMIN_URL ?>package/addEditsubpackage/" + Pid + "/" + Re;
    }

    function deleteTempimage(Re) {
        $('#previewRoomsimage' + Re).fadeOut(1000, function() {
            $('#previewRoomsimage' + Re).remove();
        });
    }

    function deleteTempimages(Re) {
        $('#previewUserimage' + Re).fadeOut(1000, function() {
            $('#previewUserimage' + Re).remove();
        });
    }

    function deleteTempflag(Re) {
        $('#previewflag' + Re).fadeOut(1000, function() {
            $('#previewflag' + Re).remove();
        });
    }

    function deleteTempVideo(Re) {
        $('#previewVideo' + Re).fadeOut(1000, function() {
            $('#previewVideo' + Re).remove();
        });
    }

    function viewsubimagelist(Re) {
        window.location.href = "<?php echo ADMIN_URL ?>package/subpackageImageList/" + Re;
    }

    function viewSubImageslist(Re) {
        window.location.href = "<?php echo ADMIN_URL ?>package&mode=subpackageImageList/" + Re;
    }

    function editItinerary(Pid, Re) {
        window.location.href = "<?php echo ADMIN_URL ?>package/addEdititinerary/" + Pid + "/" + Re;
    }

    function AddNewItinerary(Re) {
        window.location.href = "<?php echo ADMIN_URL ?>package/addEdititinerary/" + Re;
    }

    function viewItinerarylist(Re) {
        window.location.href = "<?php echo ADMIN_URL ?>package/itinerarylist/" + Re;
    }

    function editItineraryPackage(Pid, Re) {
        window.location.href = "<?php echo ADMIN_URL ?>package/addEditItineraryPackage/" + Pid + "/" + Re;
    }

    function AddNewItineraryPackage(Re) {
        window.location.href = "<?php echo ADMIN_URL ?>package/addEditItineraryPackage/" + Re;
    }

    function viewItinerarylistPackage(Re) {
        window.location.href = "<?php echo ADMIN_URL ?>package/itinerarylistpackage/" + Re;
    }

    function packageItinDelete(Re) {
        $('.MsgTitle').html('<?php echo sprintf($GLOBALS['basic']['deleteRecord_'], "Package Itinerary") ?>');
        $('.pText').html('Click on yes button to delete this itinerary permanently.!!');
        $('.divMessageBox').fadeIn();
        $('.MessageBoxContainer').fadeIn(1000);
        $(".botTempo").on("click", function() {
            var popAct = $(this).attr("id");
            if (popAct == 'yes') {
                $.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: getLocation(),
                    data: 'action=deletepackageitinerary&id=' + Re,
                    success: function(data) {
                        var msg = eval(data);
                        showMessage(msg.action, msg.message);
                        $('#' + Re).remove();
                        reStructureList(getTableId());
                    }
                });
            } else {
                Re = null;
            }
            $('.divMessageBox').fadeOut();
            $('.MessageBoxContainer').fadeOut(1000);
        });
    }

    function deleteSavedimage(Re) {
        $('.MsgTitle').html('<?php echo sprintf($GLOBALS['basic']['deleteRecord_'], "image") ?>');
        $('.pText').html('Click on yes button to delete this image permanently.!!');
        $('.divMessageBox').fadeIn();
        $('.MessageBoxContainer').fadeIn(1000);
        $(".botTempo").on("click", function() {
            var popAct = $(this).attr("id");
            if (popAct == 'yes') {
                $.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: getLocation(),
                    data: 'action=deleteSubimage&id=' + Re,
                    success: function(data) {
                        var msg = eval(data);
                        if (msg.action == 'success') {
                            $('.removeSavedimg' + Re).fadeOut(1000, function() {
                                $('.removeSavedimg' + Re).remove();
                            });
                        }
                    }
                });
            } else {
                Re = '';
            }
            $('.divMessageBox').fadeOut();
            $('.MessageBoxContainer').fadeOut(1000);
        });
    }

    function deleteSavedCompanyDoc(Re) {
        $('.MsgTitle').html('<?php echo sprintf($GLOBALS['basic']['deleteRecord_'], "pdf") ?>');
        $('.pText').html('Click on yes button to delete this pdf permanently.!!');
        $('.divMessageBox').fadeIn();
        $('.MessageBoxContainer').fadeIn(1000);
        $(".botTempo").on("click", function() {
            var popAct = $(this).attr("id");
            if (popAct == 'yes') {
                $('.removeSavedCompanyDoc' + Re).fadeOut(1000, function() {
                    $('.removeSavedCompanyDoc' + Re).remove();
                });
            } else {
                Re = '';
            }
            $('.divMessageBox').fadeOut();
            $('.MessageBoxContainer').fadeOut(1000);
        });
    }

    function deleteSavedPackageimage(Re) {
        $('.MsgTitle').html('<?php echo sprintf($GLOBALS['basic']['deleteRecord_'], "image") ?>');
        $('.pText').html('Click on yes button to delete this image permanently.!!');
        $('.divMessageBox').fadeIn();
        $('.MessageBoxContainer').fadeIn(1000);
        $(".botTempo").on("click", function() {
            var popAct = $(this).attr("id");
            if (popAct == 'yes') {
                $('#removeSavedimg' + Re).fadeOut(1000, function() {
                    $('#removeSavedimg' + Re).remove();
                    $('.uploader').fadeIn(500);
                });
            } else {
                Re = '';
            }
            $('.divMessageBox').fadeOut();
            $('.MessageBoxContainer').fadeOut(1000);
        });
    }

    function deleteSavedPackageflag(Re) {
        $('.MsgTitle').html('<?php echo sprintf($GLOBALS['basic']['deleteRecord_'], "image") ?>');
        $('.pText').html('Click on yes button to delete this image permanently.!!');
        $('.divMessageBox').fadeIn();
        $('.MessageBoxContainer').fadeIn(1000);
        $(".botTempo").on("click", function() {
            var popAct = $(this).attr("id");
            if (popAct == 'yes') {
                $('#removeSavedflag' + Re).fadeOut(1000, function() {
                    $('#removeSavedflag' + Re).remove();
                    $('.uploader').fadeIn(500);
                });
            } else {
                Re = '';
            }
            $('.divMessageBox').fadeOut();
            $('.MessageBoxContainer').fadeOut(1000);
        });
    }

    function deleteSavedPackageVideo(Re) {
        $('.MsgTitle').html('<?php echo sprintf($GLOBALS['basic']['deleteRecord_'], "video") ?>');
        $('.pText').html('Click on yes button to delete this video permanently.!!');
        $('.divMessageBox').fadeIn();
        $('.MessageBoxContainer').fadeIn(1000);
        $(".botTempo").on("click", function() {
            var popAct = $(this).attr("id");
            if (popAct == 'yes') {
                $('#removeSavedVid' + Re).fadeOut(1000, function() {
                    $('#removeSavedVid' + Re).remove();
                    $('.uploader' + Re).fadeIn(500);
                });
            } else {
                Re = '';
            }
            $('.divMessageBox').fadeOut();
            $('.MessageBoxContainer').fadeOut(1000);
        });
    }

    $(document).on('blur', 'input[name="title"], input[name="slug"]', function() {
        var title = $(this).val();
        var actid = $('#idValue').val();
        $.ajax({
            url: getLocation(),
            type: 'POST',
            dataType: 'json',
            data: {
                'action': 'slug',
                'title': title,
                'actid': actid
            },
        }).done(function(data) {
            var msg = eval(data);
            $('input[name="slug"]').val(msg.result);
            $('span#error').html(msg.msgs);
        });
        return false;
    });

    function editImageTitle(Re) {
        var clicked = $('.clicked' + Re);
        $(clicked).html("");
        $('<input/>').attr({
            type: 'text',
            id: 'ne-title',
            name: 'ne-title',
            class: 'validate[required,length[0,250]] col-md-9',
            'imgId': Re
        }).appendTo($(clicked)).focus();
        $(clicked).append(' <button type="submit" id="ne-submit" class="col-md-3">Save</button>');
        $('.up-title').on("click", "#ne-submit", function(e) {
            var data = $("#ne-title");
            var id = $(data).attr("imgId");
            var title = $(data).val();
            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: getLocation(),
                data: 'action=editSubGalleryImageText&id=' + id + '&title=' + title,
                success: function(data) {
                    var msg = eval(data);
                    if (msg.action == 'success') {
                        showMessage(msg.action, msg.message);
                        setTimeout(function() {
                            window.location.href = window.location.href;
                        }, 3000);
                    }
                    if (msg.action == 'error') {
                        showMessage(msg.action, msg.message);
                        setTimeout(function() {
                            window.location.href = window.location.href;
                        }, 3000);
                    }
                    if (msg.action == 'notice') {
                        showMessage(msg.action, msg.message);
                        setTimeout(function() {
                            window.location.href = window.location.href;
                        }, 3000);
                    }
                }
            });
        });
    }

    function subdeleteSelectedRecords(idArray) {
        if (!idArray) return;
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: getLocation(),
            data: {
                action: 'subibulkDelete',
                idArray: idArray
            },
            success: function(data) {
                if (data.action === 'success') {
                    showMessage('success', data.message);
                    var ids = idArray.split("|");
                    for (var i = 1; i < ids.length; i++) {
                        $('#' + ids[i]).remove();
                    }
                    reStructureList(getTableId());
                } else {
                    showMessage('error', data.message);
                }
            },
            error: function(err) {
                console.error("AJAX error:", err);
                showMessage('error', 'AJAX request failed.');
            }
        });
    }

    function toggleItineraryStatus(idArray) {
        if (!idArray) return;
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: getLocation(),
            data: "action=subibulkToggleStatus&idArray=" + idArray,
            success: function(msg) {
                var myMessage = idArray.split("|");
                var counter = myMessage.length;
                for (i = 1; i < counter; i++) {
                    var status = $('#imgHolder_' + myMessage[i]).attr('status');
                    newStatus = (status == 1) ? 0 : 1;
                    $('#imgHolder_' + myMessage[i]).attr({
                        'status': newStatus
                    });
                    if (status == 1) {
                        $('#imgHolder_' + myMessage[i]).removeClass("bg-green").addClass("bg-red");
                        $('#imgHolder_' + myMessage[i]).attr("data-original-title", "Click to Publish");
                    } else {
                        $('#imgHolder_' + myMessage[i]).removeClass("bg-red").addClass("bg-green");
                        $('#imgHolder_' + myMessage[i]).attr("data-original-title", "Click to Un-publish");
                    }
                }
                showMessage('success', 'Status has been toggled.');
            },
            error: function(err) {
                console.error("AJAX error:", err);
                showMessage('error', 'AJAX request failed.');
            }
        });
    }

    $('.statusItinerary').on('click', function() {
        var $link = $(this);
        var id = $link.attr('moduleId');
        var status = $link.attr('status');
        var newStatus = (status == 1) ? 0 : 1;
        $link.css('pointer-events', 'none');
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: getLocation(),
            data: "action=subisingleToggleStatus&id=" + id,
            success: function(msg) {
                if (msg.action === 'success') {
                    $link.attr({
                        'status': newStatus
                    });
                    if (status == 1) {
                        $link.removeClass("bg-green").addClass("bg-red");
                        $link.attr("data-original-title", "Click to Publish");
                    } else {
                        $link.removeClass("bg-red").addClass("bg-green");
                        $link.attr("data-original-title", "Click to Un-publish");
                    }
                } else {
                    showMessage('error', 'Server failed to update status.');
                }
            },
            error: function(err) {
                console.error("AJAX error:", err);
                showMessage('error', 'AJAX request failed.');
            },
            complete: function() {
                $link.css('pointer-events', 'auto');
            }
        });
    });

    function exploreLinkTypeSelect(Re) {
        if (Re == 0) {
            $('#exploreLinkPage_chosen').removeClass("hide").show();
            if ($('#explorelinksrc').val() == 'https://www.') $('#explorelinksrc').val('');
        } else {
            $('#exploreLinkPage_chosen').addClass("hide").hide();
            if ($('#explorelinksrc').val() == '') $('#explorelinksrc').val('https://www.');
        }
    }

    window.includeLinkTypeSelect = function(element) {
        var $row = $(element).closest('.mrg10B');
        var linkType = $row.find('input[name*="linktype"]:checked').val();
        var $pageSelectParent = $row.find('.incexc-page-select').closest('.col-md-6');
        
        if (linkType == 0) {
            // Internal link - show dropdown
            $pageSelectParent.show();
            if ($row.find('.incexc-url-input').val() == 'http://www.') {
                $row.find('.incexc-url-input').val('');
            }
        } else {
            // External link - hide dropdown
            $pageSelectParent.hide();
            if ($row.find('.incexc-url-input').val() == '') {
                $row.find('.incexc-url-input').val('http://www.');
            }
        }
    };

    $(document).on('change', '.incexc-page-select', function() {
        var $row = $(this).closest('.mrg10B');
        $row.find('.incexc-url-input').val($(this).val());
    });

    $(document).ready(function() {
        if($.fn.sortable) {
            $("#includes_sortable, #includes_sortable_2").sortable({
                handle: '.drag-handle',
                cursor: 'move',
                opacity: 0.6
            });
        }
    });

</script>