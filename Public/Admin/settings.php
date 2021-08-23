<?php

	use App\Controller\UserController;

	if(!isset($_SESSION['user']))
	{
		header('Location: login');
	}

	include "pdo.php";
	include "Ressources/php/menu.php";
	$user = new UserController($db);
	foreach($user->loadUserById() as $k => $v)
	{
		dump($v);
		$pseudo = $v['username'];
		$plan = $v['plan'];
		if(!empty($v['bio']))
		{
			$bio = $v['bio'];
		}else {
			$bio = '';
		}
	}
?>
<link rel="stylesheet" href="../Assets/css/style.css">
<div class="container mt-5 mb-5">
	<div class="col-md-12 col-xs-12">
		<div class="row">
			<h4><i class="bi bi-person-circle"></i> Profil</h4>
			<h6 class="mb-3"><i class="bi bi-arrow-return-right"></i> Gérer mon profil public</h6>
			<form method="post" action="admin/addlink">
				<div class="mt-3 mb-3">
					<label for="formFile" class="form-label">Photo de profil</label>
					<input class="form-control" type="file" id="formFile">
				</div>
				<div class="form-floating mt-3 mb-3">
					<input type="text" name="link_name" class="form-control" id="link_name" value="<?= $pseudo ?>" required>
					<label for="link_name" class="text-dark">Nom d'utilisateur public</label>
					<small>C'est le pseudonyme qui sera affiché sous votre photo de profil.</small>
					<span class="link_name badge bg-danger"></span>
				</div>
				<div class="form-floating mt-3 mb-3">
					<textarea class="form-control" id="floatingTextarea2" style="height: 100px" <?php if($plan == 0) { echo 'disabled'; } ?>>
						<?= $bio ?>
					</textarea>
					<label for="floatingTextarea2">Bio <span class="badge rounded-pill bg-danger text-center">Pro</span></label>
					<small>Ajoutez quelques mots sur votre profil pour vous présenter.</small>
				</div>
				<div class="form-check form-switch mt-3 mb-3">
					<input style="margin-top:0px;margin-right:10px;" class="form-check-input" type="checkbox" name="status" id="formContact">
					<label class="form-check-label" for="formContact">
						<b>Ajouter un bouton de contact sur votre profil <span class="badge rounded-pill bg-danger text-center">Pro</span></b>
					</label>
					<br />
					<small>Un picto tel que <i class="bi bi-envelope"></i> apparaitra en bas de votre profil permettant aux visiteurs d'accéder à un forulaire de contact directement depuis votre profil Myliinks. <b>Votre adresse email ne sera pas visible.</b></small>
				</div>
				<div class="form-check form-switch mt-3 mb-3">
					<input style="margin-top:0px;margin-right:10px;" class="form-check-input" type="checkbox" name="status" id="formContact">
					<label class="form-check-label" for="formContact">
						<b>Ajouter un bouton de contact téléphonique <span class="badge rounded-pill bg-danger text-center">Pro</span></b>
					</label>
					<br />
					<small>Un picto tel que <i class="bi bi-telephone-outbound"></i> apparaitra en bas de votre profil <b>affichant votre numéro de téléphone</b> et permettant aux visiteurs de déclencher un appel directement depuis votre profil Myliinks.</small>
				</div>
			</form>
			<h4 class="mt-5"><i class="bi bi-file-earmark-medical"></i> Abonnement & factures</h4>
			<h6 class="mb-3"><i class="bi bi-arrow-return-right"></i> Gérer mon abonnement et mes factures</h6>
			<form method="POST" action="/create-customer-portal-session">
				<button class="btn btn-purple" type="submit">Consulter <i class="bi bi-box-arrow-up-right"></i></button>
			</form>
			<small>Vous allez être redirigé sur le site de notre partenaire de paiement.</small>
			<h4 class="mt-5"><i class="bi bi-patch-check"></i> Trust Badge</h4>
			<h6 class="mb-3"><i class="bi bi-arrow-return-right"></i> Demander une vérification</h6>
			<small class="mb-3">Un badge vérifié (trust badge) est une coche à côté du nom d’un compte Myliinks (tel que : @Mon_Pseudo <i class="bi bi-patch-check-fill"></i>) qui indique qu’il s’agit du compte authentique d’une personnalité publique, d’une célébrité, d’une marque ou d’une entité qu’il représente. La transmission de votre demande ne garantit pas l’obtention du statut vérifié pour votre compte.</small>
			<form action="asktrustbadge">
				<button class="btn btn-purple" type="submit">Déposer une demande de vérification <i class="bi bi-box-arrow-up-right"></i></button>
			</form>
		</div>
	</div>
</div>

