<?php
require_once("includes/initialize.php");

if($_POST['action']=="forcoment"):
	$usermail = User::get_UseremailAddress_byId(1);
	$ccusermail = User::field_by_id(1,'optional_email');
	$sitename = Config::getField('sitename',true);

	foreach($_POST as $key=>$val){$$key=$val;}
	$body = '
	<table width="100%" border="0" cellpadding="0" style="font:12px Arial, serif;color:#222;">
	  <tr>
		<td><p>Dear Sir,</p>
		<p>someone send you booking request</p>
		</td>
	  </tr>
	  <tr>
		<td><p><span style="color:#0065B3; font-size:14px; font-weight:bold">Comment message</span><br />
		  The details provided are:</p>
		  <p><strong>Checkin</strong> : '.$checkin.'<br />		
		  <strong>Checkout</strong>: '.$checkout.'<br />
		  <strong>Guests</strong>: '.$guest.'<br />
		  <strong>Nights</strong>: '.$night.'<br />
		  </p>
		</td>
	  </tr>
	  <tr>
		<td><p>&nbsp;</p>
		<p>Thank you,<br />
		</p></td>
	  </tr>
	</table>
	';
	
	/*
	* mail info
	*/
	
	$mail = new PHPMailer(); // defaults to using php "mail()"
	
	$mail->SetFrom('swarna@longtail.info', $checkin);
	$mail->AddReplyTo('swarna@longtail.info',$checkin);
	
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
	
	$mail->Subject    = 'Enquiry mail from '.$checkin;
	
	$mail->MsgHTML($body);
	
	if(!$mail->Send()) {
		echo json_encode(array("action"=>"unsuccess","message"=>"We could not sent your request at the time. Please try again later."));
	}else{
		echo json_encode(array("action"=>"success","message"=>"Your request has been successfully received, You will be shortly informed through mail with you verified by admin."));
	}
endif;
?>