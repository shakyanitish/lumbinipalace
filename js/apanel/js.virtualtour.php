<script language="javascript">

    //FUNCTIONS WHICH CONTAINS URL, STATUS TOGGLE
    function getLocation() {
        return '<?php echo BASE_URL;?>includes/controllers/ajax.virtualtour.php';
    }

    //FUNCTIONS WHICH CONTAINS URL,WHICH ARE PASSED ON AJAX URL
    function three60() {
        return '<?php echo BASE_URL;?>includes/controllers/ajax.three60.php';
    }

    function hotspot() {
        return '<?php echo BASE_URL;?>includes/controllers/ajax.hotspots.php';
    }

    function getTableId(){
        return 'table_dnd';
    }

    $(document).ready(function () {
        //ON SUBMIT - TO PASS ITS OWN ID
        $('.btn-submit').on('click', function () {
            var actVal = $(this).attr('btn-action');
            $('#idValue').attr('myaction', actVal);
        })

        //VIRTUAL TOUR - CHECK FOR VALIDATION
        jQuery('#virtual_frm').validationEngine({
            prettySelect: true,
            autoHidePrompt: true,
            useSuffix: "_chosen",
            promptPosition: "bottomLeft",
            scroll: true,
            onValidationComplete: function (form, status) {
                if (status == true) { //CHECKING STATUS IF ALL FIELDS ARE VALIDATED OR NOT
                    $('.btn-submit').attr('disabled', 'true');
                    var action = ($('#idValue').val() == 0) ? "action=addVirtual&" : "action=editVirtual&";
                    for (instance in CKEDITOR.instances)
                        CKEDITOR.instances[instance].updateElement();
                    var data = $('#virtual_frm').serialize();
                    queryString = action + data;
                    //AJAX LOADING PAGE ON SUBMIT
                    $.ajax({
                        type: "POST",
                        dataType: "JSON",
                        url: getLocation(),
                        data: queryString,
                        //ON FORM SUBMIT - ON SUCCESS
                        success: function (data) {
                            var msg = eval(data);
                            if (msg.action == 'warning') {
                                showMessage(msg.action, msg.message);
                                $('.btn-submit').removeAttr('disabled');
                                $('.formButtons').show();
                                return false
                            }
                            //IF ACTION IS SUCCESS THEN IT REDIRECTS TO INDIVIDUAL PAGE AS PER BUTTON CLICKS
                            // SAVE = stay on same page, SAVE & MORE = load new add form page, SAVE & QUIT = load list page
                            if (msg.action == 'success') {
                                showMessage(msg.action, msg.message);
                                var actionId = $('#idValue').attr('myaction');
                                if (actionId == 2)
                                    setTimeout(function () {
                                        window.location.href = "<?php echo ADMIN_URL?>virtualtour/list/";
                                    }, 3000);
                                if (actionId == 1)
                                    setTimeout(function () {
                                        window.location.href = "<?php echo ADMIN_URL?>virtualtour/addEditVirtualTour/";
                                    }, 3000);
                                if (actionId == 0)
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
                            //SENDING ERROR MESSAGE
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

        // 360 IMAGE -CHECK FOR VALIDATION 
        jQuery('#three60_frm').validationEngine({
            prettySelect: true,
            autoHidePrompt: true,
            useSuffix: "_chosen",
            promptPosition: "bottomLeft",
            scroll: true,
            onValidationComplete: function (form, status) {//CHECKING STATUS IF ALL FIELDS ARE VALIDATED OR NOT
                if (status == true) {
                    $('.btn-submit').attr('disabled', 'true');
                    var action = ($('#idValue').val() == 0) ? "action=addThree60&" : "action=editThree60&";
                    var parentid = $('#parentid').val();

                    for (instance in CKEDITOR.instances)
                        CKEDITOR.instances[instance].updateElement();

                    var data = $('#three60_frm').serialize();
                    queryString = action + data;
                    //AJAX LOADING PAGE ON SUBMIT
                    $.ajax({
                        type: "POST",
                        dataType: "JSON",
                        url: three60(),
                        data: queryString,
                        //ON FORM SUBMIT - ON SUCCESS
                        success: function (data) {
                            var msg = eval(data);
                            if (msg.action == 'warning') {
                                showMessage(msg.action, msg.message);
                                $('.btn-submit').removeAttr('disabled');
                                $('.formButtons').show();
                                return false
                            }
                            //IF ACTION IS SUCCESS THEN IT REDIRECTS TO INDIVIDUAL PAGE AS PER BUTTON CLICKS
                            if (msg.action == 'success') {
                                showMessage(msg.action, msg.message);
                                var actionId = $('#idValue').attr('myaction');
                                // SAVE & QUIT = load list page
                                if (actionId == 2)
                                    setTimeout(function () {
                                        window.location.href = "<?php echo ADMIN_URL?>virtualtour/threeImageList/" + parentid;
                                    }, 3000);
                                // SAVE & MORE = load new add form page
                                if (actionId == 1)
                                    setTimeout(function () {
                                        window.location.href = "<?php echo ADMIN_URL?>virtualtour/addEditthreeImage/" + parentid;
                                    }, 3000);
                                // SAVE = stay on same page
                                if (actionId == 0)
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
                            //SENDING ERROR MESSAGE
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

        // SUBMITING HOTSPOTS DATA VIA AJAX - CHECKING FOR VALIDATIONS
        jQuery('#hotspot_frm').validationEngine({
            prettySelect: true,
            autoHidePrompt: true,
            useSuffix: "_chosen",
            promptPosition: "bottomLeft",
            scroll: true,
            onValidationComplete: function (form, status) {
                if (status == true) {
                    $('.btn-submit').attr('disabled', 'true');
                    var action = ($('#idValue').val() == 0) ? "action=addHotspot&" : "action=editHotspot&";
                    var parentid = $('#parentid').val();

                    for (instance in CKEDITOR.instances)
                        CKEDITOR.instances[instance].updateElement();

                    var data = $('#hotspot_frm').serialize();
                    queryString = action + data;
                    //AJAX LOADING PAGE ON SUBMIT
                    $.ajax({
                        type: "POST",
                        dataType: "JSON",
                        url: hotspot(),
                        data: queryString,
                        //ON FORM SUBMIT - ON SUCCESS
                        success: function (data) {
                            var msg = eval(data);
                            if (msg.action == 'warning') {
                                showMessage(msg.action, msg.message);
                                $('.btn-submit').removeAttr('disabled');
                                $('.formButtons').show();
                                return false
                            }
                            //IF ACTION IS SUCCESS THEN IT REDIRECTS TO INDIVIDUAL PAGE AS PER BUTTON CLICKS
                            if (msg.action == 'success') {
                                showMessage(msg.action, msg.message);
                                var actionId = $('#idValue').attr('myaction');
                                // SAVE & QUIT = load list page
                                if (actionId == 2)
                                    setTimeout(function () {
                                        window.location.href = "<?php echo ADMIN_URL?>virtualtour/viewhotspotList/" + parentid;
                                    }, 3000);
                                // SAVE & MORE = load new add form page
                                if (actionId == 1)
                                    setTimeout(function () {
                                        window.location.href = "<?php echo ADMIN_URL?>virtualtour/addEdithotspot/" + parentid;
                                    }, 3000);
                                // SAVE = stay on same page
                                if (actionId == 0)
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
                            //SENDING ERROR MESSAGE
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
    })

    $(document).ready(function () {
        $('#example').dataTable({"bJQueryUI": true, "sPaginationType": "full_numbers"})
            .rowReordering({
                sURL: "<?php echo BASE_URL;?>includes/controllers/ajax.virtualtour.php?action=sort",
                fnSuccess: function (message) {
                    var msg = jQuery.parseJSON(message);
                    showMessage(msg.action, msg.message);
                }
            });

        $('#subexample').dataTable({"bJQueryUI": true, "sPaginationType": "full_numbers"})
            .rowReordering({
                sURL: "<?php echo BASE_URL;?>includes/controllers/ajax.three60.php?action=sort",
                fnSuccess: function (message) {
                    var msg = jQuery.parseJSON(message);
                    showMessage(msg.action, msg.message);
                }
            });

        $('#subexampleh').dataTable({"bJQueryUI": true, "sPaginationType": "full_numbers"})
            .rowReordering({
                sURL: "<?php echo BASE_URL;?>includes/controllers/ajax.hotspots.php?action=sort",
                fnSuccess: function (message) {
                    var msg = jQuery.parseJSON(message);
                    showMessage(msg.action, msg.message);
                }
            });

        
        // FUNCTION FOR 360 IMAGE SELECT OPTION
        $('.parent_360').on('change', function () {
            var threeId = $(this).val();
            var virtualId = $(this).attr('vId');
            $('.scene_id').html('<option>Loading...</optioin>');
            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: hotspot(),
                data: "action=filter360Image&virtualId=" + virtualId + "&selct=" + threeId,
                success: function (data) {
                    var msg = eval(data);
                    if (msg.action == 'success') {
                        $('.scene_id').html(msg.result);
                    }
                }
            });
            return false;
        });

        // Hotspot Type change
        $('#hotspot_type').on('change', function () {
            var h_type = $(this).val();
            if(h_type == 'scene'){
                $('.linkTo').removeClass('hide');
            }
            else{
                $('.linkTo, .linkTo2').addClass('hide');
            }
        });

        $('.scene_id').on('change', function () {
            var s_id = $(this).val();
            if(s_id === ''){
                $('.linkTo2').addClass('hide');
            }
            else{
                $('.linkTo2').removeClass('hide');
            }
        });
    });
     
    //Delete temporary 360 Image
    function deleteTempimage(Re) {
        $('#previewRoomsimage' + Re).fadeOut(1000, function () {
            $('#previewRoomsimage' + Re).remove();
        });
    }


    //Delete temporary Hotspot Icon
    function deleteTempicon(Re) {
        $('#previewHotspoticon' + Re).fadeOut(1000, function () {
            $('#previewHotspoticon' + Re).remove();
        });
    }



    /***************************************** Add New Virtual Tour*******************************************/
    function AddNewVirtualTour() {
        window.location.href = "<?php echo ADMIN_URL?>virtualtour/addEditVirtualTour/";
    }

    /***************************************** Edit Virtual Tour *****************************************/
    function editVirtualTour(Re) {
        window.location.href = "<?php echo ADMIN_URL?>virtualtour/addEditVirtualTour/" + Re;
    }

    /***************************************** View Virtual Tour *****************************************/
    function viewVirtualTourList() {
        window.location.href = "<?php echo ADMIN_URL?>virtualtour/list/";
    }

    /***************************************** Deleting Virtual Tour *****************************************/

    function virtualDelete(Re) {
        $('.MsgTitle').html('<?php echo sprintf($GLOBALS['basic']['deleteRecord_'], "Virtual Tour")?>');
        $('.pText').html('Click on yes button to delete this virtual tour permanently.!!');
        $('.divMessageBox').fadeIn();
        $('.MessageBoxContainer').fadeIn(1000);

        $(".botTempo").on("click", function () {
            var popAct = $(this).attr("id");
            if (popAct == 'yes') {
                $.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: getLocation(),
                    data: 'action=deleteVirtual&id=' + Re,
                    success: function (data) {
                        var msg = eval(data);
                        showMessage(msg.action, msg.message);
                        $('#' + Re).remove();
                        reStructureList(getTableId());
                        setTimeout(function () {
                            window.location.href = window.location.href;
                        }, 2000);
                    }
                });
            } else {
                Re = null;
            }
            $('.divMessageBox').fadeOut();
            $('.MessageBoxContainer').fadeOut(1000);
        });
    }

    //Deleting Hotspot Image, while adding or editing the form
    function deleteHotspoticon(Re) {
        $('.MsgTitle').html('Do you want to delete the image?');
        $('.pText').html('Clicking yes will be delete this image permanently!!!');
        $('.divMessageBox').fadeIn();
        $('.MessageBoxContainer').fadeIn(1000);

        $(".botTempo").on("click", function () {
            var popAct = $(this).attr("id");
            if (popAct == 'yes') {
                $('#removeHotspoticon' + Re).fadeOut(1000, function () {
                    $('#removeHotspoticon' + Re).remove();
                    $('.uploader').fadeIn(500);
                });
            } else {
                Re = '';
            }
            $('.divMessageBox').fadeOut();
            $('.MessageBoxContainer').fadeOut(1000);
        });
    }


    /***************************************** Add New 360 Image *******************************************/
    function AddNewthreeImage(Re) {
        window.location.href = "<?php echo ADMIN_URL?>virtualtour/addEditthreeImage/" + Re;
    }

    /***************************************** Edit 360 Image *****************************************/
    function editthreeImage(Pid, Re) {
        window.location.href = "<?php echo ADMIN_URL?>virtualtour/addEditthreeImage/" + Pid + "/" + Re;
    }

    /***************************************** View 360 Image *****************************************/
    function viewthreeImageList(Re) {
        window.location.href = "<?php echo ADMIN_URL?>virtualtour/threeImageList/" + Re;
    }


    /***************************************** Deleting 360 Image *****************************************/
    function three60Delete(Re) {

        $('.MsgTitle').html('<?php echo sprintf($GLOBALS['basic']['deleteRecord_'], "image")?>');
        $('.pText').html('Click on yes button to delete this image permanently.!!');
        $('.divMessageBox').fadeIn();
        $('.MessageBoxContainer').fadeIn(1000);

        $(".botTempo").on("click", function () {
            var popAct = $(this).attr("id");
            if (popAct == 'yes') {
                $.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: three60(),
                    data: 'action=deleteThree60&id=' + Re,
                    success: function (data) {
                        var msg = eval(data);
                        showMessage(msg.action, msg.message);
                        $('#' + Re).remove();
                        reStructureList(getTableId());
                        setTimeout(function () {
                            window.location.href = window.location.href;
                        }, 2000);
                    }
                });
                $('#removeSavedimg' + Re).fadeOut(1000, function () {
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

    //Deleting 360 Image, while adding or editing the form
    function delete360img(Re) {
        $('.MsgTitle').html('Do you want to delete the image ?');
        $('.pText').html('Clicking yes will be delete this image permanently. !!!');
        $('.divMessageBox').fadeIn();
        $('.MessageBoxContainer').fadeIn(1000);

        $(".botTempo").on("click", function () {
            var popAct = $(this).attr("id");
            if (popAct == 'yes') {
                $('#remove360img' + Re).fadeOut(1000, function () {
                    $('#remove360img' + Re).remove();
                    $('.uploader').fadeIn(500);
                });
            } else {
                Re = '';
            }
            $('.divMessageBox').fadeOut();
            $('.MessageBoxContainer').fadeOut(1000);
        });
    }


    /***************************************** Add New Hotspot *******************************************/
    function AddNewhotspot(Re) {
        window.location.href = "<?php echo ADMIN_URL?>virtualtour/addEdithotspot/" + Re;
    }

    /***************************************** Edit Hotspot *****************************************/
    function edithotspot(Pid, Re) {
        window.location.href = "<?php echo ADMIN_URL?>virtualtour/addEdithotspot/" + Pid + "/" + Re;
    }

    /***************************************** View Hotspot *****************************************/
    function viewhotspotList(Re) {
        window.location.href = "<?php echo ADMIN_URL?>virtualtour/viewhotspotList/" + Re;
    }


    /***************************************** Deleting Hotspot *****************************************/
    function hotspotDelete(Re) {
        $('.MsgTitle').html('<?php echo sprintf($GLOBALS['basic']['deleteRecord_'], "Hotspot")?>');
        $('.pText').html('Click on yes button to delete this hotspot permanently.!!');
        $('.divMessageBox').fadeIn();
        $('.MessageBoxContainer').fadeIn(1000);

        $(".botTempo").on("click", function () {
            var popAct = $(this).attr("id");
            if (popAct == 'yes') {
                $.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: hotspot(),
                    data: 'action=deleteHotspot&id=' + Re,
                    success: function (data) {
                        var msg = eval(data);
                        showMessage(msg.action, msg.message);
                        $('#' + Re).remove();
                        reStructureList(getTableId());
                        setTimeout(function () {
                            window.location.href = window.location.href;
                        }, 2000);
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
        /*************************************** 360 Image Status Toggler ******************************************/
        $('.image360StatusToggler').on('click', function () {
            var Re = $(this).attr('moduleid');
            var status = $(this).attr('status');

            newStatus = (status == 1) ? 0 : 1;
            $.ajax({
                type: "POST",
                url: three60(),
                data: "action=imageToggleStatus&id=" + Re,
                success: function (msg) {
                }
            });
            $(this).attr({'status': newStatus});
            if (newStatus == 1) {
                $('#toggleImg' + Re).removeClass("bg-red");
                $('#toggleImg' + Re).addClass("bg-green");
            } else {
                $('#toggleImg' + Re).removeClass("bg-green");
                $('#toggleImg' + Re).addClass("bg-red");
            }
        });

        /***************************************Hotspot Status Toggler ******************************************/
        $('.hotspotStatusToggler').on('click', function () {
            var Re = $(this).attr('moduleid');
            var status = $(this).attr('status');
            newStatus = (status == 1) ? 0 : 1;
            $.ajax({
                type: "POST",
                url: hotspot(),
                data: "action=hotspotToggleStatus&id=" + Re,
                success: function (msg) {
                }
            });
            $(this).attr({'status': newStatus});
            if (newStatus == 1) {
                $('#toggleImg' + Re).removeClass("bg-red");
                $('#toggleImg' + Re).addClass("bg-green");
            } else {
                $('#toggleImg' + Re).removeClass("bg-green");
                $('#toggleImg' + Re).addClass("bg-red");
            }
        });
    });


    function checkIfAnyCheckBoxChecked() {
        var countCheckBox = 0;
        $.each($("input.bulkCheckbox:checked"), function () {
            countCheckBox++;
        });
        if (countCheckBox > 0) {
        } else {
            showMessage('warning', 'Please select at least on row!!.');
            return false;
        }
    }

    function deleteSelectedRecords360(idArray) {
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: three60(),
            data: "action=bulkDelete&idArray=" + idArray,
            success: function (data) {
                var msg = eval(data);
                if (msg.action == 'success') {
                    showMessage(msg.action, msg.message);
                    var myMessage = idArray.split("|");
                    var counter = myMessage.length;
                    for (i = 1; i < counter; i++) {
                        $('#' + myMessage[i]).remove();
                        reStructureList(getTableId());
                        setTimeout(function () {
                            window.location.href = window.location.href;
                        }, 2000);
                    }
                }
                if (msg.action == 'error') {
                    showMessage(msg.action, msg.message);
                }
            }
        });
    }

    function deleteSelectedRecordsHotspot(idArray) {
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: hotspot(),
            data: "action=bulkDelete&idArray=" + idArray,
            success: function (data) {
                var msg = eval(data);
                if (msg.action == 'success') {
                    showMessage(msg.action, msg.message);
                    var myMessage = idArray.split("|");
                    var counter = myMessage.length;
                    for (i = 1; i < counter; i++) {
                        $('#' + myMessage[i]).remove();
                        reStructureList(getTableId());
                        setTimeout(function () {
                            window.location.href = window.location.href;
                        }, 2000);
                    }
                }
                if (msg.action == 'error') {
                    showMessage(msg.action, msg.message);
                }
            }
        });
    }

    /************************************* Bulk Transactions *******************************************/
    $(function () {
        $('#applySelected_btn_360').on("click", function () {
            var action = $('#groupTaskField').val();
            if (action == '0') {
                showMessage('warning', 'Please select an action!!.');
            }
            var idArray = '0';
            $('.bulkCheckbox').each(function () {
                if ($(this).is(":checked")) {
                    idArray += "|" + $(this).attr('bulkId');
                }
            });
            checkIfAnyCheckBoxChecked();
            if (idArray != '0') {

                switch (action) {
                    case "toggleStatus":
                        $.ajax({
                            type: "POST",
                            url: three60(),
                            data: "action=bulkToggleStatus&idArray=" + idArray,
                            success: function (msg) {
                                var myMessage = idArray.split("|");
                                var counter = myMessage.length;
                                for (i = 1; i < counter; i++) {
                                    var status = $('#toggleImg' + myMessage[i]).attr('status');
                                    newStatus = (status == 1) ? 0 : 1;
                                    $('#toggleImg' + myMessage[i]).attr({'status': newStatus});
                                    if (status == 1) {
                                        $('#toggleImg' + myMessage[i]).removeClass("bg-green");
                                        $('#toggleImg' + myMessage[i]).addClass("bg-red");
                                        $('#toggleImg' + myMessage[i]).attr("data-original-title", "Click to Publish");
                                    } else {
                                        $('#toggleImg' + myMessage[i]).removeClass("bg-red");
                                        $('#toggleImg' + myMessage[i]).addClass("bg-green");
                                        $('#toggleImg' + myMessage[i]).attr("data-original-title", "Click to Un-publish");
                                    }
                                }
                                showMessage('success', 'Status has been toggled.');
                            }
                        });
                        break;

                    case "delete":
                        $('.MsgTitle').html('Do you want to delete the selected rows?');
                        $('.pText').html('Click on yes button to delete this rows permanently.!!');
                        $('.divMessageBox').fadeIn();
                        $('.MessageBoxContainer').fadeIn(1000);

                        $(".botTempo").on("click", function () {
                            var popAct = $(this).attr("id");
                            if (popAct == 'yes') {
                                deleteSelectedRecords360(idArray);
                            }
                            $('.divMessageBox').fadeOut();
                            $('.MessageBoxContainer').fadeOut(1000);
                        });
                        break;
                } // end switch section
                reStructureList(getTableId());
            } // end if section
        });

        $('#applySelected_btn_hotspot').on("click", function () {
            var action = $('#groupTaskField').val();
            if (action == '0') {
                showMessage('warning', 'Please select an action!!.');
            }
            var idArray = '0';
            $('.bulkCheckbox').each(function () {
                if ($(this).is(":checked")) {
                    idArray += "|" + $(this).attr('bulkId');
                }
            });
            checkIfAnyCheckBoxChecked();
            if (idArray != '0') {

                switch (action) {
                    case "toggleStatus":
                        $.ajax({
                            type: "POST",
                            url: hotspot(),
                            data: "action=bulkToggleStatus&idArray=" + idArray,
                            success: function (msg) {
                                var myMessage = idArray.split("|");
                                var counter = myMessage.length;
                                for (i = 1; i < counter; i++) {
                                    var status = $('#toggleImg' + myMessage[i]).attr('status');
                                    newStatus = (status == 1) ? 0 : 1;
                                    $('#toggleImg' + myMessage[i]).attr({'status': newStatus});
                                    if (status == 1) {
                                        $('#toggleImg' + myMessage[i]).removeClass("bg-green");
                                        $('#toggleImg' + myMessage[i]).addClass("bg-red");
                                        $('#toggleImg' + myMessage[i]).attr("data-original-title", "Click to Publish");
                                    } else {
                                        $('#toggleImg' + myMessage[i]).removeClass("bg-red");
                                        $('#toggleImg' + myMessage[i]).addClass("bg-green");
                                        $('#toggleImg' + myMessage[i]).attr("data-original-title", "Click to Un-publish");
                                    }
                                }
                                showMessage('success', 'Status has been toggled.');
                            }
                        });
                        break;

                    case "delete":
                        $('.MsgTitle').html('Do you want to delete the selected rows?');
                        $('.pText').html('Click on yes button to delete this rows permanently.!!');
                        $('.divMessageBox').fadeIn();
                        $('.MessageBoxContainer').fadeIn(1000);

                        $(".botTempo").on("click", function () {
                            var popAct = $(this).attr("id");
                            if (popAct == 'yes') {
                                deleteSelectedRecordsHotspot(idArray);
                            }
                            $('.divMessageBox').fadeOut();
                            $('.MessageBoxContainer').fadeOut(1000);
                        });
                        break;
                } // end switch section
                reStructureList(getTableId());
            } // end if section
        });
    })


</script>