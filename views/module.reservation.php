<?php
/*
* Reservation Form
*/

$resresrv='';

if(defined('RESERVATION_PAGE')) { 
	foreach($_POST as $key=>$val) { $$key=$val; }
	$chk_in  = !empty($check_in)?$check_in:date('Y-m-d');
	$chk_out = !empty($check_out)?$check_out: date('Y-m-d', strtotime("+1 day"));
	$resresrv.='<!-- Form | START -->
	<div class="mad-content no-pd">
            <div class="container">
                <div class="mad-section">
                    <div class="mad-entities mad-entities-reverse type-4">
	    <form id="roombooking" name="roombooking" action="" method="post">		 
			<div class="col-sm-8">
				<h4>Room List</h4>';
				$pkgId  = Package::get_accommodationId();
				$subRec = Subpackage::get_relatedpkg($pkgId);
				if($subRec) {
					$resresrv.='<table class="table roomtypes">
						<tr>
							<th>Room Type</th>
							<th>No. of Adults</th>
							<!--<th>Price Per Nights</th>
							<th>Plan Type</th>-->
							<th>No. Rooms</th>
							<th>Extra Bed</th>
						</tr>';

						foreach($subRec as $recRow) {
							$totroom = $recRow->number_room;
							$totppl = $recRow->people_qnty;
							$priceArr = array('1'=>$recRow->onep_price, '2'=>$recRow->twop_price, '3'=>$recRow->threep_price);						
							$bpriceArr = array('1'=>$recRow->oneb_price, '2'=>$recRow->twob_price, '3'=>$recRow->threeb_price);						

							$nos = unserialize($recRow->image);

							$resresrv.='<tr>
								<td  class="table_image" >
									<h4 style="margin-top:0;">'.$recRow->title.'</h4>
									<img src="'.IMAGE_PATH.'subpackage/'.@$nos[0].'" alt="room image" class="img-responsive" width="120px"/>
								</td>
							';

							for($i=1; $i<=$totppl; $i++)
							{
								$resresrv.='
									<td>
										<span class="display_hide">Pax.</span>'.$i.'
										<input type="hidden" name="ppqnty['.$recRow->title.'][]" value="'.$i.'" />
									</td>
									<!--	<span class="display_hide">Without Breakfast :</span>'.$recRow->currency.' '.$priceArr[$i].'-->
										<input type="hidden" name="roomprice['.$recRow->title.'][]" value="'.$recRow->currency.' '.$priceArr[$i].'" />
									<!--	<span class="display_hide">With Breakfast :</span>'.$recRow->currency.' '.$bpriceArr[$i].'-->
										<input type="hidden" name="roombprice['.$recRow->title.'][]" value="'.$recRow->currency.' '.$bpriceArr[$i].'" />

										<!--	<select name="roomplan['.$recRow->title.'][]" class="input-control"> 
											<option value="N/A">Plan Type</option>
											<option value="Without Breakfast">Without Breakfast</option>
											<option value="With Breakfast">With Breakfast</option>
										</select>	-->
								
									<td>
										<select name="roomqnty['.$recRow->title.'][]" class="input-control">
											<option value="N/A">No. Of Room</option>';
											for($r=1; $r<=$totroom; $r++)
											{
												$resresrv.='<option value="'.$r.'">'.$r.'</option>';
											}
										$resresrv.='</select>
									</td>												
									<td>
										<select name="extrabed['.$recRow->title.'][]" class="input-control"> 
											<option value="N/A">Extra Bed</option>
											<option value="Yes">Yes</option>
											<option value="No">No</option>
										</select>
										<!--<p style=" margin-bottom: 0;font-size: 12px;margin-top: 2px;">Extra Price '.$recRow->currency.' '.set_na($recRow->extra_bed).'</p>-->
										<input type="hidden" name="extrabedrate['.$recRow->title.'][]" value="'.$recRow->currency.' '.set_na($recRow->extra_bed).'" />
									</td>				
								</tr>';
							}
						}
					$resresrv.='</table>';
				}

			$resresrv.='</div>

			<div class="col-sm-4 ">
				<div class="row">
					<div class="form-group col-sm-12">
						<h4>Personal Details</h4>
					</div>
					<div class="form-group col-sm-12">
						<input type="text" class="input-control" name="fullname" placeholder="Full Name *" >
					</div>
					<div class="form-group col-sm-12">
						<input type="text" class="input-control" name="mailaddress" placeholder="Email *" >
					</div>
					<div class="form-group col-sm-12">
						<input type="text" class="input-control" name="phone" placeholder="Phone *" >
					</div>
					<div class="form-group col-sm-12">
						<input type="text" class="input-control" name="address" placeholder="Address *" >
					</div>
					<div class="form-group col-sm-12">			
						<select name="country" class="input-control" class="show_fields">
							<option value="">Choose Country *</option>';
							$contRec = Countries::find_all();
							foreach($contRec as $contRow){
								$resresrv.='<option value="'.$contRow->country_name.'">'.$contRow->country_name.'</option>';
							}
						$resresrv.='</select>
					</div>

					<div class="form-group col-sm-12">
						<h6 style="margin:0px;">Reservation Information</h6>
					</div>
					<div class="form-group col-sm-6">
						<input type="text" name="checkin" class="input-control" id="checkin" placeholder="Check-In *" value="'.$chk_in.'">
					</div>
					<div class="form-group col-sm-6">
						<input type="text" name="checkout" class="input-control" id="checkout" placeholder="Check-Out *" value="'.$chk_out.'">
					</div>
					<div class="form-group col-sm-12">
						<textarea name="special_offer" class="input-control" placeholder="Special Requirements or any Special Packages with Special Offer"></textarea>
					</div>
					
					<div class="form-group col-sm-6">
						<img src="'.BASE_URL.'captcha/imagebuilder.php?rand=310333" border="1" onclick="updateCaptcha(this);">				
					</div>
					<div class="form-group col-sm-6">
						<input placeholder="Enter Security Code" type="text" class="input-control" name="userstring" maxlength="5" />
					</div>
					<div class="form-group col-sm-12">
						<input id="btn-booking" name="submit" type="submit" class="btn btn-primary" value="Send">
					</div>

				</div>
			</div>
		</form>
		</div>
		</div>
	</div>
</div>';
}

$jVars['module:reservationform'] = $resresrv;


$reservation_bread='';
if (defined('RESERVATION_PAGE')) {
    $siteRegulars = Config::find_by_id(1);
    $imglink= $siteRegulars->facility_upload ;
    // pr($imglink);
if(!empty($imglink)){
    $img= IMAGE_PATH . 'preference/facility/' . $siteRegulars->facility_upload ;
}
else{
    $img='';
}

    $reservation_bread='<div class="mad-breadcrumb with-bg-img with-overlay" data-bg-image-src="'.$img.'">
    <div class="container wide">
        <h1 class="mad-page-title">Room Reservation</h1>
        <nav class="mad-breadcrumb-path">
            <span><a href="home" class="mad-link">Home</a></span> /
            <span></span>
        </nav>
    </div>
</div>';

}
$jVars['module:reservationbread'] = $reservation_bread;