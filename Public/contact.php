<?php
	namespace App;
	use PHPMailer\PHPMailer\PHPMailer;

	if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['msg-content']))
	{
		if($_POST['name'] != null && $_POST['email'] != null && $_POST['msg-content'] != null)
		{
			if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response']))
			{
				$mailer = new Mailer(true);
				$mailer->sendMail($_POST['email'], 'jerome.bsrpro@gmail.com', 'Nouveau message de contact Myliinks', null, '
					<h1>Nouveau message de contact depuis le formulaire</h1>
					<b>Email :</b> '.$_POST['email'].'<br />
					<b>Tel :</b> '.$_POST['tel'].'<br />
					<b>Nom / Prénom :</b> '.$_POST['name'].'<br />
					<b>Message :</b> '.nl2br($_POST['msg-content']).'<br />
					---------------<br />
					Vous pouvez répondre directement à cet email pour contacter l\'expéditeur.
				');
				header('Location: contact/ok');
			}else {
				$e = 'Veuillez prouvez que vous n\'êtes pas un robot.';
			}
		}else {
			$e = 'Une erreur est survenue. Veuillez vérifier que vous avez bien remplis tous les champs. ';
		}
	}
?>
<style>
	body {
		background-image: url('https://images.pexels.com/photos/821754/pexels-photo-821754.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260');
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
			<h2>Contactez-nous</h2>
		</div>
	</div>
</div>
<div class="container mt-5 mb-3">
	<div class="row">
		<div style="padding: 50px;" class="col-md-5 col-xs-12 bg-white mx-auto">
			<?php
			if(isset($e) && !empty($e))
			{
				?>
				<div class="container mb-2">
					<div class="row">
						<div class="col">
							<div class="alert alert-danger alert-dismissible fade show" role="alert">
								<?= $e ?>
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
					<input type="text" name="name" class="form-control" id="name" placeholder="Nom / Prénom" required>
					<label for="name">Nom / Prénom*</label>
					<span class="field-icon-check email-field-icon-check"></span>
					<small class="email"></small>
				</div>
				<div class="form-floating mb-3">
					<input type="email" name="email" class="form-control" id="email" placeholder="name@example.com" required>
					<label for="email">Email*</label>
					<span class="field-icon-check email-field-icon-check"></span>
					<small class="email"></small>
				</div>
				<div class="form-floating mb-3">
					<input type="tel" name="tel" class="form-control" id="tel" placeholder="Téléphone">
					<label for="tel">Téléphone</label>
					<span class="field-icon-check email-field-icon-check"></span>
					<small class="tel"></small>
				</div>
				<div class="form-floating mb-3">
					<textarea style="height: 200px;" class="form-control" placeholder="Message..." id="msg-content" name="msg-content" required></textarea>
					<label for="msg-content">Message...*</label>
				</div>
				<div class="form-floating mb-3">
					<div class="g-recaptcha" data-sitekey="6LcGIm0aAAAAAGBRGW1RYhJT43j1lhBUD38AGL9J"></div>
				</div>
				<div class="form-floating">
					<button id="submit" style="width: 100%;" type="submit" class="btn btn-success mb-3 b-tn-lg">Envoyer</button>
					<small class="alert-danger submit"></small>
				</div>
			</form>
		</div>
	</div>
</div>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
