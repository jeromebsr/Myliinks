<?php
	if(!isset($_SESSION['user']))
	{
		header('Location: /login');
	}
	use App\Controller\ThemesController;
	use App\Controller\UserController;

	include "pdo.php";
	$user = new UserController($db);
	$themes = new ThemesController($db);
	include "Ressources/php/menu.php";
?>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css">
<link rel="stylesheet" href="../Assets/css/style.css">
<link rel="stylesheet" href="../Assets/css/admin.css">
<link rel="stylesheet" href="../Assets/css/themes.css">
<style>
	@import url('https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&display=swap');
	*{
		margin: 0;
		padding: 0;
		box-sizing: border-box;
	}
	body{
		font-family: 'Open Sans', sans-serif;
		font-size: 15px;
		line-height: 1.5;
		font-weight: 400;
		background: #f0f3f6;
		color: #3a3a3a;
	}
	hr {
		margin: 20px 0;
		border: none;
		border-bottom: 1px solid #d9d9d9;
	}
	label, input{
		cursor: pointer;
	}
	h2,h3,h4,h5{
		font-weight: 600;
		line-height: 1.3;
		color: #1f2949;
	}
	h2{
		font-size: 24px;
	}
	h3 {
		font-size: 18px;
	}
	h4 {
		font-size: 14px;
	}
	h5 {
		font-size: 12px;
		font-weight: 400;
	}
	img{
		max-width: 100%;
		display: block;
		vertical-align: middle;
	}
	.container {
		max-width: 99vw;
		margin: 15px auto;
		padding: 0 15px;
	}

	.top-text-wrapper {
		margin: 20px 0 30px 0;
	}
	.top-text-wrapper h4{
		font-size: 24px;
		margin-bottom: 10px;
	}
	.top-text-wrapper code{
		font-size: .85em;
		background: linear-gradient(90deg,#fce3ec,#ffe8cc);
		color: #ff2200;
		padding: .1rem .3rem .2rem;
		border-radius: .2rem;
	}
	.tab-section-wrapper{
		padding: 30px 0;
	}

	.grid-wrapper {
		display: inline-grid;
		grid-gap: 30px;
		place-items: center;
		place-content: center;
		margin-bottom: 30px;
	}
	.grid-col-auto{
		grid-template-columns: repeat(auto-fill, minmax(280px, .1fr));
		grid-template-rows: auto;
	}
	.grid-col-1{
		grid-template-columns: repeat(1, auto);
		grid-template-rows: repeat(1, auto);
	}
	.grid-col-2{
		grid-template-columns: repeat(2, auto);
		grid-template-rows: repeat(1, auto);
	}
	.grid-col-3{
		grid-template-columns: repeat(3, auto);
		grid-template-rows: repeat(1, auto);
	}
	.grid-col-4{
		grid-template-columns: repeat(4, auto);
		grid-template-rows: repeat(1, auto);
	}


	/* ******************* Selection Radio Item */

	.selected-content{
		text-align: center;
		border-radius: 3px;
		box-shadow: 0 2px 4px 0 rgba(219, 215, 215, 0);
		border: solid 2px transparent;
		background: #FBFBFD;
		max-width: 280px;
		height: 330px;
		padding: 15px;
		display: grid;
		grid-gap: 15px;
		place-content: center;
		transition: .3s ease-in-out all;
	}

	.selected-content img {
		width: 230px;
		height: 172px !important;
		border: none !important;
		margin: 0 auto;
	}
	.selected-content h4 {
		font-size: 16px;
		letter-spacing: -0.24px;
		text-align: center;
		color: #1f2949;
	}
	.selected-content h5 {
		font-size: 14px;
		line-height: 1.4;
		text-align: center;
		color: #686d73;
	}

	.selected-label{
		position: relative;
	}
	.selected-label input{
		display: none;
	}
	.selected-label .icon{
		width: 20px;
		height: 20px;
		border: solid 2px #e3e3e3;
		border-radius: 50%;
		position: absolute;
		top: 15px;
		left: 15px;
		transition: .3s ease-in-out all;
		transform: scale(1);
		z-index: 1;
	}
	.selected-label .icon:before{
		content: "\f00c";
		position: absolute;
		width: 100%;
		height: 100%;
		font-family: "Font Awesome 5 Free";
		font-weight: 900;
		font-size: 12px;
		color: #000;
		text-align: center;
		opacity: 0;
		transition: .2s ease-in-out all;
		transform: scale(2);
	}
	.selected-label input:checked + .icon{
		background: #3057d5;
		border-color: #3057d5;
		transform: scale(1.2);
	}
	.selected-label input:checked + .icon:before{
		color: #fff;
		opacity: 1;
		transform: scale(.8);
	}
	.selected-label input:checked ~ .selected-content{
		box-shadow: 0 2px 4px 0 rgba(219, 215, 215, 0.5);
		border: solid 2px #3057d5;
	}

	.accordion-body {
		padding-left: 2.80rem !important;
		padding-right: 0rem !important;
	}
</style>

<div class="container mt-5 padding-mobile-fixed">
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<div class="container">
				<div class="top-text-wrapper">
					<h4>Choose your theme 👻</h4>
					<p>Which <code>Theme</code> will you choose ? Rather <code>Colorful</code> ? Rather <code>Dark</code> ? Pick one and customize it !</p>
					<a class="btn btn-purple" target="_blank" href="https://myliinks.com/<?= $user->getSlug() ?>">Voir mon profil</a>
					<hr>
				</div>
				<div class="col-md-12 col-xs-12 mb-5">
					<h4>Options</h4>
					<div class="form-check form-switch">
						<form method="post" action="editoption">
							<?php
								if($user->getOptionPictoOnLink() == 1)
								{
									$option = "on";
								}else {
									$option = "off";
								}
							?>
							<input <?php if($user->getUserPlan() == 0) { echo 'disabled="disabled"'; } ?> id="picto_option" onchange="submit();" class="form-check-input" type="checkbox" name="picto_option" value="<?= $option ?>" <?php if($user->getOptionPictoOnLink() == 1) { echo 'checked'; } ?>>
							<label style="padding-top: 4px; padding-left: 10px;" class="form-check-label" for="picto_option"> Ajouter les icônes devant les liens <span class="badge rounded-pill bg-danger text-center">Pro</span></label>
						</form>
					</div>
				</div>
				<div class="col-md-12 col-xs-12 mb-5">
					<h4>Forme du bouton <span class="badge rounded-pill bg-danger text-center">Pro</span></h4>
					<form action="edittheme" method="post">
						<div class="row">
							<div class="col-md-3 col-xs-6">
								<div class="form-check">
									<input class="form-check-input" type="radio" name="btn-type" id="btn-type-squared" value="btn-squared" <?php if($themes->getButtonType() == 'btn-squared') { echo 'checked'; } ?>>
									<label class="form-check-label" for="btn-type-squared">
										<button type="button" class="btn btn-dark btn-squared">Carré plein</button>
									</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="btn-type" id="btn-type-rounded" value="btn-rounded" <?php if($themes->getButtonType() == 'btn-rounded') { echo 'checked'; } ?>>
									<label class="form-check-label" for="btn-type-rounded">
										<button type="button" class="btn btn-dark btn-rounded">Légèrement arrondi plein</button>
									</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="btn-type" id="btn-type-circle" value="btn-circle" <?php if($themes->getButtonType() == 'btn-circle') { echo 'checked'; } ?>>
									<label class="form-check-label" for="btn-type-circle">
										<button type="button" class="btn btn-dark btn-circle">Arrondi plein</button>
									</label>
								</div>
							</div>
							<div class="col-md-3 col-xs-6">
								<div class="form-check">
									<input class="form-check-input" type="radio" name="btn-type" id="btn-type-outline-squared" value="btn-outline-squared" <?php if($themes->getButtonType() == 'btn-outline-squared') { echo 'checked'; } ?>>
									<label class="form-check-label" for="btn-type-outline-squared">
										<button type="button" class="btn btn-outline-dark btn-squared">Carré contour</button>
									</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="btn-type" id="btn-type-outline-rounded" value="btn-outline-rounded" <?php if($themes->getButtonType() == 'btn-outline-rounded') { echo 'checked'; } ?>>
									<label class="form-check-label" for="btn-type-outline-rounded">
										<button type="button" class="btn btn-outline-dark btn-rounded">Légèrement arrondi contour</button>
									</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="btn-type" id="btn-type-outline-circle" value="btn-outline-circle" <?php if($themes->getButtonType() == 'btn-outline-circle') { echo 'checked'; } ?>>
									<label class="form-check-label" for="btn-type-outline-circle">
										<button type="button" class="btn btn-outline-dark btn-circle">Arrondi contour</button>
									</label>
								</div>
							</div>
						</div>
					</div>
					<div class="accordion" id="accordionExample">
						<div class="accordion-item">
							<h2 class="accordion-header" id="headingOne">
								<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
									<span class="badge rounded-pill bg-success text-center">FREE THEMES</span>
								</button>
							</h2>
							<div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
								<div class="accordion-body">
									<?php
									foreach($themes->getAllFreeThemes() as $k => $v)
									{
										foreach($user->getUserThemeId() as $k2 => $v2)
										{
											if($v2 == $v['id']) {
												$selected = "checked";
											}else {
												$selected = "";
											}
										}
										if(strlen($v['bg_color']) > 7) {
											$background =  'background: ' . $v['bg_color'].';';
										}else {
											$background = 'background-color: '.$v['bg_color'].';';
										}
										echo '
										<div class="grid-wrapper grid-col-2">
											<div class="selection-wrapper">
												<label for="selected-item-free-'.$v['id'].'" class="selected-label">
													<input type="radio" name="theme_id" id="selected-item-free-'.$v['id'].'" value="'.$v['id'].'" '.$selected.'>
													<span class="icon"></span>
													<div class="selected-content">';
														if(empty($v['bg_img']))
														{
															echo '<div class="selected-content" style="'.$background.';width: 230px; height: 172px;"></div>';
														}else {
															echo '<img src="'.$v['bg_img'].'" alt="">';
														}
														echo '
														<h4>'.$v['theme_name'].'</h4>
														<h5><button type="button" class="btn '.$v['bg_btn'] . ' '. $v['link_color'] . '">Sample button</button></h5>
													</div>
												</label>
											</div>
										</div>';
									}
									?>
								</div>
							</div>
						</div>
						<div class="accordion-item">
							<h2 class="accordion-header" id="headingTwo">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
									<span class="badge rounded-pill bg-danger text-center">PRO THEMES</span>
								</button>
							</h2>
							<div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
								<div class="accordion-body">
									<?php
									if($user->getUserPlan() == 0)
									{
										$disabled = "disabled";
										$message = "⚠️ You must have <span class=\"badge rounded-pill bg-danger text-center\">PRO PLAN</span> to get themes bellow. 👇🏼";
										echo '
											<div class="alert alert-danger text-center" role="alert">
												<h4>'.$message.'</h4>  
											</div>
										';
									}else {
										$disabled = "none";
										$message = "🎉️ Congrats ! You have <span class=\"badge rounded-pill bg-danger text-center\">PRO PLAN</span>, you can now choose Premium Theme ! 😁";
										echo '
											<div class="alert alert-success text-center" role="alert">
												<h4>'.$message.'</h4>  
											</div>
										';
									}
									foreach($themes->getAllPremiumThemes() as $k => $v)
									{
										foreach($user->getUserThemeId() as $k2 => $v2)
										{
											if($v2 == $v['id']) {
												$selected = "checked";
											}else {
												$selected = "";
											}
										}

										echo '
										<div class="grid-wrapper grid-col-2">
											<div class="selection-wrapper">
												<label for="selected-item-pro-'.$v['id'].'" class="selected-label">
													<input type="radio" name="theme_id" id="selected-item-pro-'.$v['id'].'" value="'.$v['id'].'" '.$selected.' '. $disabled .'>
													<span class="icon"></span>
													<div class="selected-content">';
														if(empty($v['bg_img']))
														{
															echo '<div class="selected-content" style="background-color:'.$v['bg_color'].';width: 230px; height: 172px;"></div>';
														}else {
															//echo '<img src="'.$v['bg_img'].'" alt="">';
														}
														echo '
														<h4>'.$v['theme_name'].'</h4>
														<h5><button type="button" class="btn '.$v['bg_btn'] . ' '. $v['link_color'] . '">Sample button</button></h5>
													</div>
												</label>
											</div>
										</div>';
									}
									?>
								</div>
							</div>
						</div>
					</div>
					<div class="mt-5 col-md-12 col-xs-12 text-center fixe-mobile">
						<button type="submit" class="btn btn-success btn-save-mobile">Enregistrer le thème 👌🏼</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>