<?php
	namespace App;
	use App\Controller\UserController;

	if(isset($_SESSION['user']))
	{
		header('Location: login');
	}

	if(isset($_POST['email']) && !empty($_POST['email'])
		&& isset($_POST['username']) && !empty($_POST['username'])
		&& isset($_POST['password']) && !empty($_POST['password'])
		&& isset($_POST['password_repeat']) && !empty($_POST['password_repeat'])
		&& $_POST['password'] === $_POST['password_repeat'])
	{
		$newuser = new User([
			"email" => $_POST['email'],
			"username" => $_POST['username'],
			"password" => $_POST['password'],
			"passowrd_repeat" => $_POST['password_repeat']
		]);
		include "pdo.php";
		$user = new UserController($db);
		$user->registerUser($newuser);
		var_dump($_POST);
	}
?>
<style>
	body {
		background-image: url('https://images.pexels.com/photos/325185/pexels-photo-325185.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260');
		background-color: #f5f6f8;
		color: #333;
	}
</style>
<div class="container mt-5 text-center">
	<div class="row">
		<div class="col-md-12 col-xs-12 mx-auto">
			<img width="200" src="Assets/img/logo.png" alt="Logo MyLiinks">
			<h2>Register now and boost your network !</h2>
		</div>
	</div>
</div>
<div class="container mt-5">
	<div class="row">
		<div style="padding: 50px;" class="col-md-5 col-xs-12 bg-white mx-auto">
			<form method="post">
				<div class="form-floating mb-3">
					<input type="email" name="email" class="form-control" id="email" placeholder="name@example.com" required>
					<label for="email">Email</label>
					<span class="field-icon-check email-field-icon-check"></span>
					<small class="email"></small>
				</div>
				<div class="form-floating mb-3">
					<input type="text" name="username" class="form-control" id="username" placeholder="test" required>
					<label for="username">Username</label>
					<span class="field-icon-check username-field-icon-check"></span>
					<small class="username"></small>
				</div>
				<div class="form-floating mb-3">
					<input type="password" name="password" class="form-control" id="password" toggle="#password" placeholder="Password" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"  required>
					<label for="password">Password</label>
					<span style="cursor: pointer;" toggle="#password" class="bi bi-eye-slash-fill field-icon toggle-password"></span>
					<span class="field-icon-check psw-field-icon-check"></span>
					<small class="password"></small>
				</div>
				<div class="form-floating mb-3">
					<input type="password" name="password_repeat" class="form-control" id="password_repeat" toggle="#password_repeat" placeholder="Password Repeat" required>
					<label for="password">Password repeat</label>
					<span style="cursor: pointer;" toggle="#password_repeat" class="bi bi-eye-slash-fill field-icon toggle-password"></span>
					<span class="field-icon-check psw-repeat-field-icon-check"></span>
					<small class="password_repeat"></small>
				</div>
				<div class="form-floating mb-3" id="message">
					<p class="title">Password must contain the following :</p>
					<p id="letter" class="invalid"><span id="letteri"></span> A <b>lowercase</b> letter</p>
					<p id="capital" class="invalid"><span id="capitali"></span> A <b>capital (uppercase)</b> letter</p>
					<p id="number" class="invalid"><span id="numberi"></span> A <b>number</b></p>
					<p id="length" class="invalid"><span id="lenghti"></span> Minimum <b>8 characters</b></p>
				</div>
				<div class="form-floating mb-3">
					<button id="submit" style="width: 100%;" type="submit" class="btn btn-success mb-3 b-tn-lg">Register</button>
					<small class="alert-danger submit"></small>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="container text-center">
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<small>By using this service you are agreeing to the terms of service and privacy policy.</small>
		</div>
		<div class="col-md-12 col-xs-12">
			<small>Already have an account ? <a href="login">Log in</a></small>
		</div>
	</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="Assets/js/register_form.js"></script>