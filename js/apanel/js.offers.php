<script language="javascript">

    function getLocation() {
        return '<?php echo BASE_URL;?>includes/controllers/ajax.offers.php';
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
    var desiredPage = localStorage.getItem("offersdbpage") ? parseInt(localStorage.getItem("offersdbpage")) : 0;
    var desiredLength = localStorage.getItem("offersdblength") ? parseInt(localStorage.getItem("offersdblength")) : 10;

    console.log("Desired Page:", desiredPage);
    console.log("Desired Length:", desiredLength);

    // Initialize the DataTable
    var oTable = $('#example').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "iDisplayLength": desiredLength // Set the initial length
    }).rowReordering({
        sURL: "<?php echo BASE_URL;?>includes/controllers/ajax.offers.php?action=sort",
        fnSuccess: function (message) {
            var msg = jQuery.parseJSON(message);
            showMessage(msg.action, msg.message);
        }
    });

    // Store the current page in local storage when a pagination button is clicked
    $(document).on("click", ".fg-button", function () {
        var currentPage = oTable.fnPagingInfo().iPage;
        localStorage.setItem("offersdbpage", currentPage);
        console.log("Stored Page:", currentPage);
    });

    // Store the selected length in local storage when the length menu changes
    $(document).on('change', '#example_length select', function () {
        var selectedLength = $(this).val();
        localStorage.setItem("offersdblength", selectedLength);
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
        $('.booktype').on('click', function () {
            var clkVal = $(this).val();
            if (clkVal == 1) {
                $('.payment-info').slideDown();
            } else {
                $('.payment-info').slideUp();
            }
        })

        $('.btn-submit').on('click', function () {
            var actVal = $(this).attr('btn-action');
            $('#idValue').attr('myaction', actVal);
        })

        // form submisstion actions
        jQuery('#offers_frm').validationEngine({
            prettySelect: true,
            autoHidePrompt: true,
            useSuffix: "_chosen",
            scroll: true,
            onValidationComplete: function (form, status) {
                if (status == true) {
                    $('.btn-submit').attr('disabled', 'true');
                    var action = ($('#idValue').val() == 0) ? "action=add&" : "action=edit&";
                    /* By Me */
                    for (instance in CKEDITOR.instances)
                        CKEDITOR.instances[instance].updateElement();
                    /* End By Me */
                    var data = $('#offers_frm').serialize();
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
                                        window.location.href = "<?php echo ADMIN_URL?>offers/list";
                                    }, 3000);
                                if (actionId == 1)
                                    setTimeout(function () {
                                        window.location.href = "<?php echo ADMIN_URL?>offers/addEdit";
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
                                        window.location.href = "<?php echo ADMIN_URL?>offers/list";
                                    }, 3000);
                            }
                            if (msg.action == 'notice') {
                                showMessage(msg.action, msg.message);
                                setTimeout(function () {
                                    window.location.href = "<?php echo ADMIN_URL?>offers/list";
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
        $('.MsgTitle').html('<?php echo sprintf($GLOBALS['basic']['deleteRecord_'], "Slider image")?>');
        $('.pText').html('Click on yes button to delete this image permanently.!!');
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
                        Re = '';
                        reStructureList(getTableId());
                    }
                });
            }
            $('.divMessageBox').fadeOut();
            $('.MessageBoxContainer').fadeOut(1000);
        });
    }

    /***************************************** Link Type Choose *******************************************/
    function linkTypeSelect(Re) {
        if (Re == 0) {
            $('#linkPage_chosen').removeClass("hide");
            ($('#linksrc').val() == 'http://www.') ? $('#linksrc').val('') : null;
        } else {
            $('#linkPage_chosen').addClass("hide");
            ($('#linksrc').val() == '') ? $('#linksrc').val("http://www.") : null;
        }
    }

    $(document).ready(function () {
        $('#linkPage').change(function () {
            $('#linksrc').val($(this).val());
        });

        $('.addtype').on('click', function () {
            var clkVal = $(this).val();
            console.log(clkVal);
            if (clkVal == 1) {
                $('.dynamic-discount').slideUp();
                $('.static-discount').slideDown();
                $('.multi-discount').slideUp();
                $('.none-discount').slideUp();
            } 
            else if(clkVal == 0) {
                $('.static-discount').slideUp();
                $('.dynamic-discount').slideDown();
                $('.multi-discount').slideUp();
                $('.none-discount').slideUp();
                
            }
            else if(clkVal == 2) {
                $('.static-discount').slideUp();
                $('.multi-discount').slideDown();
                $('.dynamic-discount').slideUp();                                                                                                                                                                                                                                                                  
                $('.none-discount').slideUp();
                
            }
            else if(clkVal == 3) {
                $('.static-discount').slideUp();
                $('.multi-discount').slideUp();
                $('.dynamic-discount').slideUp();                                                                                                                                                                                                                                                                  
                $('.none-discount').slideDown();
                
            }
        })
    });

    /***************************************** View offers Lists *******************************************/
    function viewOfferslist() {
        window.location.href = "<?php echo ADMIN_URL?>offers/list";
    }

    /***************************************** Add New offers *******************************************/
    function AddNewOffers() {
        window.location.href = "<?php echo ADMIN_URL?>offers/addEdit";
    }

    /***************************************** Edit records *****************************************/
    function editRecord(Re) {
        window.location.href = "<?php echo ADMIN_URL?>offers/addEdit/" + Re;
    }
    function toggleMetadata(){
	$( ".metadata" ).slideToggle("slow",function(){});
}

    /******************************** Remove temp upload image ********************************/
    function deleteTempimage(Re) {
        $('#previewUserimage' + Re).fadeOut(1000, function () {
            $('#previewUserimage' + Re).remove();
            $('#preview_Image').html('<input type="hidden" name="imageArrayname" value="" class="">');
        });
    }

    /******************************** Remove saved offers image ********************************/
    function deleteSavedOffersimage(Re) {
        $('.MsgTitle').html('Do you want to delete the record ?');
        $('.pText').html('Clicking yes will be delete this record permanently. !!!');
        $('.divMessageBox').fadeIn();
        $('.MessageBoxContainer').fadeIn(1000);

        $(".botTempo").on("click", function () {
            var popAct = $(this).attr("id");
            if (popAct == 'yes') {
                $('#removeSavedimg' + Re).fadeOut(1000, function () {
                    $('#removeSavedimg' + Re).remove();
                    $('.uploader' + Re).fadeIn(500);
                });
            } else {
                Re = '';
            }
            $('.divMessageBox').fadeOut();
            $('.MessageBoxContainer').fadeOut(1000);
        });
    }

    /***************************************** offer Create Date *******************************************/
    $(document).ready(function(){
	$('#offer_date').datepicker({
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		dateFormat: 'yy-mm-dd',
        minDate: 0
	});
	$('#start_date').datepicker({
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		dateFormat: 'yy-mm-dd'
	});

	$(".character-brief").keyup(function(){
		var a=250,b=$(this).val().length;
		if(b>=a)$(".brief-remaining").text(" you have reached the limit");
		else{
			var c=a-b;$(".brief-remaining").text(c+" characters left")
		}
	});
    });
    /******************************** Remove User saved Package list images ********************************/
    function deleteSavedOfferLimage(Re) {
        $('.MsgTitle').html('<?php echo sprintf($GLOBALS['basic']['deleteRecord_'], "image")?>');
        $('.pText').html('Click on yes button to delete this image permanently.!!');
        $('.divMessageBox').fadeIn();
        $('.MessageBoxContainer').fadeIn(1000);

        $(".botTempo").on("click", function () {
            var popAct = $(this).attr("id");
            if (popAct == 'yes') {
                $('#removeSavedimg1' + Re).fadeOut(1000, function () {
                    $('#removeSavedimg1' + Re).remove();
                    $('.uploader2').fadeIn(500);
                });
            } else {
                Re = '';
            }
            $('.divMessageBox').fadeOut();
            $('.MessageBoxContainer').fadeOut(1000);
        });
    }

    $(document).on('click', 'a.btn-add', function () {
        var rand = Math.random().toString(36).substr(2, 5);
        var newTr = '<tr class="' + rand + '">\
            <td><input class="validate[required]" type="text" name="offer_pax[]"></td>\
            <td width="90"><input class="validate[required,custom[number]]" type="text" name="offer_usd[]"></td>\
            <td width="40">\
                <a href="javascript:;" class="btn bg-blue btn-add">&nbsp;+&nbsp;</a>\
                <a href="javascript:;" class="btn bg-red btn-remove" data-id="' + rand + '">&nbsp;x&nbsp;</a>\
            </td>\
        </tr>';
        $('table.tbl-result').append(newTr);
    });

    $(document).on('click', 'a.multi-btn-add', function () {
        var rand = Math.random().toString(36).substr(2, 5);
        var newTr = '<tr class="' + rand + '">\
            <td><input class="validate[required]" type="text" name="multi_offer_title[]"></td>\
            <td width="90"><input class="validate[required,custom[number]]" type="text" name="multi_offer_npr[]"></td>\
            <td width="40">\
                <a href="javascript:;" class="btn bg-blue multi-btn-add">&nbsp;+&nbsp;</a>\
                <a href="javascript:;" class="btn bg-red multi-btn-remove" multi-data-id="' + rand + '">&nbsp;x&nbsp;</a>\
            </td>\
        </tr>';
        $('table.tbl-result').append(newTr);
    });

    $(document).on('click', 'a.btn-remove', function () {
        var dcode = $(this).attr('data-id');
        $('tr.' + dcode).remove();
    });

    $(document).on('click', 'a.multi-btn-remove', function () {
        var dcode = $(this).attr('multi-data-id');
        $('tr.' + dcode).remove();
    });
</script>