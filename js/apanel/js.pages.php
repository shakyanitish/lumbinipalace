<script language="javascript">
    //gives the ajax url for pages
    function getLocation() {
        return '<?php echo BASE_URL; ?>includes/controllers/ajax.pages.php';
    }
    //gives the table id for pages
    function getTableId() {
        return 'table_dnd';
    }

    /* ----------------------------------------------------
      Handles the pagination of the pages list with DataTables
    ---------------------------------------------------- */
    $(document).ready(function() {
        // Extend DataTable API to get paging info
        $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings) {
            if (!oSettings || oSettings._iDisplayStart === undefined) {
                return null;
            }
            return {
                "iStart": oSettings._iDisplayStart,//starting record index like  page 2 shows rows 11–20, iStart = 10
                "iEnd": oSettings.fnDisplayEnd(),//ending record index like page 2 shows rows 11–20, iEnd = 20
                "iLength": oSettings._iDisplayLength,//number of records to be shown per page
                "iTotal": oSettings.fnRecordsTotal(),//total records in the dataset
                "iFilteredTotal": oSettings.fnRecordsDisplay(),//total records after filtering/searching
                "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),//current page index (0-based)
                "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)//total number of pages
            };
        };

        // Retrieve stored page and length
        var desiredPage = localStorage.getItem("pagesdbpage") ? parseInt(localStorage.getItem("pagesdbpage")) : 0;//which pages user was last viewing
        var desiredLength = localStorage.getItem("pagesdblength") ? parseInt(localStorage.getItem("pagesdblength")) : 10;//how many records user wanted to see per page

        // Initialize the DataTable list with row reordering
        var oTable = $('#example').dataTable({
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "aLengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            "iDisplayLength": desiredLength
        }).rowReordering({
            //surl php script that recieves the new order via ajax and updates the database
            sURL: "<?php echo BASE_URL; ?>includes/controllers/ajax.pages.php?action=sort",
            fnSuccess: function(message) {
                var msg = jQuery.parseJSON(message);//parse the json message returned from the server to javascript
                showMessage(msg.action, msg.message);//pages has been sorted successfully
            }
        });

        // Store current page on pagination click
        $(document).on("click", ".fg-button", function() {
            var currentPage = oTable.fnPagingInfo().iPage;
            localStorage.setItem("pagesdbpage", currentPage);
        });

        // Store current length on length change
        $(document).on('change', '#example_length select', function() {
            var selectedLength = $(this).val();
            localStorage.setItem("pagesdblength", selectedLength);

            if (selectedLength == -1) {
                $('.fg-button').prop('disabled', true);
            } else {
                $('.fg-button').prop('disabled', false);
            }
        });

        // Set to desired page after initialization
        if (oTable.fnPagingInfo() != null) {
            if (desiredLength != -1) {
                oTable.fnPageChange(desiredPage, true);
            }
        }
    });

    // Datepicker initialization

    $(document).ready(function() {
        // Save button handlers
        $('.btn-submit').on('click', function() {
            var actVal = $(this).attr('btn-action');
            $('#idValue').attr('myaction', actVal);
        });

        if ($('#date').length) {
            $('#date').datepicker({
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                dateFormat: 'yy-mm-dd',

            });
        }
    });



    /******************************** Remove saved Page image permanently ********************************/
    function deleteSavedimage(Re) {// re is random id generated while displaying the saved image
        $('.MsgTitle').html('Do you want to delete the record ?');
        $('.pText').html('Clicking yes will be delete this record permanently. !!!');
        $('.divMessageBox').fadeIn();
        $('.MessageBoxContainer').fadeIn(1000);

        $(".botTempo").on("click", function() {
            var popAct = $(this).attr("id");
            if (popAct == 'yes') {
                $('#removeSavedimg' + Re).fadeOut(1000, function() {
                    $('#removeSavedimg' + Re).remove(); //#removeSavedimg + Re →"removeSavedimg123"
                    $('.uploader').fadeIn(500);
                });
            } else {
                Re = '';
            }
            $('.divMessageBox').fadeOut();
            $('.MessageBoxContainer').fadeOut(1000);
        });
    }

    function deletesavepageimage(Re) {
        $('.MsgTitle').html('Do you want to delete the record ?');
        $('.pText').html('Clicking yes will be delete this record permanently. !!!');
        $('.divMessageBox').fadeIn();
        $('.MessageBoxContainer').fadeIn(1000);

        $(".botTempo").on("click", function() {
            var popAct = $(this).attr("id");
            if (popAct == 'yes') {
                $('#removesaveimg' + Re).fadeOut(1000, function() {
                    $('#removesaveimg' + Re).remove();
                    $('.uploader').fadeIn(500);
                });
            } else {
                Re = '';
            }
            $('.divMessageBox').fadeOut();
            $('.MessageBoxContainer').fadeOut(1000);
        });
    }

    /* ----------------------------------------------------
       Form Submission
    ---------------------------------------------------- */

    $(document).ready(function() {
        $('.btn-submit').on('click', function() {
            var actVal = $(this).attr('btn-action');
            $('#idValue').attr('myaction', actVal);
        });

        // form submisstion actions
        jQuery('#pages_frm').validationEngine({
            autoHidePrompt: true,
            scroll: false,
            onValidationComplete: function(form, status) {
                if (status == true) {
                    $('.btn-submit').attr('disabled', 'true');
                    var action = ($('#idValue').val() == 0) ? "action=add&" : "action=edit&";
                    for (instance in CKEDITOR.instances)
                        CKEDITOR.instances[instance].updateElement();
                    var data = $('#pages_frm').serialize();
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
                                    setTimeout(function() { window.location.href = "<?php echo ADMIN_URL?>pages/list"; }, 3000);
                                if (actionId == 1)
                                    setTimeout(function() { window.location.href = "<?php echo ADMIN_URL?>pages/addEdit"; }, 3000);
                                if (actionId == 0)
                                    setTimeout(function() { window.location.href = ""; }, 3000);
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
        })


    });


    /* ----------------------------------------------------
       Delete Record completely
    ---------------------------------------------------- */
    function recordDelete(Re) {
        $('.MsgTitle').html('<?php echo sprintf($GLOBALS['basic']['deleteRecord_'], "HomePage") ?>');
        $('.pText').html('Click on yes button to delete this Homepage permanently!');
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

    /*************************************** Toggle Meta tags ********************************************/
    function toggleMetadata() {
        $(".metadata").slideToggle("slow");
    }


    /***************************************** Navigation *******************************************/
    function viewPageslist() {
        window.location.href = "<?php echo ADMIN_URL ?>pages/list";// Redirects to the pages list page
    }

    function AddNewPages() {
        window.location.href = "<?php echo ADMIN_URL ?>pages/addEdit";// Redirects to the add/edit page
    }

    function editRecord(Re) {
        window.location.href = "<?php echo ADMIN_URL ?>pages/addEdit/" + Re;// Re is the page id    
    }

    /******************************** Remove Temp Upload Image ********************************/
    function deleteTempimage(Re) {
        $('#previewUserimage' + Re).fadeOut(1000, function() {
            $('#previewUserimage' + Re).remove();
        });
    }

    /******************************** Generate New Slug ********************************/
    $(document).on('blur', 'input[name="title"], input[name="slug"]', function() {// when title field loses focus
        var title = $(this).val();
        var actid = $('#idValue').val();
        $.ajax({// ajax call to generate slug
                url: getLocation(),
                type: 'POST',
                dataType: 'json',
                data: {
                    'action': 'slug',
                    'title': title,
                    'actid': actid
                },
            })
            .done(function(data) {
                var msg = eval(data);
                $('input[name="slug"]').val(msg.result);
                $('span#error').html(msg.msgs);
            });
        return false;
    });
</script>