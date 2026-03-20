<?php
require_once("includes/initialize.php");
$usermail = User::get_UseremailAddress_byId(1);
$ccusermail = User::field_by_id(1, 'optional_email');
$sitename = Config::getField('sitename', true);

$recaptcha_secret = '6Lf5y4YsAAAAADLx7PNcXxF96tHeeRm1sh87joQL';

foreach ($_POST as $key => $val) {
    $$key = $val;
}


if ($_POST['action'] == "forContact"):
    //--------------------------
    function verifyRecaptcha($response, $secret)
    {
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = array(
            'secret' => $secret,
            'response' => $response
        );

        $options = array(
            'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
    $context  = stream_context_create($options);
        $result = @file_get_contents($url, false, $context);

        if ($result === FALSE) {
            return false; // Failed to communicate with Google API
        }

        $json_result = json_decode($result, true);

        // Check the 'success' key from Google's response
    return isset($json_result['success']) && $json_result['success'] == true;}

    // Verify reCAPTCHA first
    $recaptcha_response = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '';
    if (empty($recaptcha_response) || !verifyRecaptcha($recaptcha_response, $recaptcha_secret)) {
        echo json_encode(array("action" => "error", "message" => "reCAPTCHA verification failed. Please try again."));
        exit;
    }

    //-------------------------
    $body = '
        <table width="100%" border="0" cellpadding="0" style="font:12px Arial, serif;color:#222;">
            <tr>
                <td><p>Dear Sir,</p></td>
            </tr>
            <tr>
                <td>
                    <p>
                        <span style="color:#0065B3; font-size:14px; font-weight:bold">
                        Contact message</span><br />
                        The details provided are:
                    </p>
                    <p>
                        <strong>Name</strong> : ' . $name . '<br />		
                        <strong>E-mail Address</strong>: ' . $email . '<br />
                        <strong>Phone</strong>: ' . $phone . '<br />
                        <strong>Message</strong>: ' . $message . '<br />

                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p>Thank you,<br />
                    ' . $name . '
                    </p>
                </td>
            </tr>
        </table>
  ';

    $mail = new PHPMailer();
    $mail->SetFrom($email, $name);
    $mail->AddReplyTo($email, $name);
    $mail->AddAddress($usermail, $sitename);
    if (!empty($ccusermail)) {
        $rec = explode(';', $ccusermail);
        if ($rec) {
            foreach ($rec as $row) {
                $mail->AddCC($row, $sitename);
            }
        }
    }

    $mail->Subject = 'Enquiry Contact mail from ' . $name . '';
    $mail->MsgHTML($body);

    if (!$mail->Send()) {
        echo json_encode(array("action" => "unsuccess", "message" => "We could not sent your message at the time. Please try again later."));
    }
    else {
        echo json_encode(array("action" => "success", "message" => "Your message has been successfully sent."));
    }
endif;




//Career***************************************************************************************
if ($_POST['action'] == "forCareer"):

    $career_title = isset($_POST['career_title']) ? $_POST['career_title'] : '';
    $messageText = isset($_POST['message']) ? $_POST['message'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $vacancy_id = isset($_POST['vacancy_id']) ? intval($_POST['vacancy_id']) : 0;
    $file_name = '';

    // Handle File Upload
    $upload_dir = 'images/career/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_name = basename($_FILES['file']['name']);
        // Add timestamp to avoid filename conflicts
        $file_name = time() . '_' . $file_name;
        $file_path = $upload_dir . $file_name;
        
        if (!move_uploaded_file($file_tmp, $file_path)) {
            $file_name = ''; // Reset if upload fails
        }
    }

    // Save Applicant to Database
    $applicant = new Applicant();
    $applicant->fullname = $name;
    $applicant->email = $email;
    $applicant->mobile = $phone;
    $applicant->phone = $phone;
    $applicant->position = $vacancy_id;
    $applicant->myfile = $file_name;
    $applicant->qualification = $messageText;
    $applicant->sortorder = Applicant::find_maximum('sortorder');

    $save_result = $applicant->save();

    $body = '
        <table width="100%" border="0" cellpadding="0" style="font:12px Arial, serif;color:#222;">
            <tr>
                <td><p>Dear Sir,</p></td>
            </tr>
            <tr>
                <td>
                    <p>
                        <span style="color:#0065B3; font-size:14px; font-weight:bold">Career / Job Application</span><br />
                        An application has been received. The details provided are:
                    </p>
                    <p>
                        <strong>Applied For</strong> : ' . $career_title . '<br />		
                        <strong>Name</strong> : ' . $name . '<br />		
                        <strong>E-mail Address</strong>: ' . $email . '<br />
                        <strong>Phone No.</strong>: ' . $phone . '<br />
                        <strong>Message</strong>: ' . $messageText . '<br />
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p>Thank you,<br />
                    ' . $name . '
                    </p>
                </td>
            </tr>
        </table>
    ';

    $mail = new PHPMailer();
    $mail->SetFrom($email, $name);
    $mail->AddReplyTo($email, $name);
    $mail->AddAddress($usermail, $sitename);

    // Handle File Attachment for Email
    if (!empty($file_name) && file_exists($file_path)) {
        $mail->AddAttachment($file_path, $_FILES['file']['name']);
    }

    if (!empty($ccusermail)) {
        $rec = explode(';', $ccusermail);
        if ($rec) {
            foreach ($rec as $row) {
                $mail->AddCC($row, $sitename);
            }
        }
    }

    $mail->Subject =  'Career / Job Application mail from ' . $name . '';
    $mail->MsgHTML($body);

    if (!$mail->Send()) {
        echo json_encode(array("action" => "unsuccess", "message" => "We could not send your application at this time. Please try again later."));
    } else {
        echo json_encode(array("action" => "success", "message" => "Your application has been successfully submitted."));
    }
endif;
//hall***************************************************************************************************
if ($_POST['action'] == "forHall"):
    $body = '
      <table width="100%" border="0" cellpadding="0" style="font:12px Arial, serif;color:#222;">
          <tr>
              <td><p>Dear Sir,</p></td>
          </tr>
          <tr>
              <td>
                  <p>
                      <span style="color:#0065B3; font-size:14px; font-weight:bold">Online Reservation Inquiry message</span><br />
                      The details provided are:
                  </p>
                  <p>
                      <strong>Event Date</strong> : ' . $event_date . '<br />		
                      <strong>Pax</strong> : ' . $pax . '<br />		
                      <strong>Event Time</strong> : ' . $event_time . '<br />		
  ';
    if (!empty($rooms)) {
        $body .= '<strong>Rooms for the Event?</strong> : ' . $rooms . ' <br />';
    }
    $body .= '
                      <strong>Name</strong> : ' . $name . '<br />		
                      <strong>E-mail Address</strong>: ' . $email . '<br />
                      <strong>Phone</strong>: ' . $phone . '<br />
                  </p>
              </td>
          </tr>
          <tr>
              <td>
                  <p>Thank you,<br />
                  ' . $name . '
                  </p>
              </td>
          </tr>
      </table>
';

    $mail = new PHPMailer();
    $mail->SetFrom($email, $name);
    $mail->AddReplyTo($email, $name);
    $mail->AddAddress($usermail, $sitename);
    if (!empty($ccusermail)) {
        $rec = explode(';', $ccusermail);
        if ($rec) {
            foreach ($rec as $row) {
                $mail->AddCC($row, $sitename);
            }
        }
    }

    $mail->Subject = 'Online Reservation Inquiry mail from ' . $name;
    $mail->MsgHTML($body);

    if (!$mail->Send()) {
        echo json_encode(array("action" => "unsuccess", "message" => "We could not sent your Inquiry at the time. Please try again later."));
    }
    else {
        echo json_encode(array("action" => "success", "message" => "Your Inquiry has been successfully sent."));
    }
endif;


?>