<?php
	try{
		$db = new PDO('mysql:host=db5001692237.hosting-data.io;dbname=dbs1400238; charset=utf8', 'dbu1526741', 'Ga92mf91!');
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	} catch(PDOException $e) {
		die('Erreur : ' . $e->getMessage());
	}

	$username = $_POST['username'];

	if(strlen($username) == 0)
	{
		echo ('empty');
	}
	else {
		$req = $db->prepare('SELECT username FROM users WHERE username = :username');
		$req->execute([
			'username' => $username
		]);
		$chk_pseudo = $req->rowCount();

		// Si le pseuo existe déjà on retourne non
		if ($chk_pseudo == '1' || $chk_pseudo > '1')
		{
			echo('no');
			$ok = false;
		} else {
			echo('yes');
		}
	}