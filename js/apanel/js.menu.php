<script language="javascript">

function getLocation(){
	return '<?php echo BASE_URL;?>includes/controllers/ajax.menu.php';
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
    var desiredPage = localStorage.getItem("menudbpage") ? parseInt(localStorage.getItem("menudbpage")) : 0;
    var desiredLength = localStorage.getItem("menudblength") ? parseInt(localStorage.getItem("menudblength")) : 10;

    console.log("Desired Page:", desiredPage);
    console.log("Desired Length:", desiredLength);

    // Initialize the DataTable
    var oTable = $('#example').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "iDisplayLength": desiredLength // Set the initial length
    }).rowReordering({
        sURL: "<?php echo BASE_URL;?>includes/controllers/ajax.menu.php?action=sort",
        fnSuccess: function (message) {
            var msg = jQuery.parseJSON(message);
            showMessage(msg.action, msg.message);
        }
    });

    // Store the current page in local storage when a pagination button is clicked
    $(document).on("click", ".fg-button", function () {
        var currentPage = oTable.fnPagingInfo().iPage;
        localStorage.setItem("menudbpage", currentPage);
        console.log("Stored Page:", currentPage);
    });

    // Store the selected length in local storage when the length menu changes
    $(document).on('change', '#example_length select', function () {
        var selectedLength = $(this).val();
        localStorage.setItem("menudblength", selectedLength);
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
	jQuery('#menu_frm').validationEngine({
		prettySelect : true,
		autoHidePrompt:true,
		useSuffix: "_chosen",
		scroll: true,
		onValidationComplete: function(form, status){
			if(status==true){	
				$('.btn-submit').attr('disabled', 'true');
				var action = ($('#idValue').val() == 0) ? "action=add&" : "action=edit&" ;
				var data = $('#menu_frm').serialize();
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
						   	setTimeout( function(){window.location.href="<?php echo ADMIN_URL?>menu/list";},3000);						   	
						   if(actionId==1)	
						   	setTimeout( function(){window.location.href="<?php echo ADMIN_URL?>menu/addEdit";},3000);						   	
						   if(actionId==0)
						   	setTimeout( function(){window.location.href="";},3000);
					   }
					   if(msg.action=='notice'){
					  	 showMessage(msg.action,msg.message);		   					   
					   	 setTimeout( function(){window.location.href="<?php echo ADMIN_URL?>menu/list";},3000);
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

	//Parent onchange Event
	$('#parentOf').on('change',function(){
		var selVal = $("select option:selected").val();
		(selVal==0)?$('.menu-position').slideDown():$('.menu-position').slideUp();
	})
});

/***************************************** AddEdit New Menu *******************************************/
function AddNewMenu()
{
	window.location.href="<?php echo ADMIN_URL?>menu/addEdit";
} 

/***************************************** View Person List *******************************************/
function viewMenulist()
{
	window.location.href="<?php echo ADMIN_URL?>menu/list";
}

/***************************************** view person details *******************************************/
function viewMenuDetails(Re)
{
	window.location.href="<?php echo ADMIN_URL?>menu/view/"+Re;
}

/***************************************** Edit records *******************************************/
function editRecord(Re)
{
	window.location.href="<?php echo ADMIN_URL?>menu/addEdit/"+Re;
}

/*$(function(){
	$('#personsetting_frm')[0].reset();
	$('#btn-submit').removeAttr('disabled');	
});*/

/***************************************** Link Type Choose *******************************************/
function linkTypeSelect(Re){
	if(Re == 0) {		
		$('#linkPage_chosen').removeClass("hide");
		($('#linksrc').val() == 'http://www.') ? $('#linksrc').val('') : null ;
	} else {
		$('#linkPage_chosen').addClass("hide");
		($('#linksrc').val() == '') ? $('#linksrc').val("http://www.") : null ;
	}
}
$(document).ready(function(){	
	$('#linkPage').change(function(){
		$('#linksrc').val($(this).val());
	});
});


//preview logo


    function deleteTemplogo(Re) {
        $('#previewUserlogo' + Re).fadeOut(1000, function() {
            $('#previewUserlogo' + Re).remove();
        });
    }


function deleteSavedMenulogo(Re) {
    $('.MsgTitle').html('Do you want to delete the record ?');
    $('.pText').html('Clicking yes will delete this record permanently.');
    $('.divMessageBox').fadeIn();
    $('.MessageBoxContainer').fadeIn(1000);

    $(".botTempo").off("click").on("click", function() {
        var popAct = $(this).attr("id");

        if (popAct == 'yes') {
            $('#removeSavedlogo' + Re).fadeOut(1000, function() {
                $('#removeSavedlogo' + Re).remove();

                // IMPORTANT FIX
                $('.uploader_logo').removeClass('hide').fadeIn(500);

                $('#preview_logo').html(""); // optional clear preview
            });
        }

        $('.divMessageBox').fadeOut();
        $('.MessageBoxContainer').fadeOut(1000);
    });
}


		
/***************************************** Delete Records *******************************************/
function recordDelete(Re,Relvl){
	var level = Relvl;
	
	$('.MsgTitle').html('<?php echo sprintf($GLOBALS['basic']['deleteRecord_'],"menu")?>');															
	$('.pText').html('Clicking yes will be delete this record permanently. !!!');
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
				  if(level!=0){
						if(level==1){
							$('.submenu1, .submenu2, .submenu3, .submenu4').fadeOut(500);
						}else if(level==2){
							$('.submenu2, .submenu3, .submenu4').fadeOut(500);
						}else if(level==3){
							$('.submenu3, .submenu4').fadeOut(500);
						}else if(level==4){
							$('.submenu4').fadeOut(500);
						}		
					} 
									   
				 var msg = eval(data);  
				 showMessage(msg.action,msg.message);
				 $('#'+Re).remove();
				 reStructureList(getTableId());
			   }
			});
		}else{ Re = null;}
		$('.divMessageBox').fadeOut();
		$('.MessageBoxContainer').fadeOut(1000);
	});	
}

/***************************************** Level wise sub menu *******************************************/
function displaySubMenu(Re,Rename,Ind){		
	var Indx = (Ind==null)?1:Ind;
	$('.submenu'+Indx).html('<img src="<?php echo BASE_URL;?>images/apanel/load.gif">');
	$('.submenu'+Indx).slideDown(500, function(){
		$.ajax({
		   type: "POST",
		   dataType:"JSON",
		   url:  "<?php echo ADMIN_URL?>menu/submenu.php",
		   data: 'action=submenu&parentOf='+Re+'&Rname='+Rename+'&Indx='+Indx,
		   success: function(data){
			   if(Indx!=0){
					if(Indx==1){
						$('.submenu2, .submenu3, .submenu4').fadeOut(500);
					}else if(Indx==2){
						$('.submenu3, .submenu4').fadeOut(500);
					}else if(Indx==3){
						$('.submenu4').fadeOut(500);
					}	
				} 
			   
				var msg = eval(data);
				$('.submenu'+Indx).html(msg.submenu);
		   }
		});
		return false;
	});
}
</script>