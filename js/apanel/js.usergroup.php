<script language="javascript">

    function getLocation() {
        return '<?php echo BASE_URL;?>includes/controllers/ajax.usergroup.php';
    }

    function getTableId() {
        return 'table_dnd';
    }

    /***************************************** Re ordering Users *******************************************/
    $(document).ready(function () {
    // Function to get paging information
    $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings) {
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

    // Retrieve the stored page and length values from local storage
    var desiredPage = localStorage.getItem("usergroupdbpage") ? parseInt(localStorage.getItem("usergroupdbpage")) : 0;
    var desiredLength = localStorage.getItem("usergroupdblength") ? parseInt(localStorage.getItem("usergroupdblength")) : 10;

    console.log("Desired Page:", desiredPage);
    console.log("Desired Length:", desiredLength);

    // Initialize the DataTable
    var oTable = $('#example').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "iDisplayLength": desiredLength // Set the initial length
    }).rowReordering({
        sURL: "<?php echo BASE_URL;?>includes/controllers/ajax.usergroup.php?action=sort",
        fnSuccess: function (message) {
            var msg = jQuery.parseJSON(message);
            showMessage(msg.action, msg.message);
        }
    });

    // Store the current page in local storage when a pagination button is clicked
    $(document).on("click", ".fg-button", function () {
        var currentPage = oTable.fnPagingInfo().iPage;
        localStorage.setItem("usergroupdbpage", currentPage);
        console.log("Stored Page:", currentPage);
    });

    // Store the selected length in local storage when the length menu changes
    $(document).on('change', '#example_length select', function () {
        var selectedLength = $(this).val();
        localStorage.setItem("usergroupdblength", selectedLength);
        console.log("Stored Length:", selectedLength);

        // Handle the -1 (All) case
        if (selectedLength == -1) {
            // Disable pagination controls when "All" is selected
            $('.fg-button').prop('disabled', true);
        } else {
            // Enable pagination controls for other options
            $('.fg-button').prop('disabled', false);
        }
    });

    // Set the desired page after the table is initialized
    if (oTable.fnPagingInfo() != null) {
        // Only change the page if the length is not -1 (All)
        if (desiredLength != -1) {
            oTable.fnPageChange(desiredPage, true);
            console.log("Initialized on Page:", desiredPage);
        }
    }
});


    /***************************************** USer Record delete *******************************************/
    function recordDelete(Re) {
        $('.MsgTitle').html('<?php echo sprintf($GLOBALS['basic']['deleteRecord_'], "User Group")?>');
        $('.pText').html('Click on yes button to delete this user group permanently.!!');
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

    /***************************************** Add new *******************************************/
    function addNewGroup() {
        window.location.href = "<?php echo ADMIN_URL?>usergroup/addEdit";
    }

    /***************************************** View Users Lists *******************************************/
    function viewList() {
        window.location.href = "<?php echo ADMIN_URL?>usergroup/list";
    }

    /***************************************** Edit User login Info *******************************************/
    function editRecord(Re) {
        window.location.href = "<?php echo ADMIN_URL?>usergroup/addEdit/" + Re;
    }

    function permission(Re) {
        window.location.href = "<?php echo ADMIN_URL?>usergroup/permission/" + Re;
    }

    /***************************************** AddEdit login Info *******************************************/
    $(document).ready(function () {
        // form submisstion actions
        jQuery('#adminusersetting_frm').validationEngine({
            prettySelect: true,
            useSuffix: "_chosen",
            autoHidePrompt: true,
            scroll: false,
            onValidationComplete: function (form, status) {
                if (status == true) {
                    $('#btn-submit').attr('disabled', 'true');
                    var action = ($('#idValue').val() == 0) ? "action=addNewUser&" : "action=editNewUser&";
                    var data = $('#adminusersetting_frm').serialize();
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
                                    window.location.href = "<?php echo ADMIN_URL?>usergroup/list";
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

        jQuery('#permission_frm').validationEngine({
            prettySelect: true,
            autoHidePrompt: true,
            scroll: false,
            onValidationComplete: function (form, status) {
                if (status == true) {
                    $('#btn-submit').attr('disabled', 'true');
                    var action = "action=userPermission&";
                    var data = $('#permission_frm').serialize();
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
                                    window.location.href = "<?php echo ADMIN_URL?>usergroup/list";
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
        });
    });

    jQuery(document).on('click', 'a.check-all', function () {
        jQuery('input.mcheck').prop("checked", true);
    });

    jQuery(document).on('click', 'a.uncheck-all', function () {
        jQuery('input.mcheck').prop("checked", false);
    });

    jQuery(document).on('click', 'input.parent', function () {
        var _val = jQuery(this).val();
        if (jQuery(this).prop('checked') == true) {
            jQuery('input.child-' + _val).prop('checked', true);
        }
        else {
            jQuery('input.child-' + _val).prop('checked', false);
        }
    });
</script>