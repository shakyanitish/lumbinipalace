<?php
require_once("includes/initialize.php");

if($_POST['action']=="forbooking"):
	$usermail = User::get_UseremailAddress_byId(1);
	$ccusermail = User::field_by_id(1,'optional_email');
	$sitename = Config::getField('sitename',true);

	foreach($_POST as $key=>$val){$$key=$val;}

	$newArr=array();
	foreach($roomprice as $k=>$v)
	{
		foreach($roomprice[$k] as $rk=>$rv)
		{
			$newArr[] = array('roomname'=>$k, 'ppqnty'=>$ppqnty[$k][$rk], 'roomprice'=>$rv, 'roomqnty'=>$roomqnty[$k][$rk], 'roombprice'=>$roombprice[$k][$rk], 'roompln'=>$roomplan[$k][$rk], 'extrabed'=>$extrabed[$k][$rk], 'extrabedrate'=>$extrabedrate[$k][$rk]);
		}
	}

	$body = '
	<table width="100%" border="0" cellpadding="0" style="font:12px Arial, serif;color:#222;">
	  <tr>
		<td><p>Dear Sir,</p>
		</td>
	  </tr>
	  <tr>
		<td><p><span style="color:#0065B3; font-size:14px; font-weight:bold">Online Inquiry from HA</span><br />
		  The details provided are:</p>
	  		<p>
			    <span style="width:150px; display:inline-block; margin-bottom: 4px;"><strong>Full Name</strong></span> : '.$fullname.'<br />
				<span style="width:150px; display:inline-block; margin-bottom: 4px;"><strong>Email Address</strong></span> : '.$mailaddress.'<br />
				<span style="width:150px; display:inline-block; margin-bottom: 4px;"><strong>Phone</strong></span> : '.$phone.'<br />
				<span style="width:150px; display:inline-block; margin-bottom: 4px;"><strong>Address</strong></span> : '.$address.'<br />
				<span style="width:150px; display:inline-block; margin-bottom: 4px;"><strong>Country</strong></span> : '.$country.'<br />
				<span style="width:150px; display:inline-block; margin-bottom: 4px;"><strong>Check-In Date</strong></span> : '.$checkin.'<br />
				<span style="width:150px; display:inline-block; margin-bottom: 4px;"><strong>Check-Out Date</strong></span> : '.$checkout.'<div style="border-top: 1px solid #E0DFDF; margin:15px 0;"></div>
				<table width="100%">
					<tr style="background:#E0DFDF;">
						<th style="padding: 8px 10px;">S.No.</th>
						<th style="padding: 8px 10px;">Room Type</th>
						<!--<th style="padding: 8px 10px;">Plan Type</th>
						<th style="padding: 8px 10px;">Max</th>
						<th style="padding: 8px 10px;">Price Per Nights</th>-->
					
						<th style="padding: 8px 10px;">Extra Bed</th>
							<th style="padding: 8px 10px;">No. Rooms</th>
					</tr>';
				$sn=1;	
				foreach($newArr as $reck=>$recv)
				{
					if($recv['roomqnty']!='N/A')
					{
						$body.='<tr style="background: #F1F1F1;">
							<td style="padding: 8px 10px; text-align:center;">'.$sn.'</td>
							<td style="padding: 8px 10px; ">'.$recv['roomname'].'</td>
							<td style="padding: 8px 10px; text-align:center;">'.$recv['extrabed'].'</td>
								<td style="padding: 8px 10px;">'.$recv['roomqnty'].'</td>
						<!--<td style="padding: 8px 10px; text-align:center;">'.$recv['ppqnty'].'</td>
						<td style="padding: 8px 10px; text-align:center;">'.(($recv['roompln']=='Without Breakfast')?$recv['roomprice']:$recv['roombprice']).'</td>-->
						
							';
								
							$body.='
						</tr>';	
					}
				$sn++; }
				$body.='</table>
                
				<div style="border-top: 1px solid #E0DFDF; margin:15px 0;"></div>
				
				<span style="display:inline-block; margin-bottom: 4px; color:#222;"><strong>Special Requirements or any Special Packages with Special Offer</strong></span> :<br /> '.set_na($special_offer).'
		  	</p>
		</td>
	  </tr>
	  <tr>
		<td><p>&nbsp;</p>
		<p>Thank you,<br />
		'.$fullname.'
		</p></td>
	  </tr>
	</table>';

	/*
	* mail info
	*/
	
	$mail = new PHPMailer(); // defaults to using php "mail()"
	
	$mail->SetFrom($mailaddress, $fullname);
	$mail->AddReplyTo($mailaddress,$fullname);
	
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
	
	$mail->Subject    = "Room's Reservation ".$sitename;
	
	$mail->MsgHTML($body);

	/**Email to Subscriber**/
	$clientbody = '<table width="100%" border="0" cellpadding="0" style="font:12px Arial, serif;color:#222;">
		  <tr>
			<td><p>Dear '.$fullname.',</p>
			</td>
		  </tr>
		  <tr>
			<td><p><span style="color:#0065B3; font-size:17px; font-weight:bold"> Thank you for booking in '.ucwords($sitename).' </span><br />
			 <p><span style="font-size:14px; font-weight:bold"> We will contact you soon.</p>
			</td>
		  </tr>
		  <tr>
			<td><p>&nbsp;</p>
				<strong><p>Thank you,<br />
				'.$sitename.'
				</p></strong>
			</td>
		  </tr>
		</table>';
		
		/** mail info**/	
		$cmail = new PHPMailer(); // defaults to using php "mail()"	
		$cmail->SetFrom($usermail, $sitename);
		$cmail->AddReplyTo($usermail, $sitename);	
		
		$cmail->AddAddress($mailaddress, $fullname);
		$cmail->Subject    = "Thank You For Booking - ".$sitename;
		$cmail->MsgHTML($clientbody);
	
	if(!$mail->Send()) {
		echo json_encode(array("action"=>"unsuccess","message"=>"We could not sent your request at the time. Please try again later."));
	}else{
		$cmail->Send();
		echo json_encode(array("action"=>"success","message"=>"Your request has been successfully received, You will be shortly informed by admin."));
	}
endif; ?>