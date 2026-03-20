<?php 
$resbpkg='';

if(defined('BOOK_PAGE')) {
	$slug = !empty($_REQUEST['slug'])?addslashes($_REQUEST['slug']):'';
	$sRec = Offers::find_by_slug($slug);

	if(!empty($sRec)) {
		$resbpkg.='
	 	<div class="breadcrumb-area overlay-dark-2 bg-2" style="background-image:url(' . IMAGE_PATH . 'offers/' . $sRec->image . '); background-repeat: no-repeat; ">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="breadcrumb-text text-center">
                            <div class="breadcrumb-bar position-absolute">
                                <!--<ul class="breadrum-list">
                                    <li><a href="' . BASE_URL . 'home">Home</a></li>
                                    <li><a href="' . BASE_URL . 'offer/' . $sRec->slug . '"> ' . $sRec->title . '</a></li>
                                    <li>Book Now</li>
                                </ul>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
	 	<section class="gallery-inner">
	 	    <div class="container">
			    <div class="row">
			        <div class="col-md-12">
			            <h4 class="text=center">' . $sRec->title . '</h4>
			        </div>
			    </div>
			    
			    <div class="alert alert-success" id="msg" style="display:none;"></div>
					<form action="" method="post" id="frm-booking">
					    <div class="row">
							<div class="col-lg-7 col-md-7 col-xs-12">
							    <input type="hidden" name="offer_type" value="'.$sRec->type.'">';
                                if ($sRec->type == 1) {
                                $resbpkg .= '
								<table class="table table-bordered">
									<tr>
										<th>Package</th>
										<th>Price(US$)</th>
										<th>No. of People</th>
										<th>Total Amount</th>
									</tr>

									<tr class="parent">
										<td>
											<a class="text-info" href="' . BASE_URL . 'offer/' . $sRec->slug . '" target="_blank">' . $sRec->title . '</a>
											<input type="hidden" name="package_title[]" value="' . $sRec->title . '">
										</td>
										<td>
											' . $sRec->rate . '
											<input type="hidden" name="package_price[]" value="' . $sRec->rate . '">
											<input type="hidden" name="package_discount[]" value="' . $sRec->discount . '">
										</td>
										<td class="form-group">
											<!--<input type="text" name="no_pax[]" class="form-control"/>-->
											<select name="no_pax[]" class="form-control">
                                              <option value="">Select</option>
                                              ';
                                            for ($i = 1; $i <= $sRec->adults; $i++) {
                                                $resbpkg .= '<option value="' . $i . '">' . $i . '</option>';
                                            }
                                            $resbpkg .= '
                                            </select>
										</td>
										<td class="text-center totalamt">0</td>
									</tr>';
            if (!empty($sRec->discount) and $sRec->discount > 0) {
                $resbpkg .= '
                                    <tr>
										<td colspan="3">Discount (' . $sRec->discount . '%)<br>
										<small>* Discount not applicable for only 1 person</small></td>
										<td class="text-center discountamt">0</td>
									</tr>
                    ';
            }
            $resbpkg .= '
                                    <tr>
										<td colspan="3">Grand Total</td>
										<td class="text-center grand-total">0</td>
									</tr>
								</table>
								';
        }

        if ($sRec->type == 0) {
            $resbpkg .= '
								<table class="table">
									<tr>
										<th class="text-center">Choose</th>
										<th class="text-center">Price(US$)</th>
										<th class="text-center">Number Of People</th>
									</tr>
									';
            $sql = "SELECT * FROM tbl_offer_child WHERE offer_id=$sRec->id";
            $query = $db->query($sql);
            $num = $db->num_rows($query);
            
            if ($num > 0) {
                while ($row = $db->fetch_array($query)) {
                    $resbpkg .= '
                                    <tr class="parent">
										<td class="col-sm-3 text-center">
											<input type="radio" value="' . $row['offer_pax'] . ';;' . $row['offer_usd'] . '" name="radio_type" id="radio_type" style="height:1em;"> 
										</td>
										<td class="col-sm-3 text-center">
											' . $row['offer_usd'] . '
											<input type="hidden" name="package_title[]" value="' . $sRec->title . '">
											<input type="hidden" name="package_price[]" value="' . $row['offer_usd'] . '">
										</td>
										<td class="col-sm-3 text-center">
											<input type="text" name="no_pax[]" class="hidden" value="' . $row['offer_pax'] . '"/>
											' . $row['offer_pax'] . '
										</td>
									</tr>
                    ';
                }
            }
            $resbpkg .= '
								</table>
								';
        }
		if ($sRec->type == 2) {
            $resbpkg .= '
								<table class="table">
									<tr>
										<th class="text-center">Choose</th>
										<th class="text-center">Items</th>
										<th class="text-center">Price of Item</th>
										<th class="text-center">no of pax</th>
										<th class="text-center">total</th>
									</tr>
									';
            $sql = "SELECT * FROM tbl_offer_child WHERE offer_id=$sRec->id";
            $query = $db->query($sql);
            $num = $db->num_rows($query);
            
            if ($num >= 0) {
                while ($row = $db->fetch_array($query)) {
                    $resbpkg .= '
                                    <tr class="parent">
										<td class="col-sm-3 text-center">
											<input type="checkbox" name="multi_item[]" value="' . $row['multi_offer_title'] . '|' . $row['multi_offer_npr'] . '"
											
										</td>
										<td class="col-sm-3 text-center">
											' . $row['multi_offer_title'] . '
										</td>
										<td class="col-sm-3 text-center">
											' . $row['multi_offer_npr'] . '
											<input type="hidden" name="package_title[]" value="' . $sRec->title . '">
											<input type="hidden" name="package_price[]" value="' . $row['multi_offer_npr'] . '">
											<input type="hidden" name="package__item[]" value="' . $row['multi_offer_title'] . '">
										</td>
										<td class="col-sm-3 text-center">
											<input type="number" name="no_pax[]" class="hidden" min="1" value="" disabled/>
										</td>
										<td class="col-sm-3 text-center">
											<div class="row_total"></div>
											<input type="hidden" name="row_hidden[]" class="row_hidden" value=""/>
											</td>
											
											</tr>
											';
										}
									}
									$resbpkg .= '
									<tr>
									<td></td>
									<td></td>
									<td></td>
									<td>Grand Total</td>
									<td class="gtotal">0</td>
									<input type="hidden" class="gtotal" name="multitotal" value=""/>
									</tr>
								</table>
								';
        }
		if ($sRec->type == 3) {
            $resbpkg .= '';
        }

        $resbpkg .= '
							</div>

							<div class="col-lg-5 col-md-5 col-xs-12">
								<div class="row">
    								<div class="form-group col-sm-6">
    						            <input id="person_checkin" name="person_checkin" type="text" placeholder="Check In Date" class="form-control"/>						            
    						        </div>
    						        <div class="clearfix"></div>
    								<div class="form-group col-sm-6">
    						            <input name="person_first" type="text" placeholder="First Name" class="form-control"/>						            
    						        </div>
    						        <div class="form-group col-sm-6">
    						            <input name="person_last" type="text" placeholder="Last Name" class="form-control"/>						            
    						        </div>
    						        <div class="form-group col-sm-6">
    						            <input name="person_contact" type="text" placeholder="Contact No." class="form-control"/>						            
    						        </div>
    						        <div class="form-group col-sm-6">
    						            <input name="person_email" type="text" placeholder="Email Address" class="form-control"/>						            
    						        </div>
    						        <div class="form-group col-sm-6">
    						            <input name="person_address" type="text" placeholder="Address" class="form-control"/>						            
    						        </div>
    						        <div class="form-group col-sm-6">
    						            <select name="person_country" class="form-control">
    						            	<option value="">Choose</option>';
        $contRec = Countries::find_all();
        foreach ($contRec as $contRow) {
            $resbpkg .= '<option value="' . $contRow->country_name . '">' . $contRow->country_name . '</option>';
        }
        $resbpkg .= '</select>					            
    						        </div>
    						        <div class="form-group col-sm-6">
    						            <input name="person_city" type="text" placeholder="City" class="form-control"/>						            
    						        </div>
    						        <div class="form-group col-sm-6">
    						            <input name="person_zpicode" type="text" placeholder="Zip Code" class="form-control"/>						            
    						        </div>
    						        <div class="form-group col-sm-12">
    						            <textarea name="person_message" placeholder="Message" class="form-control"></textarea>
    						        </div>						        
    				                <div class="form-group col-sm-6">
    				        			<img src="' . BASE_URL . 'captcha/imagebuilder.php?rand=310333" border="1"  onclick="updateCaptcha(this);">						
    				        		
    				                    <input placeholder="Enter Security Code" type="text" class="form-control" name="userstring" maxlength="5" />
    				                </div>
    				                <div class="form-group col-sm-12">
    						            <button class="btn btn-primary pay-btn" id="submit" type="submit">Submit</button>
    						        </div>
                                </div>
							</div>
						</div>
					</form>						
	   				</div>
				</div>
			
		</section>';
	}
}

$jVars['module:bookpkg_detail'] = $resbpkg;