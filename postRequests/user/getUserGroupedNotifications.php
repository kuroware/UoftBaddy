<?php
header("Content-Type: application/json"); //Set header for outputing the JSON information
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/autoload.php';
$user = User::get_current_user();
try {
	if ($user instanceof CurrentUser) {
		$notifications = $user->get_notifications();
		foreach ($notifications as $key=>$val) {
			
		}
		http_response_code(200);
		echo json_encode($notifications, JSON_PRETTY_PRINT);	
	}
	else {
		throw new UnexpectedValueException('UnexpectedValueException occured on request, no user is logged in');
	}
}
catch (Exception $e) {
	http_response_code(400);
	Database::print_exception($e);
}