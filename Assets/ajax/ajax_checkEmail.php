<?php
	try{
		$db = new PDO('mysql:host=db5001692237.hosting-data.io;dbname=dbs1400238; charset=utf8', 'dbu1526741', 'Ga92mf91!');
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	} catch(PDOException $e) {
		die('Erreur : ' . $e->getMessage());
	}

	$email = $_POST['email'];

	if(strlen($email) == 0)
	{
		echo ('empty');
	} else {
		$req = $db->prepare('SELECT email FROM users WHERE email = :email');
		$req->execute(array(
			'email' => $email
		));
		$chk_email = $req->rowCount();

		// Si le pseuo existe déjà on retourne non
		if ($chk_email == '1' || $chk_email > '1') {
			echo('no');
			$ok = false;
		} else {
			echo('yes');
		}
	}