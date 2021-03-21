<?php
	use App\Controller\LinksController;
	use App\Controller\ThemesController;
	use App\Controller\UserController;
	use App\Router\Router;
	use http\Client\Request;
	use http\Client\Response;

session_start();
	ob_start();
	include 'pdo.php';
	require 'vendor/autoload.php';
?>
<!doctype html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- Bootstrap CSS -->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
		<link rel="stylesheet" href="Assets/css/style.css">
		<link rel="stylesheet" href="Assets/css/admin.css">
		<link rel="stylesheet" href="Assets/css/themes.css">
		<title>Hello, world!</title>
	</head>
	<body>
		<?php
			$user = new UserController($db);
			$link = new LinksController($db);
			$theme = new ThemesController($db);
			$router = new Router($_GET['url']);

			$router->get('/', function(){ include 'Public/wp/index.php'; });
			$router->get('/register', function(){ include 'Public/register.php'; });
			$router->get('/activate=:username-token=:token', function ($username, $token) use($user) { $user->activateAccount($username, $token); });
			$router->post('/register', function(){ include 'Public/register.php'; });
			$router->get('/login', function(){ include 'Public/login.php'; });
			$router->post('/login', function(){ include 'Public/login.php'; });
			$router->get('/register/ok', function(){ include 'Public/Views/success.html'; });
			$router->get('/contact', function() { include 'Public/contact.php'; });
			$router->post('/contact', function() { include 'Public/contact.php'; });
			$router->get('/contact/ok', function() { include 'Public/Views/contact_success.html'; });
			$router->get('/admin', function(){ include 'Public/Admin/index.php'; });
			$router->post('/admin', function(){ include 'Public/Admin/index.php'; });
			$router->post('/admin/reorder', function() use($link) { $link->reorderLinks(); });
			$router->get('/admin/settings', function(){ include 'Public/Admin/settings.php'; });
			$router->post('/create-customer-portal-session', function() {
				\Stripe\Stripe::setApiKey('sk_test_s84ulO6WuXfGl1M8gDGNFIF800G4OhThSy');

				// Authenticate your user.
				$session = \Stripe\BillingPortal\Session::create([
					'customer' => 'cus_EsBGUrHxYB6eu1',
					'return_url' => 'https://myliinks.com/admin/settings',
				]);

				// Redirect to the customer portal.
				header("Location: " . $session->url);
				exit();
			});

			$router->get('/404', function() { include 'Public/Views/404.html'; });
			$router->get('/admin/404', function() { include 'Public/Views/404.html'; });
			$router->post('/admin/addlink', function() use($link) { $link->addLink(); });
			$router->post('/admin/editlinkname', function() use($link) { $link->editLinkName($_POST['hidden_id']); });
			$router->post('/admin/editlinkurl', function() use($link) { $link->editLinkUrl($_POST['hidden_id']); });
			$router->post('/admin/deletelink', function() use($link) { $link->deleteLink($_POST['hidden_id']); });
			$router->post('/admin/editstatus', function() use($link) { $link->editLinkStatus($_POST['hidden_id'], $_POST['status']); });
			$router->post('/admin/edittheme', function() use($theme) { $theme->editTheme($_POST['theme_id'], $_POST['btn-type']); });
			$router->get('/admin/logout', function() use($user) { $user->logout(); });
			$router->get('/admin/themes', function(){ include 'Public/Admin/themes.php'; });
			$router->post('/admin/webhook', function(){ include 'Public/Admin/webhook.php'; });
			$router->get('/admin/webhook', function(){ include 'Public/Admin/webhook.php'; });
			$router->post('/admin/editoption', function() use($user) { $user->updateOptionPictoOnLink($_POST['picto_option']); });
			$router->get('/admin/pro', function(){ include 'Public/Admin/pro.php'; });
			$router->get('/admin/checkout', function(){ include 'Public/Admin/stripe/index.php'; });
			$router->get('/success', function () { include 'Public/Views/stripe/success.html'; });
			$router->get('/cancel', function () { echo '<h1>CANCEL</h1>'; });
			$router->get('/'.$_GET['url'], function()  { include 'Public/profil.php'; })->with('profil', '[a-z0-9]');
			$router->run();
		?>
		<!-- Bootstrap Bundle with Popper -->
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
	</body>
</html>
