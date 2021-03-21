<?php

use App\Router\Router;

if(isset($_SESSION['user']))
{
	include "links.php";
} else {
	header('Location: login');
}