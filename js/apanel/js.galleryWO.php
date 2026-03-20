<script language="javascript">

function getLocation(){
	return '<?php echo BASE_URL;?>includes/controllers/ajax.gallery.php';
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
    var desiredPage = localStorage.getItem("gallerydbpage") ? parseInt(localStorage.getItem("gallerydbpage")) : 0;
    var desiredLength = localStorage.getItem("gallerydblength") ? parseInt(localStorage.getItem("gallerydblength")) : 10;

    console.log("Desired Page:", desiredPage);
    console.log("Desired Length:", desiredLength);

    // Initialize the DataTable
    var oTable = $('#example').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "iDisplayLength": desiredLength // Set the initial length
    }).rowReordering({
        sURL: "<?php echo BASE_URL;?>includes/controllers/ajax.gallery.php?action=sort",
        fnSuccess: function (message) {
            var msg = jQuery.parseJSON(message);
            showMessage(msg.action, msg.message);
        }
    });

    // Store the current page in local storage when a pagination button is clicked
    $(document).on("click", ".fg-button", function () {
        var currentPage = oTable.fnPagingInfo().iPage;
        localStorage.setItem("gallerydbpage", currentPage);
        console.log("Stored Page:", currentPage);
    });

    // Store the selected length in local storage when the length menu changes
    $(document).on('change', '#example_length select', function () {
        var selectedLength = $(this).val();
        localStorage.setItem("gallerydblength", selectedLength);
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

/*************************** Shorting Sub Image Gallery Postion *******************************/
$(document).ready(function() {
	oTable = $('#sub-example').dataTable({
		"bJQueryUI": true,
		"sPaginationType": "full_numbers"
	}).rowReordering({ 
		  sURL:"<?php echo BASE_URL;?>includes/controllers/ajax.gallery.php?action=subSort",
		  fnSuccess: function(message) { 
					var msg = jQuery.parseJSON(message);
					showMessage(msg.action,msg.message);
			   }
		   });
});

$(document).ready(function(){		
	// form submisstion actions		
	jQuery('#gallery_frm').validationEngine({
		autoHidePrompt:true,
		scroll: false,
		onValidationComplete: function(form, status){
			if(status==true){	
				$('#btn-submit').attr('disabled', 'true');
				var action = ($('#idValue').val() == 0) ? "action=add&" : "action=edit&" ;
				var data = $('#gallery_frm').serialize();
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
						   setTimeout( function(){$('.my-msg').html('');},3000);
						   $('#btn-submit').removeAttr('disabled');						   			   
		 				   $('.formButtons').show();
						   return false
					   }
					   if(msg.action=='success'){
						   showMessage(msg.action,msg.message);							   
						   setTimeout( function(){window.location.href="<?php echo ADMIN_URL?>gallery/list";},3000);
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
/***************************************** View Gallery Lists *******************************************/
	jQuery('#subgallery_frm').validationEngine({
	autoHidePrompt:true,
	scroll: false,
	onValidationComplete: function(form, status){
		if(status==true){	
			$('#btn-submit').attr('disabled', 'true');
			var action = "action=addSubGalleryImage&";
			var data = $('#subgallery_frm').serialize();
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
					   $('#btn-submit').removeAttr('disabled');						   			   
					   $('.formButtons').show();
					   return false
				   }
				   if(msg.action=='success'){
					   showMessage(msg.action,msg.message);	  
					   setTimeout( function(){window.location.href=window.location.href;},3000);
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
	
});

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
	$('.MsgTitle').html('<?php echo sprintf($GLOBALS['basic']['deleteRecord_'],"Gallery")?>');															
	$('.pText').html('Click on yes button to delete this gallery permanently.!!');
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
				 reStructureList(getTableId());
			   }
			});
		}else{Re=null;}
		$('.divMessageBox').fadeOut();
		$('.MessageBoxContainer').fadeOut(1000);
	});	
}

/***************************************** View Gallery Lists *******************************************/
function viewGallerylist()
{
	window.location.href="<?php echo ADMIN_URL?>gallery/list";
}

/***************************************** Add New Gallery *******************************************/
function AddNewGallery()
{
	window.location.href="<?php echo ADMIN_URL?>gallery/addEdit";
}

/***************************************** Edit records *****************************************/
function editRecord(Re)
{
	window.location.href="<?php echo ADMIN_URL?>gallery/addEdit/"+Re;
}



/***************************************** View Articles Lists *******************************************/
function viewGalleryImageslist(Re)
{
	window.location.href="<?php echo ADMIN_URL?>gallery&mode=galleryimagelist/"+Re;
}

/***************************************** Add New Articles *******************************************/
function AddNewGalleryImage(Re)
{
	window.location.href="<?php echo ADMIN_URL?>gallery/addeditgalleryimage/"+Re;
}

/***************************************** Edit Gallery Images records *****************************************/
function editGalleryImageRecord(Re,Reg)
{
	window.location.href="<?php echo ADMIN_URL?>gallery/addeditgalleryimage/"+Re+"/"+Reg;
}

/******************************** Remove temp upload image ********************************/
function deleteTempimage(Re)
{
	$('#previewUserimage'+Re).fadeOut(1000,function(){$('#previewUserimage'+Re).remove();});
}

function viewsubimagelist(Re)
{
	window.location.href="<?php echo ADMIN_URL?>gallery/galleryimagelist/"+Re;
}

/******************************** Remove User saved Sub Gallery images ********************************/
function subrecordDelete(Re)
{
	$('.MsgTitle').html('<?php echo sprintf($GLOBALS['basic']['deleteRecord_'],"image")?>');															
	$('.pText').html('Click on yes button to delete this image permanently.!!');
	$('.divMessageBox').fadeIn();
	$('.MessageBoxContainer').fadeIn(1000);
	
	$(".botTempo").on("click",function(){						
		var popAct=$(this).attr("id");						
		if(popAct=='yes'){
			$.ajax({
			   type: "POST",
			   dataType:"JSON",
			   url:  getLocation(),
			   data: 'action=deleteSubimage&id='+Re,
			   success: function(data){
				 var msg = eval(data);  
				 if(msg.action=='success'){
					 $('#'+Re).remove();
				 	 reStructureList(getTableId());
				 }
			   }
			});
		}else{Re='';}
		$('.divMessageBox').fadeOut();
		$('.MessageBoxContainer').fadeOut(1000);
	});	
}

/******************************** Remove User saved Gallery images ********************************/
function deleteSavedGalleryimage(Re)
{
	$('.MsgTitle').html('<?php echo sprintf($GLOBALS['basic']['deleteRecord_'],"image")?>');															
	$('.pText').html('Click on yes button to delete this image permanently.!!');
	$('.divMessageBox').fadeIn();
	$('.MessageBoxContainer').fadeIn(1000);
	
	$(".botTempo").on("click",function(){						
		var popAct=$(this).attr("id");						
		if(popAct=='yes'){
			$('#removeSavedimg'+Re).fadeOut(1000,function(){$('#removeSavedimg'+Re).remove(); $('.uploader').fadeIn(500);});
		}else{Re='';}
		$('.divMessageBox').fadeOut();
		$('.MessageBoxContainer').fadeOut(1000);
	});	
}

$(function(){
/*************************************** USer Image Status Toggler ******************************************/		
	$('.imageStatusToggle').on('click', function(){
		var Re 		= $(this).attr('rowId');
		var status 	= $(this).attr('status');		
		newStatus = (status == 1) ? 0 : 1;
		$.ajax({
		   type: "POST",
		   url:  getLocation(),
		   data: "action=toggleStatusSubimage&id="+Re,
		   success: function(msg){}
		});
		$(this).attr({'status':newStatus});
		if(status==1){
			$('#toggleImg'+Re).removeClass("icon-check-circle-o");
			$('#toggleImg'+Re).addClass("icon-clock-os-circle-o");
		}else{
			$('#toggleImg'+Re).removeClass("icon-clock-os-circle-o");
			$('#toggleImg'+Re).addClass("icon-check-circle-o");
		}
	});
});
</script>