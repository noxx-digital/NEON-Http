<?php
	use Neon\Http\Message;

	require_once './src/Message.php';

	echo '<pre>';
	var_dump(getallheaders());
	$msg = new Message();
	var_dump($msg->get_headers());
	echo '</pre>';