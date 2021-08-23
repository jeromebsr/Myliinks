<?php
	if(!isset($_SESSION['user']))
	{
		header('Location: login');
	}

	include "pdo.php";
	use App\Controller\UserController;
	$user = new UserController($db);

	foreach($user->loadUserById() as $k => $v)
	{
		$username = $v['username'];
	}

	if(isset($_POST['username'])
		&& isset($_POST['full_name'])
		&& isset($_POST['known_as'])
		&& isset($_POST['category'])
		&& isset($_POST['id_doc'])
	)
	{
		$user->sendVerificationRequest();
	}
?>

<link rel="stylesheet" href="../Assets/css/style.css">
<div class="container mt-5 mb-5">
	<div class="col-md-12 col-xs-12">
		<div class="row">
			<h1><i class="bi bi-patch-check"></i> Apply for Myliinks Verification</h1>
			<form method="post" enctype="multipart/form-data">
				<div class="form-floating mt-3 mb-3">
					<input type="text" name="username" class="form-control" id="link_name" placeholder="Pseudo" value="<?= $username ?>" required>
					<label for="link_name" class="text-dark">Username</label>
					<span class="link_name badge bg-danger"></span>
				</div>
				<div class="form-floating mt-3 mb-3">
					<input type="text" name="full_name" class="form-control" id="link_name" placeholder="Pseudo" required>
					<label for="link_name" class="text-dark">Full name</label>
					<span class="link_name badge bg-danger"></span>
				</div>
				<div class="form-floating mt-3 mb-3">
					<input type="text" name="known_as" class="form-control" id="link_name" placeholder="Pseudo" required>
					<label for="link_name" class="text-dark">Known as :</label>
					<span class="link_name badge bg-danger"></span>
				</div>
				<select  name="category" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" required>
					<option selected>Select a category for your accounts</option>
					<option value="News/Media">News/Media</option>
					<option value="Sports">Sports</option>
					<option value="Government/Politics">Government/Politics</option>
					<option value="Music">Music</option>
					<option value="Fashio">Fashion</option>
					<option value="Entertainement">Entertainement</option>
					<option value="logger/Influencer">Blogger/Influencer</option>
					<option value="Business/Brand/Organization">Business/Brand/Organization</option>
					<option value="Other">Other</option>
				</select>
				<div class="mt-3 mb-3">
					<label for="formFile" class="form-label">Please attach a photo of your ID</label>
					<input class="form-control" type="file" id="id_doc" name="id_doc" required>
					<small>
						We require a government-issued photo ID that shows your name and date of birth (e.g. driver's license, passport or national identification card) or official business documents (tax filing, recent utility bill, article of incorporation) in order to review your request.
					</small>
				</div>
				<button type="submit" class="btn btn-purple">Send</button>
			</form>
		</div>
	</div>
</div>