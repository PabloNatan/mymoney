<?php
	require_once 'config.php';
	$user = new User();

	print_r($_POST);

	if($user->login($_POST['user_login'], $_POST['user_password']))
	{
		header('Location: home.php');
	}
?>