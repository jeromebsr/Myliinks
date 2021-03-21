<?php
# IMPORTANT !!!!
# Penser lors de l'intégration de Stripe à repasser le thème en FREE si l'abonnement n'est pas reconduit

	include "pdo.php";

	use App\Controller\ThemesController;
	use App\Controller\UserController;

	$user = new UserController($db);
	$theme = new ThemesController($db);

	if(!$user->checkProfil($_GET['url']))
	{
		header('Location: 404');
	}

	foreach($user->loadProfil() as $k => $v)
	{
		$profil_pic = $v['profil_pic'];
		$username = $v['username'];
		$plan = $v['plan'];

		if(isset($v['id_user']))
		{
			$id_user = $v['id_user'];
		}
		break;
	}

	foreach($theme->loadTheme($plan) as $k => $v)
	{
		$bg = $v['bg_img'];
		$bg_color = $v['bg_color'];
		$bg_btn = $v['bg_btn'];
		$link_color = $v['link_color'];
		$btn_size = $v['btn_size'];
		break;
	}

	if(strlen($bg_color) > 7) {
		$background =  'background: ' . $bg_color.';';
	}else {
		$background = 'background-color: '.$bg_color.';';
	}

	$user->addView();
?>
<style>
	body {
		background-image: url('<?= $bg ?>');
		<?= $background ?>
		background-position: center center;
		background-repeat: no-repeat;
		background-size: cover;
		background-attachment: fixed;
	}

	@media (max-device-width: 576px) {
		.btn {
			max-width: 80%;
			margin-left: 10%;
		}
	}
</style>
<div class="container mt-5">
	<div class="row">
		<div class="col-12 mx-auto text-center mb-5">
			<img width="100" src="<?= $profil_pic ?>" alt="profil picture">
			<h5 class="<?= $link_color ?> mt-3">@<?= $username ?></h5>
		</div>
		<div class="col-md-6 col-xs-12 mx-auto text-center">
			<?php
			if(!isset($id_user) || !$id_user)
			{
				echo '
				<div class="card bg-dark">
					<div class="card-body">
						<h2>Hum... 🤨</h2>
						<h5>This user doesn\'t seem to have a link to share.</h5>
					</div>
				</div>
				';
			}else {
				foreach($user->loadProfil() as $k => $v)
				{
					if($v['status'] == 1)
					{
						echo '
						<div class="row mb-3">
							<a 
								style="padding: 10px; cursor:pointer;" 
								href="https://'.$v['url'].'" 
								title="Link to '.$v['link_name'].'" 
								target="_blank" 
								class="text-break btn '. $bg_btn . ' '. $btn_size . '"
							>';
								if($v['picto_on_link'] == 1)
								{
									echo '<img style="float:left;width:25px;height:auto;" src="Assets/img/brands/'.$v['thumbnail'].'" alt="image">'.$v['link_name'];
								}else {
									echo $v['link_name'];
								}
								echo '
							</a>
						</div>';
					}
				}
			}
			?>
		</div>
	</div>
</div>
