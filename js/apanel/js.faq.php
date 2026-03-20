<script language="javascript">

    function getLocation() {
        return '<?php echo BASE_URL;?>includes/controllers/ajax.faq.php';
    }

    function getTableId() {
        return 'table_dnd';
    }

    //Data Table initialization and setting the page and length values from local storage
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
        var desiredPage = localStorage.getItem("faqcatdbpage") ? parseInt(localStorage.getItem("faqcatdbpage")) : 0;
        var desiredLength = localStorage.getItem("faqcatdblength") ? parseInt(localStorage.getItem("faqcatdblength")) : 10;

        console.log("Desired Page:", desiredPage);
        console.log("Desired Length:", desiredLength);

        // Initialize the DataTable
        var oTable = $('#example').dataTable({
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "iDisplayLength": desiredLength // Set the initial length
        }).rowReordering({
            sURL: "<?php echo BASE_URL;?>includes/controllers/ajax.faq.php?action=sort",
            fnSuccess: function (message) {
                var msg = jQuery.parseJSON(message);
                showMessage(msg.action, msg.message);
            }
        });

        // Store the current page in local storage when a pagination button is clicked
        $(document).on("click", ".fg-button", function () {
            var currentPage = oTable.fnPagingInfo().iPage;
            localStorage.setItem("faqcatdbpage", currentPage);
            console.log("Stored Page:", currentPage);
        });

        // Store the selected length in local storage when the length menu changes
        $(document).on('change', '#example_length select', function () {
            var selectedLength = $(this).val();
            localStorage.setItem("faqcatdblength", selectedLength);
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
        var desiredPage = localStorage.getItem("faqdbpage") ? parseInt(localStorage.getItem("faqdbpage")) : 0;
        var desiredLength = localStorage.getItem("faqdblength") ? parseInt(localStorage.getItem("faqdblength")) : 10;

        console.log("Desired Page:", desiredPage);
        console.log("Desired Length:", desiredLength);

        // Initialize the DataTable
        var oTable = $('#subexample').dataTable({
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "iDisplayLength": desiredLength // Set the initial length
        }).rowReordering({
            sURL: "<?php echo BASE_URL;?>includes/controllers/ajax.faq.php?action=subSort",
            fnSuccess: function (message) {
                var msg = jQuery.parseJSON(message);
                showMessage(msg.action, msg.message);
            }
        });

        // Store the current page in local storage when a pagination button is clicked
        $(document).on("click", ".fg-button", function () {
            var currentPage = oTable.fnPagingInfo().iPage;
            localStorage.setItem("faqdbpage", currentPage);
            console.log("Stored Page:", currentPage);
        });

        // Store the selected length in local storage when the length menu changes
        $(document).on('change', '#example_length select', function () {
            var selectedLength = $(this).val();
            localStorage.setItem("faqdblength", selectedLength);
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

    $(document).ready(function () {
        $('.btn-submit').on('click', function () {
            var actVal = $(this).attr('btn-action');
            $('#idValue').attr('myaction', actVal);
        })

        // form submisstion actions
        jQuery('#faq_frm').validationEngine({
            autoHidePrompt: true,
            scroll: false,
            onValidationComplete: function (form, status) {
                if (status == true) {
                    $('.btn-submit').attr('disabled', 'true');
                    var action = ($('#idValue').val() == 0) ? "action=add&" : "action=edit&";
                    for (instance in CKEDITOR.instances)
                        CKEDITOR.instances[instance].updateElement();

                    var data = $('#faq_frm').serialize();
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
                                $('.btn-submit').removeAttr('disabled');
                                $('.formButtons').show();
                                return false
                            }
                            if (msg.action == 'success') {
                                showMessage(msg.action, msg.message);
                                var actionId = $('#idValue').attr('myaction');
                                if (actionId == 2)
                                    setTimeout(function () {
                                        window.location.href = "<?php echo ADMIN_URL?>faq/list";
                                    }, 3000);
                                if (actionId == 1)
                                    setTimeout(function () {
                                        window.location.href = "<?php echo ADMIN_URL?>faq/addEdit";
                                    }, 3000);
                                if (actionId == 0)
                                    setTimeout(function () {
                                        window.location.href = "";
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

        // form submisstion actions
        jQuery('#offers_meta_frm').validationEngine({
            prettySelect: true,
            autoHidePrompt: true,
            useSuffix: "_chosen",
            scroll: true,
            onValidationComplete: function (form, status) {
                if (status == true) {
                    $('.btn-submit').attr('disabled', 'true');
                    var action = "action=metadata&";
                    var data = $('#offers_meta_frm').serialize();
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
                                $('.btn-submit').removeAttr('disabled');
                                $('.formButtons').show();
                                return false
                            }
                            if (msg.action == 'success') {
                                showMessage(msg.action, msg.message);
                                var actionId = $('#idValue').attr('myaction');
                                // console.log(actionId);
                                if (actionId == 0)
                                    setTimeout(function () {
                                        window.location.href = "<?php echo ADMIN_URL?>faq/list";
                                    }, 3000);
                            }
                            if (msg.action == 'notice') {
                                showMessage(msg.action, msg.message);
                                setTimeout(function () {
                                    window.location.href = "<?php echo ADMIN_URL?>faq/list";
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

        jQuery('#faq_frmm').validationEngine({
            autoHidePrompt: true,
            scroll: false,
            onValidationComplete: function (form, status) {
                if (status == true) {
                    var Re = $("#category").val();
                    $('.btn-submit').attr('disabled', 'true');
                    var action = ($('#idValue').val() == 0) ? "action=addSub&" : "action=editSub&";
                    for (instance in CKEDITOR.instances)
                        CKEDITOR.instances[instance].updateElement();

                    var data = $('#faq_frmm').serialize();
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
                                $('.btn-submit').removeAttr('disabled');
                                $('.formButtons').show();
                                return false
                            }
                            if (msg.action == 'success') {
                                showMessage(msg.action, msg.message);
                                var actionId = $('#idValue').attr('myaction');
                                if (actionId == 2)
                                    setTimeout(function () {
                                        window.location.href = "<?php echo ADMIN_URL?>faq/sublist/" + Re;
                                    }, 3000);
                                if (actionId == 1)
                                    setTimeout(function () {
                                        window.location.href = "<?php echo ADMIN_URL?>faq/addEditSub/" + Re;
                                    }, 3000);
                                if (actionId == 0)
                                    setTimeout(function () {
                                        window.location.href = "";
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
    });


    // Deleting Record
    function recordDelete(Re) {
        $('.MsgTitle').html('<?php echo sprintf($GLOBALS['basic']['deleteRecord_'], "FAQ")?>');
        $('.pText').html('Click on yes button to delete this FAQ permanently.!!');
        $('.divMessageBox').fadeIn();
        $('.MessageBoxContainer').fadeIn(1000);

        $(".botTempo").on("click", function () {
            var popAct = $(this).attr("id");
            if (popAct == 'yes') {
                $.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: getLocation(),
                    data: 'action=deleteSub&id=' + Re,
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

    /*************************************** Toggle AddEdit Form ********************************************/
    function toggleMetadata() {
        $(".metadata").slideToggle("slow", function () {
        });
    }

    /***************************************** View FAQ Lists *******************************************/
    function viewFaqList() {
        window.location.href = "<?php echo ADMIN_URL?>faq/list";
    }

    /***************************************** Add New FAQ *******************************************/
    function addNewFaq(Re) {
        window.location.href = "<?php echo ADMIN_URL?>faq/addEditSub/" + Re;
    }

    /***************************************** Edit FAQ *****************************************/
    function editRecord(Pid, Re) {
        window.location.href = "<?php echo ADMIN_URL?>faq/addEditSub/" + Pid + "/" + Re;
    }

    function addNewFaqCategory() {
        window.location.href = "<?php echo ADMIN_URL?>faq/addEdit";
    }

    function editCategory(Re) {
        window.location.href = "<?php echo ADMIN_URL?>faq/addEdit/" + Re;
    }

    function viewSubFaqList(Re) {
        window.location.href = "<?php echo ADMIN_URL?>faq/sublist/" + Re;
    }

    function deleteCategory(Re) {
        $('.MsgTitle').html('<?php echo sprintf($GLOBALS['basic']['deleteRecord_'], "FAQ Category")?>');
        $('.pText').html('Click on yes button to delete this record permanently.!!');
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

</script>