<?php
require_once("includes/initialize.php");

if($_POST['action']=="forbooking"):
	$usermail = User::get_UseremailAddress_byId(1);
	$ccusermail = User::field_by_id(1,'optional_email');
	$sitename = Config::getField('sitename',true);

	foreach($_POST as $key=>$val){$$key=$val;}
	$body = '
	<table width="100%" border="0" cellpadding="0" style="font:12px Arial, serif;color:#222;">
	  <tr>
		<td><p>Dear Sir,</p>
		</td>
	  </tr>
	  <tr>
		<td><p><span style="color:#0065B3; font-size:14px; font-weight:bold">Hall Enquiry</span><br />
		  The details provided are:</p>
		  <p><strong>Name</strong> : '.$name1.'<br />		
		  <strong>Organization</strong>: '.$organization.'<br />
		  <strong>Address</strong>: '.$add1.'<br />
		  <strong>Phone Number</strong>: '.$phone1.'<br />
		  <strong>Email Address</strong>: '.$email1.'<br />
		  <strong>Conference Title</strong>: '.$ctitle.'<br />
		  <strong>Day</strong>: '.$day.'<br />
		  <strong>Month</strong>: '.$month.'<br />
		  <strong>Year</strong>: '.$year.'<br />
		  <strong>Start Time</strong>: '.$start.'<br />
		  <strong>End Time</strong>: '.$end.'<br />
		  <strong>No. of pax</strong>: '.$pax.'<br />
		  <strong>Comment</strong>: '.$msg.'<br />
		  </p>
		</td>
	  </tr>
	  <tr>
		<td><p>&nbsp;</p>
		<p>Thank you,<br />
		'.$name1.'
		</p></td>
	  </tr>
	</table>
	';
	
	/*
	* mail info
	*/
	
	$mail = new PHPMailer(); // defaults to using php "mail()"
	
	$mail->SetFrom($email1, $name1);
	$mail->AddReplyTo($email1,$name1);
	
	$mail->AddAddress($usermail, $sitename);
	// if add extra email address on back end
	if(!empty($ccusermail)){
		$rec = explode(';', $ccusermail);
		if($rec){
			foreach($rec as $row){
				$mail->AddCC($row,$sitename);
			}		
		}
	}
	
	$mail->Subject    = 'Enquiry mail from '.$name1;
	
	$mail->MsgHTML($body);
	
	if(!$mail->Send()) {
		echo json_encode(array("action"=>"unsuccess","message"=>"We could not sent your request at the time. Please try again later."));
	}else{
		echo json_encode(array("action"=>"success","message"=>"Your request has been successfully received, You will be shortly informed through mail with you verified by admin."));
	}
endif;
?>