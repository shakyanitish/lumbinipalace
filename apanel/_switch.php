<?php require_once("../includes/initialize.php");
$accesskey  =  !empty($_GET['_token']) ? $_GET['_token'] : '';
$row_user  =  User::get_access_by($accesskey);
if(!empty($row_user)) {
	$session->set('u_group',$row_user->group_id);
    $session->set('u_id',$row_user->id);
    $session->set('acc_ip',$_SERVER['REMOTE_ADDR']);
    $session->set('acc_agent',$_SERVER['HTTP_USER_AGENT']);
    $session->set('loginUser',$row_user->first_name.' '.$row_user->middle_name.' '.$row_user->last_name);
    $session->set('accesskey',$row_user->accesskey);
    redirect_to(BASE_URL.'apanel/dashboard');
}