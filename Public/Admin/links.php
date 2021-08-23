<?php
	if(!isset($_SESSION['user']))
	{
		header('Location: login');
	}
           
	use App\Controller\LinksController;
	use App\Controller\UserController;
	use App\Router\Router;
	use chillerlan\QRCode\QRCode;
	use chillerlan\QRCode\QROptions;

	include "pdo.php";
	$user = new UserController($db);
	$link = new LinksController($db);
	$router = new Router($_GET['url']);

	include "Public/Admin/Ressources/php/menu.php";

	$data = $_SERVER['HTTP_HOST']. '/' .$_SESSION['user']['username'];
	$options = new QROptions([
		'version'    => 5,
		'outputType' => QRCode::OUTPUT_IMAGE_JPG,
		'eccLevel'   => QRCode::ECC_L,
	]);
	$qrcode = new QRCode($options);

$qrcode->render($data);
?>
<style>
	@media (max-device-width: 576px)
	{
		.card {
			text-align: left;
		}

		.thumbnail {
			width: 100%;
		}

		.form-switch .form-check-input {
			margin-left: -10px;
		}

		.trash-icon {
			margin-left: 40px;
		}
	}
</style>
<div class="container mt-5">
	<div class="col-md-9 col-xs-12">
		<button id="btn-add-link" style="width: 50%;padding: 10px; margin-left: 25%;" type="button" class="btn btn-success mb-5">
			<i class="bi bi-node-plus-fill"></i> Add New Link
		</button>
		<div id="card-add-link" class="col-md-9 col-xs-12 mx-auto display-none">
			<div class="row">
				<div class="card mb-3 mx-auto bg-cutom-card">
					<div class="row g-0">
						<div class="col-12">
							<div style="float: right;" class="col-1">
								<i style="cursor:pointer; font-size: 20px;" class="bi bi-x-octagon-fill close"></i>
							</div>
							<div class="card-body col-md-6 col-xs-12 mx-auto">
								<form method="post" action="admin/addlink">
									<div class="form-floating mt-3 mb-3">
										<input type="text" name="link_name" class="form-control" id="link_name" placeholder="test" required>
										<label for="link_name" class="text-dark">Enter Title</label>
										<span class="link_name badge bg-danger"></span>
									</div>
									<div class="form-floating mb-3">
										<input type="text" name="url" class="form-control" id="url" placeholder="test" required>
										<label for="url" class="text-dark">Enter URL </label>
										<span class="url badge bg-danger"></span>
									</div>
									<div class="form-floating">
										<button id="submit" style="width: 100%;" type="submit" class="btn btn-success btn-validate-add-link mb-3 b-tn-lg">Add Link</button>
										<small class="alert-danger submit"></small>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<form action="/admin/reorder" method="post">
		<h4>Réorganisation des liens</h4>
		<?php
		foreach($link->getAllLinks() as $k => $v)
		{
			echo '
			<div class="row">
				<div class="col-md-2 col-xs-12">
					<div class="mb-1">
					<img class="thumbnail" width="12" style="margin-left: -12px;" src="Assets/img/brands/'.$v['thumbnail'].'" alt="'.$v['link_name'].'">
						<label for="'.$v['link_name'].'" class="form-label">"'.$v['link_name'].'"</label>
						<input type="number" name="order_link[]" class="form-control" value="'.$v['order_link'].'">
						<input type="hidden" name="link_id[]" class="form-control" value="'.$v['id'].'">
					</div>
				</div>
			</div>';
		}
		?>
		<button class="btn btn-success mt-3" type="submit">Valider</button>
	</form>

	<div class="row">
		<div class="col-md-9 col-xs-12 mb-5">
			<?php
			foreach($link->getAllLinks() as $k => $v)
			{
				if($v['status'] === "1")
				{
					$checked = "checked";
				} else {
					$checked = null;
				}

				echo '
				<div class="row">
					<div style="max-width: 70%;" class="card mb-3 mx-auto bg-cutom-card">
						<div class="row g-0">
							<div class="col-md-4 col-2">
								<img class="thumbnail" style="margin-left: -12px;" src="Assets/img/brands/'.$v['thumbnail'].'" alt="'.$v['link_name'].'">
							</div>
							<div class="col-md-6 col-7">
								<div class="card-body">
									<div style="cursor: pointer;" class="edit-link-name" id="edit-link-name-'.$v['id'].'" data-id="'.$v['id'].'">
										<h5 class="card-title">'.$v['link_name'].' <i style="font-size: 12px;" class="bi bi-pencil-fill"></i></h5>
									</div>
									<form id="form-edit-link-name" class="display-none form-edit-link-name" action="admin/editlinkname" method="post">
										<div class="form-floating mt-3 mb-3">
											<input type="text" name="link_name_edit" class="form-control" id="link_name_edit" value="'.$link->getLinkName($v['id']).'" required>
											<input type="hidden" name="hidden_id" value="'.$v['id'].'">
											<label for="link_name_edit" class="text-dark">Change Title</label>
											<span class="link_name_edit badge bg-danger"></span>
											
											<button type="button" class="btn btn-dark mb-3 b-tn-lg close-form">Cancel</button>
											<button type="submit" class="btn btn-validate-edit-title btn-success mb-3 b-tn-lg">Confirm</button>
										</div>
									</form>
									<div style="cursor: pointer;" class="edit-link-url" id="edit-link-url-'.$v['id'].'" data-id="'.$v['id'].'">
										<p class="card-title">'.$v['url'].' <i style="font-size: 12px;" class="bi bi-pencil-fill"></i></p>
									</div>
									<form id="form-edit-link-url" class="display-none form-edit-link-url" action="admin/editlinkurl" method="post">
										<div class="form-floating mt-3 mb-3">
											<input type="text" name="link_url_edit" class="form-control" id="link_url_edit" value="'.$v['url'].'" required>																<input type="hidden" name="hidden_id" value="'.$v['id'].'">
											<label for="link_url_edit" class="text-dark">Change URL</label>
											<span class="link_url_edit badge bg-danger"></span>
	
											<button type="button" class="btn btn-dark mb-3 b-tn-lg close-form">Cancel</button>
											<button type="submit" class="btn btn-validate-edit-title btn-success mb-3 b-tn-lg">Confirm</button>
										</div>
									</form> 
								</div>
							</div>
							<div class="col-md-2 col-3">
								<div class="card-body">
									<div class="form-check form-switch">
										<form id="editstatus" action="admin/editstatus" method="post">
											<input onchange="submit();" class="form-check-input" type="checkbox" name="status" value="'.$v['status'].'" '.$checked.'>	
											<input type="hidden" name="hidden_id" value="'.$v['id'].'">
										</form>
									</div>
									<div class="row mt-5">
										<form>
											<div class="deleteLinkId" data-id="'.$v['id'].'">
												<i style="cursor:pointer;" class="bi bi-trash-fill trash-icon" data-bs-toggle="modal" data-bs-target="#exampleModal"></i>
											</div>
										</form>
										<!-- Modal -->
										<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title" id="exampleModalLabel">Delete link</h5>
														<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
													</div>
													<div class="modal-body">
														<div id="card-remove-link" class="card-body">
															<p>Are you sure you want to delete this link ? This action cannot be undone.</p>
														</div>
													</div>
													<div class="modal-footer">
														<form action="admin/deletelink" method="post">
															<input class="hidden_delete_id" type="hidden" name="hidden_id" value="">
															<button type="button" class="btn btn-dark mb-3 b-tn-lg" data-bs-dismiss="modal">Cancel</button>
															<button type="submit" class="btn btn-danger mb-3 b-tn-lg">Delete</button>
														</form>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>';
			}
			?>
		</div>
		<div class="col-md-3 col-xs-12 text-center">
			<div class="row mb-3">
				<h2>Mon profil 👇🏼</h2>
				<h5>
					Myliinks :
					<a id="foo" value="<?= $_SERVER['HTTP_HOST'] ?>/<?= $_SESSION['user']['username'] ?>" target="_blank" href="/<?= $_SESSION['user']['username'] ?>">
						https://myliinks.com/<?= $_SESSION['user']['username'] ?>
					</a>
				</h5>
			</div>
			<div class="row mt-2 mb-2">
				<h5>Mon QR Code :</h5>
				<img src="<?=($qrcode)->render($data) ?>" alt="Mon QR Code" />
			</div>

				<!-- Trigger -->
				<button class="btn btn-purple" data-clipboard-target="#foo">
					<i class="bi bi-clipboard"></i> Copier le lien
				</button>
				<p><span class="copied">C'est fait ! 👌🏼</span></p>
			</div>
			<div class="row mb-3">
				<h5><i class="bi bi-eye"></i> Vues sur mon profil : <?= $user->getViews() ?></h5>
			</div>
			<div class="row mb-3">
				<h5><i class="bi bi-hand-index-thumb"></i> Total clicks : 38</h5>
			</div>
		</div>
	</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.rawgit.com/zenorocha/clipboard.js/master/dist/clipboard.min.js"></script>
<script>
	var clip = new ClipboardJS('.btn');
	clip.on('success', function(e) {
		$('.copied').show();
		$('.copied').fadeOut(1000);
	});
</script>
<script src="Assets/js/add-link.js"></script>