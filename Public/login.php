<?php
namespace App;
use App\Controller\UserController;

# Redirige si déjà connecté
if(isset($_SESSION['user']))
{
	header('Location: admin');
}

if(isset($_POST['email']) && isset($_POST['password']))
{
	include "pdo.php";
	$user = new UserController($db);
	if(!$user->loginUser($_POST['email'], $_POST['password']))
	{
		$alert = $_SESSION['messageLogin'];
	}
}
?>
<style>
	body{
		background-image: url('https://images.pexels.com/photos/571169/pexels-photo-571169.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260');
		background-color: #f5f6f8;
		color: #333;
	}
	/* Cards links mobile */
	@media (max-device-width: 576px)
	{
		.form-switch .form-check-input {
			margin-left: -50px;
		}
	}
</style>
<div class="container text-center mt-5">
	<div class="row">
		<div class="col-md-12 col-xs-12 mx-auto">
			<img width="150" src="Assets/img/logo.png" alt="Logo MyLiinks">
			<p class="mt-3">Sign up for your account</p>
		</div>
	</div>
</div>
<div class="container mt-5">
	<div class="row">
		<div style="padding: 50px;" class="col-md-5 col-xs-12 bg-white mx-auto">
			<?php
			if(isset($alert) && !empty($alert))
			{
				?>
				<div class="container mb-2">
					<div class="row">
						<div class="col">
							<div class="alert alert-danger alert-dismissible fade show" role="alert">
								<?= $_SESSION['messageLogin'] ?>
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
						</div>
					</div>
				</div>
				<?php
			}
			?>
			<form method="post">
				<div class="form-floating mb-3">
					<input type="email" name="email" class="form-control" id="email" placeholder="name@example.com" required>
					<label for="email">Email</label>
					<span class="field-icon-check email-field-icon-check"></span>
					<small class="email"></small>
				</div>
				<div class="form-floating mb-3">
					<input type="password" name="password" class="form-control" id="password" toggle="#password" placeholder="Password" required>
					<label for="password">Password</label>
					<small class="password"></small>
				</div>
				<div class="form-check form-switch mb-3">
					<input class="form-check-input" name="remember" type="checkbox" id="remember">
					<label class="form-check-label" for="remember">Remember me</label>
				</div>
				<div class="form-floating mb-3">
					<button id="submit" style="width: 100%;" type="submit" class="btn btn-success mb-3 b-tn-lg">Log in</button>
					<small class="alert-danger submit"></small>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="container">
	<div class="row">
		<div class="col-md-12 col-xs-12 text-center">
			<small>Don't have an account ? <a href="register">Register now !</a></small>
		</div>
	</div>
</div>