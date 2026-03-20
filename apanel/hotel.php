<?php require_once('../includes/initialize.php');
global $db;
$sql = "SELECT accesskey FROM tbl_users ORDER BY id ASC LIMIT 1 ";
$query = $db->query($sql);
$rRow = $db->fetch_object($query);
$_curl = ADMIN_URL . 'switch/' . $rRow->accesskey; ?>
<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>Rojai</title></head>
<body style="margin: 0; padding: 0;">
<?php $_token = !empty($_REQUEST['hotel_id']) ? addslashes($_REQUEST['hotel_id']) : '';
$hotel_url = 'https://www.rojai.com/apanel/switch/' . $_token; ?>
<form id="moodleform" target="iframe" method="post" action="<?php echo $hotel_url; ?>"><input type="hidden"
                                                                                              name="m_dashboard"
                                                                                              value="<?php echo $_curl; ?>"/>
</form>
<iframe name="iframe" width="100%" height="100%" style="position: absolute; height: 100%; border: none;"></iframe>
<script type="text/javascript">document.getElementById('moodleform').submit();</script>
</body>
</html>