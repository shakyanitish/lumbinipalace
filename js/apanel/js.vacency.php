<script language="javascript">

    function getLocation() {
        return '<?php echo BASE_URL;?>includes/controllers/ajax.vacency.php';
        
    }

    function getApp() {
         return '<?php echo BASE_URL;?>includes/controllers/ajax.applicant.php';
       
    }

    function getTableId() {
        return 'table_dnd';
    }

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
    var desiredPage = localStorage.getItem("vacencydbpage") ? parseInt(localStorage.getItem("vacencydbpage")) : 0;
    var desiredLength = localStorage.getItem("vacencydblength") ? parseInt(localStorage.getItem("vacencydblength")) : 10;

    console.log("Desired Page:", desiredPage);
    console.log("Desired Length:", desiredLength);

    // Initialize the DataTable
    var oTable = $('#example').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "iDisplayLength": desiredLength // Set the initial length
    }).rowReordering({
        sURL: "<?php echo BASE_URL;?>includes/controllers/ajax.vacency.php?action=sort",
        fnSuccess: function (message) {
            var msg = jQuery.parseJSON(message);
            showMessage(msg.action, msg.message);
        }
    });

    // Store the current page in local storage when a pagination button is clicked
    $(document).on("click", ".fg-button", function () {
        var currentPage = oTable.fnPagingInfo().iPage;
        localStorage.setItem("vacencydbpage", currentPage);
        console.log("Stored Page:", currentPage);
    });

    // Store the selected length in local storage when the length menu changes
    $(document).on('change', '#example_length select', function () {
        var selectedLength = $(this).val();
        localStorage.setItem("vacencydblength", selectedLength);
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


    /***************************************** Vacency Create Date *******************************************/
    $(document).ready(function () {
        $('#vacency_date').datepicker({
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'yy-mm-dd'
        });
    });
    $(document).ready(function () {
        $('#date1').datepicker({
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'yy-mm-dd'
        });
    });

    /***************************************** View Subpackage Lists *******************************************/
    function viewApplicantlist(Re) {
        window.location.href = "<?php echo ADMIN_URL?>vacency/vacencylist/" + Re;
    }

    /*************************************** Toggle AddEdit Form ********************************************/
    function toggleMetadata() {
        $(".metadata").slideToggle("slow", function () {
        });
    }

    $(document).ready(function () {
        // form submisstion actions
        jQuery('#vacency_frm').validationEngine({
            autoHidePrompt: true,
            scroll: true,
            onValidationComplete: function (form, status) {
                if (status == true) {
                    $('#btn-submit').attr('disabled', 'true');
                    var action = ($('#idValue').val() == 0) ? "action=add&" : "action=edit&";
                    for (instance in CKEDITOR.instances)
                        CKEDITOR.instances[instance].updateElement();

                    var data = $('#vacency_frm').serialize();
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
                                    window.location.href = "<?php echo ADMIN_URL?>vacency/list";
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
                                        window.location.href = "<?php echo ADMIN_URL?>vacency/list";
                                    }, 3000);
                            }
                            if (msg.action == 'notice') {
                                showMessage(msg.action, msg.message);
                                setTimeout(function () {
                                    window.location.href = "<?php echo ADMIN_URL?>vacency/list";
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

    // Edit records
    function editRecord(Re) {
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: getLocation(),
            data: 'action=editExistsRecord&id=' + Re,
            success: function (data) {
                var msg = eval(data);
                $("#title").val(msg.title);
                $("#idValue").val(msg.editId);
            }
        });
    }

    // Deleting Record
    function recordDelete(Re) {
        $('.MsgTitle').html('<?php echo sprintf($GLOBALS['basic']['deleteRecord_'], "vacency")?>');
        $('.pText').html('Click on yes button to delete this vacency permanently.!!');
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
            }
            $('.divMessageBox').fadeOut();
            $('.MessageBoxContainer').fadeOut(1000);
        });
    }

    // Deleting Record
    function recordApplicationDelete(Re) {
        $('.MsgTitle').html('<?php echo sprintf($GLOBALS['basic']['deleteRecord_'], "vacency")?>');
        $('.pText').html('Click on yes button to delete this vacency permanently.!!');
        $('.divMessageBox').fadeIn();
        $('.MessageBoxContainer').fadeIn(1000);

        $(".botTempo").on("click", function () {
            var popAct = $(this).attr("id");
            if (popAct == 'yes') {
                $.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: getApp(),
                    data: 'action=delete&id=' + Re,
                    success: function (data) {
                        var msg = eval(data);
                        showMessage(msg.action, msg.message);
                        $('#' + Re).remove();
                        reStructureList(getTableId());
                    }
                });
            }
            $('.divMessageBox').fadeOut();
            $('.MessageBoxContainer').fadeOut(1000);
        });
    }

    /***************************************** View Vacencys Lists *******************************************/
    function viewvacencylist() {
        window.location.href = "<?php echo ADMIN_URL?>vacency/list";
    }
    function viewapplicantlist(Re) {
        window.location.href = "<?php echo ADMIN_URL?>vacency/vacencylist/"+Re;
    }

    /***************************************** Add New Vacencys *******************************************/
    function AddNewVacencys() {
        window.location.href = "<?php echo ADMIN_URL?>vacency/addEdit";
    }

    /***************************************** Edit records *****************************************/
    function editRecord(Re) {
        window.location.href = "<?php echo ADMIN_URL?>vacency/addEdit/"+Re;
    }

    /***************************************** Edit records *****************************************/
    function editApplicant(Re) {
        window.location.href = "<?php echo ADMIN_URL?>vacency/addEditApplicant/" + Re;
    }
</script>