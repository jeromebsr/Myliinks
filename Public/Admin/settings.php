<?php
	if(!isset($_SESSION['user']))
	{
		header('Location: login');
	}
	use App\Controller\ThemesController;
	use App\Controller\UserController;

	include "pdo.php";
	include "Ressources/php/menu.php";
?>

<form method="POST" action="/create-customer-portal-session">
	<button type="submit">Manage billing</button>
</form>
