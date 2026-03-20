<script language="javascript">

function getLocation(){
	return '<?php echo BASE_URL;?>includes/controllers/ajax.services.php';
}
function getTableId(){
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
    var desiredPage = localStorage.getItem("servicesdbpage") ? parseInt(localStorage.getItem("servicesdbpage")) : 0;
    var desiredLength = localStorage.getItem("servicesdblength") ? parseInt(localStorage.getItem("servicesdblength")) : 10;

    console.log("Desired Page:", desiredPage);
    console.log("Desired Length:", desiredLength);

    // Initialize the DataTable
    var oTable = $('#example').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "iDisplayLength": desiredLength // Set the initial length
    }).rowReordering({
        sURL: "<?php echo BASE_URL;?>includes/controllers/ajax.services.php?action=sort",
        fnSuccess: function (message) {
            var msg = jQuery.parseJSON(message);
            showMessage(msg.action, msg.message);
        }
    });

    // Store the current page in local storage when a pagination button is clicked
    $(document).on("click", ".fg-button", function () {
        var currentPage = oTable.fnPagingInfo().iPage;
        localStorage.setItem("servicesdbpage", currentPage);
        console.log("Stored Page:", currentPage);
    });

    // Store the selected length in local storage when the length menu changes
    $(document).on('change', '#example_length select', function () {
        var selectedLength = $(this).val();
        localStorage.setItem("servicesdblength", selectedLength);
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

$(document).ready(function(){	
	$('.btn-submit').on('click',function(){
		var actVal = $(this).attr('btn-action');
		$('#idValue').attr('myaction',actVal);
	})

	// form submisstion actions		
	jQuery('#services_frm').validationEngine({		
		prettySelect : true,
		autoHidePrompt:true,
		useSuffix: "_chosen",
		scroll: true,
		onValidationComplete: function(form, status){
			if(status==true){	
				$('.btn-submit').attr('disabled', 'true');
				var action = ($('#idValue').val() == 0) ? "action=add&" : "action=edit&" ;	
				/* By Me */
				for ( instance in CKEDITOR.instances ) 
				CKEDITOR.instances[instance].updateElement();	
				/* End By Me */				
				var data = $('#services_frm').serialize();
				queryString = action+data;
				$.ajax({
				   type: "POST",
				   dataType:"JSON",
				   url:  getLocation(),
				   data: queryString,
				   success: function(data){
					   var msg = eval(data);
					   if(msg.action=='warning'){
						   showMessage(msg.action,msg.message);
						   $('.btn-submit').removeAttr('disabled');						   			   
		 				   $('.formButtons').show();
						   return false
					   }
					   if(msg.action=='success'){
						   showMessage(msg.action,msg.message);	
						   var actionId = $('#idValue').attr('myaction');
						   if(actionId==2)
						   	setTimeout( function(){window.location.href="<?php echo ADMIN_URL?>services/list";},3000);						   	
						   if(actionId==1)	
						   	setTimeout( function(){window.location.href="<?php echo ADMIN_URL?>services/addEdit";},3000);						   	
						   if(actionId==0)
						   	setTimeout( function(){window.location.href="";},3000);	
					   }
					   if(msg.action=='notice'){
						   showMessage(msg.action,msg.message);		   					   
						   setTimeout( function(){window.location.href=window.location.href;},3000);
					   }			   					   
					   if(msg.action=='error'){
						   showMessage(msg.action,msg.message);
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


	$('.user-hotel-select').change(function () {
            let $this = $(this);
            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: getLocation(),
                data: `action=setschoolsId&type_id_service=${$this.val()}`,
                success: function (data) {
                    var msg = eval(data);
                    if (msg.action == 'success') {
                        location.reload();
                    } else {
                        alert('something went wrong')
                    }
                }
            });
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
                                        window.location.href = "<?php echo ADMIN_URL?>services/list";
                                    }, 3000);
                            }
                            if (msg.action == 'notice') {
                                showMessage(msg.action, msg.message);
                                setTimeout(function () {
                                    window.location.href = "<?php echo ADMIN_URL?>services/list";
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
function toggleMetadata(){
	$( ".metadata" ).slideToggle("slow",function(){});
}

// Edit records
function editRecord(Re)
{
	$.ajax({
	   type: "POST",
	   dataType:"JSON",
	   url:  getLocation(),
	   data: 'action=editExistsRecord&id='+Re,
	   success: function(data){
		   var msg = eval(data);
		   $("#title").val(msg.title);
		   $("#idValue").val(msg.editId);		   
	   }
	});
}
		
// Deleting Record
function recordDelete(Re){
	$('.MsgTitle').html('<?php echo sprintf($GLOBALS['basic']['deleteRecord_'],"Academics")?>');															
	$('.pText').html('Click on yes button to delete this academic permanently.!!');
	$('.divMessageBox').fadeIn();
	$('.MessageBoxContainer').fadeIn(1000);
	
	$(".botTempo").on("click",function(){						
		var popAct=$(this).attr("id");						
		if(popAct=='yes'){
			$.ajax({
			   type: "POST",
			   dataType:"JSON",
			   url:  getLocation(),
			   data: 'action=delete&id='+Re,
			   success: function(data){
				 var msg = eval(data);  
				 showMessage(msg.action,msg.message);
				 $('#'+Re).remove();
				 Re='';
				 reStructureList(getTableId());
			   }
			});
		}
		$('.divMessageBox').fadeOut();
		$('.MessageBoxContainer').fadeOut(1000);
	});	
}

/***************************************** Link Type Choose *******************************************/
function linkTypeSelect(Re){
	if(Re == 0) {		
		$('#linkPage_chosen').removeClass("hide");
		($('#linksrc').val() == 'https://www.') ? $('#linksrc').val('') : null ;
	} else {
		$('#linkPage_chosen').addClass("hide");
		($('#linksrc').val() == '') ? $('#linksrc').val("https://www.") : null ;
	}
}
$(document).ready(function(){	
	$('#linkPage').change(function(){
		$('#linksrc').val($(this).val());
	});
});



function bookLinkTypeSelect(Re){
    if(Re == 0) {        
        $('#bookLinkPage_chosen').removeClass("hide").show();
        if($('#booklinksrc').val() == 'https://www.') $('#booklinksrc').val('');
    } else {
        $('#bookLinkPage_chosen').addClass("hide").hide();
        if($('#booklinksrc').val() == '') $('#booklinksrc').val('https://www.');
    }
}

$(document).ready(function(){    
    // Trigger correct state on page load
    var initialType = <?php echo !empty($advInfo->booklinktype) ? $advInfo->booklinktype : 0; ?>;
    bookLinkTypeSelect(initialType);

    // Update input when select changes
    $('#bookLinkPage').change(function(){
        $('#booklinksrc').val($(this).val());
    });
});



function exploreLinkTypeSelect(Re){
    if(Re == 0) {        
        $('#exploreLinkPage_chosen').removeClass("hide").show();
        if($('#explorelinksrc').val() == 'https://www.') $('#explorelinksrc').val('');
    } else {
        $('#exploreLinkPage_chosen').addClass("hide").hide();
        if($('#explorelinksrc').val() == '') $('#explorelinksrc').val('https://www.');
    }
}

$(document).ready(function(){    
    // Trigger correct state on page load
    var initialType = <?php echo !empty($advInfo->explorelinktype) ? $advInfo->explorelinktype : 0; ?>;
    exploreLinkTypeSelect(initialType);

    // Update input when select changes
    $('#exploreLinkPage').change(function(){
        $('#explorelinksrc').val($(this).val());
    });
});



/***************************************** View services Lists *******************************************/
function viewServiceslist()
{
	window.location.href="<?php echo ADMIN_URL?>services/list";
}

/***************************************** Add New services *******************************************/
function AddNewServices()
{
	window.location.href="<?php echo ADMIN_URL?>services/addEdit";
}

/***************************************** Edit records *****************************************/
function editRecord(Re)
{
	window.location.href="<?php echo ADMIN_URL?>services/addEdit/"+Re;
}

/******************************** Remove temp upload image ********************************/
function deleteTempimage(Re)
{
	$('#previewUserimage'+Re).fadeOut(1000,function(){
		$('#previewUserimage'+Re).remove(); 
		$('#preview_Image').html('<input type="hidden" name="imageArrayname" value="" class="">');
	});
}

function deleteTempicon(Re)
{
	$('#previewUsericon'+Re).fadeOut(1000,function(){
		$('#previewUsericon'+Re).remove(); 
		$('#preview_Icon').html('<input type="hidden" name="iconArrayname" value="" class="">');
	});
}
function deleteTempbanner(Re)
{
	$('#previewUserbanner'+Re).fadeOut(1000,function(){
		$('#previewUserbanner'+Re).remove(); 
		$('#preview_Banner').html('<input type="hidden" name="bannerArrayname" value="" class="">');
	});
}

/******************************** Remove saved services image ********************************/
function deleteSavedServicesimage(Re)
{
	$('.MsgTitle').html('Do you want to delete the record ?');															
	$('.pText').html('Clicking yes will be delete this record permanently. !!!');
	$('.divMessageBox').fadeIn();
	$('.MessageBoxContainer').fadeIn(1000);
	
	$(".botTempo").off("click").on("click",function(){						
		var popAct=$(this).attr("id");						
		if(popAct=='yes'){
			// Remove the saved image DOM element AND its hidden input
			var $element = $('#removeSavedimg'+Re);
			$element.find('input[name="imageArrayname[]"]').remove();
			$element.fadeOut(1000,function(){$element.remove();});
		}
		$('.divMessageBox').fadeOut();
		$('.MessageBoxContainer').fadeOut(1000);
	});	
}

function deleteSavedServicesicon(Re)
{
	$('.MsgTitle').html('Do you want to delete the record ?');															
	$('.pText').html('Clicking yes will be delete this record permanently. !!!');
	$('.divMessageBox').fadeIn();
	$('.MessageBoxContainer').fadeIn(1000);
	
	$(".botTempo").off("click").on("click",function(){						
		var popAct=$(this).attr("id");						
		if(popAct=='yes'){
			// Remove the saved image DOM element AND its hidden input
			var $element = $('#removeSavedimg1'+Re);
			$element.find('input[name="iconArrayname[]"]').remove();
			$element.fadeOut(1000,function(){$element.remove();});
		}
		$('.divMessageBox').fadeOut();
		$('.MessageBoxContainer').fadeOut(1000);
	});	
}

function deleteSavedServicesBanner(Re)
{
	$('.MsgTitle').html('Do you want to delete the record ?');															
	$('.pText').html('Clicking yes will be delete this record permanently. !!!');
	$('.divMessageBox').fadeIn();
	$('.MessageBoxContainer').fadeIn(1000);
	
	$(".botTempo").off("click").on("click",function(){						
		var popAct=$(this).attr("id");						
		if(popAct=='yes'){
			// Remove the saved image DOM element AND its hidden input
			var $element = $('#removeSavedimg2'+Re);
			$element.find('input[name="bannerArrayname[]"]').remove();
			$element.fadeOut(1000,function(){$element.remove();});
		}
		$('.divMessageBox').fadeOut();
		$('.MessageBoxContainer').fadeOut(1000);
	});	
}

// Delete temporary uploaded banner images
function deleteTempbanner(Re)
{
	$('#previewUserbanner'+Re).fadeOut(1000,function(){
		$('#previewUserbanner'+Re).remove();
	});
}

//brief

$(document).ready(function(){
	$(".character-brief").keyup(function(){
		var a=250,b=$(this).val().length;
		if(b>=a)$(".brief-remaining").text(" you have reached the limit");
		else{
			var c=a-b;$(".brief-remaining").text(c+" characters left")
		}
	});
});



//******************************************************************** */

// New slug
$(document).on('blur', 'input[name="title"], input[name="slug"]', function() {
	var title = $(this).val();
	var actid = $('#idValue').val();
	$.ajax({
		url: getLocation(),
		type: 'POST',
		dataType: 'json',
		data: {'action': 'slug', 'title':title, 'actid':actid},
	})
	.done(function(data) {
		var msg = eval(data);
		$('input[name="slug"]').val(msg.result);
		$('span#error').html(msg.msgs);
	});
	return false;
});





//***************************************************************** */
/***************************************** View Services Image List *******************************************/
function viewServiceImages(Re) {
    window.location.href = "<?php echo ADMIN_URL?>services/servicesImageList/" + Re;
}

/******************************** Remove temp upload services sub image ********************************/
function deleteTempServicesimage(Re) {
    $('#previewUserimage' + Re).fadeOut(1000, function () {
        $('#previewUserimage' + Re).remove();
    });
}

/******************************** Remove saved services sub images ********************************/
function deleteSavedServicesSubimage(Re) {
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
                url: getLocation(),
                data: 'action=deleteServicesSubimage&id=' + Re,
                success: function (data) {
                    var msg = eval(data);
                    if (msg.action == 'success') {
                        $('.removeSavedimg' + Re).fadeOut(1000, function () {
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

/******************************** Edit Services Image Title ********************************/
function editServicesImageTitle(Re) {
    var curTitle = $('.clicked' + Re).text();
    var content = '<input type="text" id="uptitle' + Re + '" name="ne-title" class="validate[required,length[0,250]] col-md-9" value="' + curTitle + '" imgid="' + Re + '">';
    content += ' <button type="button" class="col-md-3 updateServicesImageTitle" rowId="' + Re + '">Save</button>';
    $('.clicked' + Re).html(content);
}

/******************************** Update Services Image Title ********************************/
$(document).on("click", ".updateServicesImageTitle", function () {
    var getId = $(this).attr('rowId');
    var getVal = $('#uptitle' + getId).val();
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: getLocation(),
        data: 'action=updateServicesImageTitle&id=' + getId + '&title=' + getVal,
        success: function (data) {
            var msg = eval(data);
            $('.clicked' + getId).html(msg.title);
        }
    });
});

/******************************** Toggle Services Image Status ********************************/
$(document).on("click", ".servicesImageStatusToggle", function () {
    var getId = $(this).attr('rowId');
    var getStatus = $(this).attr('status');
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: getLocation(),
        data: 'action=toggleServicesImageStatus&id=' + getId + '&status=' + getStatus,
        success: function (data) {
            var msg = eval(data);
            if (msg.status == 1) {
                $('#toggleImg' + getId).removeClass('icon-clock-os-circle-o').addClass('icon-check-circle-o');
            } else {
                $('#toggleImg' + getId).removeClass('icon-check-circle-o').addClass('icon-clock-os-circle-o');
            }
            $('.servicesImageStatusToggle[rowId="' + getId + '"]').attr('status', msg.status);
        }
    });
});

/*************************** Sorting Sub Image Services Position *******************************/
$(document).ready(function () {
    $(function () {
        $(".subImageservices-sort").sortable({
            start: function (event, ui) {
                var start_pos = ui.item.index();
                ui.item.data('start_pos', start_pos);
            },
            update: function (event, ui) {
                var mySel = "";
                $('div.oldsort').each(function (i) {
                    mySel = mySel + ';' + $(this).attr('csort');
                });
                var id = ui.item.context.id;
                var end_pos = ui.item.index();
                $.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: getLocation(),
                    data: 'action=sortServicesImages&sortIds=' + mySel,
                    success: function (data) {
                        var msg = eval(data);
                        showMessage(msg.action, msg.message);
                    }
                });
            }
        });
    });
});

/*************************** Services Sub Image Form Submit *******************************/
$(document).ready(function () {
    jQuery('#subservices_frm').validationEngine({
        autoHidePrompt: true,
        scroll: false,
        onValidationComplete: function(form, status) {
            if (status == true) {
                $('#btn-submit').attr('disabled', 'true');
                var action = "action=addServicesImage&";
                var data = $('#subservices_frm').serialize();
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
                            }, 1000);
                        }
                        if (msg.action == 'notice') {
                            showMessage(msg.action, msg.message);
                            setTimeout(function() {
                                window.location.href = window.location.href;
                            }, 1000);
                        }
                        if (msg.action == 'error') {
                            showMessage(msg.action, msg.message);
                            $('#btn-submit').removeAttr('disabled');
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
</script>