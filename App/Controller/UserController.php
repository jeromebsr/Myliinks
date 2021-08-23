<?php
namespace App\Controller;
use App\Mailer;
use App\Router\Route;
use App\Router\Router;
use App\User;
use PDO;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class UserController extends User
{
	/**
	 * @var PDO
	 */
	protected $db;

	public function __construct(PDO $db)
	{
		$this->db = $db;
	}

	public function registerUser(User $user)
	{
		if(!$this->checkEmail($user->getEmail()))
		{
			$checkmail = true;
		}else {
			$checkmail = false;
		}

		if(!$this->checkUsername($user->getUsername()))
		{
			$checkpseudo = true;
		}else {
			$checkpseudo = false;
		}

		if($checkmail && $checkpseudo)
		{
			$token = $this->generateUserToken();
			$q = $this->db->prepare('
				INSERT INTO 
					users 
				SET 
					email = :email,
					username = :username,
					password = :password,
					slug = :username,
					token = :token
			');
			$q->bindValue(':email', $user->getEmail());
			$q->bindValue(':username', $user->getUsername());
			$q->bindValue(':password', $user->getPassword());
			$q->bindValue(':slug', $user->getUsername());
			$q->bindValue(':token', $token);

			$q->execute();

			$query = $this->db->prepare('
				SELECT id
				FROM users
				WHERE username = :username
			');
			$query->bindValue(':username', $user->getUsername());
			$query->execute();

			$tab = $query->fetch(PDO::FETCH_ASSOC);

			$query = $this->db->prepare('
				INSERT INTO 
					users_links
				SET
					link_name = :link_name,
					url = :url,
					thumbnail = :thumbnail,
					id_user = :id_user		
 			');
			$query->bindValue(':link_name', 'My first link');
			$query->bindValue(':url', 'https://');
			$query->bindValue(':thumbnail', 'logo-default.png');
			$query->bindValue(':id_user', $tab['id']);
			$query->execute();

			$query = $this->db->prepare('
				INSERT INTO
					theme_options
				SET 
					picto_link = :picto_link,
					btn_type = :btn_type,
					id_user = :id_user	
			');
			$query->bindValue(':picto_link', 0);
			$query->bindValue(':btn_type', 'btn-rounded');
			$query->bindValue(':id_user', $tab['id']);

			$query->execute();

			$mailer = new Mailer(true);

			$mailer->sendMail(
				'noreply@myliinks.com',
				 $user->getEmail(),
				'Activation de votre compte Myliinks',
				'activate_account_template',
				'Bienvenue sur Myliinks !<br/>
				  Activez votre compte, veuillez cliquer sur le lien ci-dessous ou copier/coller dans votre navigateur.<br />
				  <a href=\'https://myliinks.com/activate='.urlencode($user->getUsername()).'-token='.urlencode($token).'\'>CLIQUEZ ICI POUR ACTIVER VOTRE COMPTE</a>
				  ---------------<br />
				  Ceci est un mail automatique, merci de ne pas y répondre.');
			header('Location: register/ok');
		}else {
			return $this->redirectToRoute('register', null, 'Erreur', 'danger');
		}
	}

	/**
	 * @param $route
	 * @param $delay
	 * @param $message
	 * @param $type
	 * @return void
	 * Redirige l'utilisateur, possibilité d'ajouter un délai + un message flash
	 */
	public function redirectToRoute($route, $delay, $message, $type)
	{
		if(isset($message) && !empty($message))
		{
			$_SESSION['flash'] = [
				'message' => $message,
				'type' => $type
			];
		}

		if(isset($delay) && ($delay != null))
		{
			sleep($delay);
		}

		return header('Location: /' . $route);
	}

	/**
	 * Retourne un message Flash
	 */
	public function flash()
	{
		if(isset($_SESSION['flash']))
		{
			$icon = null;
			if($_SESSION['flash']['type'] == "success")
			{
				$icon = "ion ion-checkmark-circled";
			}elseif($_SESSION['flash']['type'] == "danger")
			{
				$icon = "ion ion-alert";
			}elseif($_SESSION['flash']['type'] == "warning")
			{
				$icon = "ion ion-android-warning";
			}elseif($_SESSION['flash']['type'] == "info")
			{
				$icon = "ion ion-information-circled";
			}
			?>
			<div class="flash flash-<?= $_SESSION['flash']['type'] ?>" id="flash">
				<div class="flash__icon flash-<?= $_SESSION['flash']['type'] ?>">
					<i style="padding-top: 50%; !important;" class="<?= $icon ?>"></i>
				</div>
				<p class="flash__body">
					<?= $_SESSION['flash']['message'] ?>
				</p>
			</div>
			<?php
			unset($_SESSION['flash']);
		}
	}

	/**
	 * @param $post_email
	 * @param $post_password
	 * @return bool
	 * Vérifie les infos de connexion pour se logger à son espace annonceur
	 */
	public function loginUser($post_email, $post_password)
	{
		foreach($this->getInfosUser($post_email) as $key => $value)
		{
			if($value['email'] === $post_email && password_verify($post_password, $value['password']) && $value['acc_status'] === "2")
			{
				if(isset($_POST['remember']))
				{
					setcookie('auth', $value['id'] . '-----' . sha1($value['username'] . $value['password'] . $_SERVER['REMOTE_ADDR']), time() + 3600 * 24 * 3, '/', 'localhost/links', true, true);
				}

				$_SESSION['user'] = $value;
				header("Location: admin");
				return true;

			} elseif($value['acc_status'] == "1") {
				$_SESSION['messageLogin'] = "Your account have been suspended. Please contact support.";
				return false;
			} elseif($value['acc_status'] == "0") {
				$_SESSION['messageLogin'] = "Your account is inactive, please activate it via the activation link received by email.";
				return false;
			} else {
				$_SESSION['messageLogin'] = "Incorrect email address or password.";
				return false;
			}
		}
	}

	/**
	 * @param $user_email
	 * @return array
	 * Retourne toutes les infos du compte connecté
	 */
	public function getInfosUser($user_email)
	{
		$query = $this->db->prepare('SELECT * FROM users WHERE email = :user_email');
		$query->bindValue(':user_email', $user_email);
		$query->execute();

		$tab = $query->fetchAll(PDO::FETCH_ASSOC);
		return $tab;
	}

	/**
	 * Déconnecte l'utilisateur
	 */
	public function logout()
	{
		unset($_SESSION);
		session_destroy();
		return header('Location: /login');
	}

	/**
	 * @param $email
	 * @return mixed
	 * Vérifie si l'adresse email existe lors de la création d'un compte user
	 */
	private function checkEmail($email)
	{
		$query = $this->db->prepare('SELECT email FROM users WHERE email = :email');
		$query->bindValue(':email', $email);
		$query->execute();

		$tab = $query->fetch(PDO::FETCH_ASSOC);
		return $tab;
	}

	/**
	 * @param $username
	 * @return mixed
	 * Vérifie si l'username existe lors de la création d'un compte user
	 */
	private function checkUsername($username)
	{
		$query = $this->db->prepare('SELECT username FROM users WHERE username = :username');
		$query->bindValue(':username', $username);
		$query->execute();

		$tab = $query->fetch(PDO::FETCH_ASSOC);
		return $tab;
	}

	/**
	 * Charge les infos du profil (page profil)
	 */
	public function loadProfil()
	{
		$query = $this->db->prepare('
			SELECT *
			FROM users
			JOIN users_links ON users.id = users_links.id_user
			WHERE username = :username
			ORDER BY users_links.order_link ASC
		');
		$query->bindValue(':username', $_GET['url']);
		$query->execute();

		$tab = $query->fetchAll(PDO::FETCH_ASSOC);

		# Si vide = aucun lien pour cet utilisateur
		# On charge juste les infos du profil sans les liens
		if(empty($tab))
		{
			$query = $this->db->prepare('
			SELECT *
			FROM users
			WHERE username = :username
		');
			$query->bindValue(':username', $_GET['url']);
			$query->execute();

			$tab = $query->fetchAll(PDO::FETCH_ASSOC);
		}
		return $tab;
	}

	/**
	 * @return array
	 * Renvoie les infos de l'utilisateur via son ID (SESSION)
	 */
	public function loadUserById()
	{
		$query = $this->db->prepare('
			SELECT *
			FROM users
			WHERE id = :id
		');
		$query->bindValue(':id', $_SESSION['user']['id']);
		$query->execute();

		$tab = $query->fetchAll(PDO::FETCH_ASSOC);
		return $tab;
	}

	/**
	 * @param $profil
	 * @return mixed
	 * Vérifie si le profil demandé existe bien
	 */
	public function checkProfil($profil)
	{
		$query = $this->db->prepare('SELECT username, acc_status FROM users WHERE username = :username');
		$query->bindValue(':username', $profil);
		$query->execute();

		$tab = $query->fetchAll(PDO::FETCH_ASSOC);

		foreach($tab as $k => $v)
		{
			if($v['acc_status'] == 2)
			{
				return $v;
			}
		}
	}

	/**
	 * @return mixed
	 * Récupère l'id du thème de l'utilisteur
	 */
	public function getUserThemeId()
	{
		$query = $this->db->prepare('SELECT theme_id FROM users WHERE id = :id');
		$query->bindValue(':id', $_SESSION['user']['id']);
		$query->execute();

		$tab = $query->fetch(PDO::FETCH_ASSOC);
		return $tab;
	}

	/**
	 * @return mixed
	 * Retourne le plan de l'utilisateur
	 */
	public function getUserPlan()
	{
	    $query = $this->db->prepare('SELECT plan FROM users WHERE id = :id');
		$query->bindValue(':id', $_SESSION['user']['id']);
		$query->execute();

		$tab = $query->fetch(PDO::FETCH_ASSOC);
		foreach($tab as $k => $v)
		{
			return $v;
		}
	}

	/**
	 * @return mixed
	 * Retourne l'option Picto
	 */
	public function getOptionPictoOnLink()
	{
		$query = $this->db->prepare('SELECT picto_on_link FROM users WHERE id = :id');
		$query->bindValue(':id', $_SESSION['user']['id']);
		$query->execute();

		$tab = $query->fetch(PDO::FETCH_ASSOC);
		foreach($tab as $k => $v)
		{
			return $v;
		}
	}

	/**
	 * @param $option
	 * @return bool|void
	 * Met à jour l'option Picto
	 */
	public function updateOptionPictoOnLink($option)
	{
		$user = new UserController($this->db);
		if($user->getUserPlan() == 0)
		{
			header('Location: themes');
		}else {
			if($option == "off")
			{
				$option = 1;
			}else {
				$option = 0;
			}

			$query = $this->db->prepare('UPDATE users SET picto_on_link = :option WHERE id = :id');
			$query->bindValue(':option', $option);
			$query->bindValue(':id', $_SESSION['user']['id']);
			$query->execute();

			return header('Location: themes');
		}
	}



	/**
	 * @return string
	 * Active le compte utilisateur si le token match
	 */
	public function activateAccount($username, $token)
	{
		$query = $this->db->prepare('SELECT token, acc_status FROM users WHERE username = :username');
		$query->bindValue(':username', $username);
		$query->execute();

		$tab = $query->fetchAll(PDO::FETCH_ASSOC);
		foreach($tab as $key => $value)
		{
			if($value['acc_status'] == '2' or $value['acc_status'] == '1')
			{
				echo '
					<div class="container">
						<div class="col-lg-12 mt-5 mb-5">
							<div class="alert alert-danger" role="alert">
								<h4 class="alert-heading text-center">Oops...</h4>
								<p>Une erreur est survenue, veuillez réssayer.</p>
								<hr />
								<p class="mb-0">Toute l\'équipe vous remercie pour votre confiance.</p>
							</div>
						</div>
					</div>';
			} else {
				if($value['token'] === $token)
				{
					$query = $this->db->prepare('UPDATE users SET acc_status = 2 WHERE username = :username');
					$query->bindValue(':username', $username);
					$query->execute();

					echo '
					<div class="container">
						<div class="col-lg-12 mt-5 mb-5">
							<div class="alert alert-success" role="alert">
								<h4 class="alert-heading text-center">Bienvenue à bord !</h4>
								<p>Votre compte est désormais activé ! Vous pouvez à présent créer votre profil !</p>
								<a href="/admin" title="Créer mon premier lien">Créer mon premier lien</a>
								<hr />
								<p class="mb-0">Toute l\'équipe vous remercie pour votre confiance.</p>
							</div>
						</div>
					</div>';
				} else {
					echo '
					<div class="container">
						<div class="col-lg-12 mt-5 mb-5">
							<div class="alert alert-danger" role="alert">
								<h4 class="alert-heading text-center">Oops...</h4>
								<p>Une erreur est survenue, veuillez réssayer.</p>
								<hr />
								<p class="mb-0">Toute l\'équipe vous remercie pour votre confiance.</p>
							</div>
						</div>
					</div>';
				}
			}
		}
	}

	/**
	 * Ajoute une vue au profil chargé
	 */
	public function addView()
	{
		$query = $this->db->prepare('SELECT total_views FROM users WHERE username = :username');
		$query->bindValue(':username', $_GET['url']);
		$query->execute();
		$tab = $query->fetch(PDO::FETCH_ASSOC);

		$total_views = $tab['total_views']+1;

		$query = $this->db->prepare('
			UPDATE users 
			SET total_views = :add_view 
			WHERE username = :username ');
		$query->bindValue(':add_view', $total_views);
		$query->bindValue(':username', $_GET['url']);
		$query->execute();
	}

	/**
	 * Retourne le nombre total de vues sur le profil
	 */
	public function getViews()
	{
		$query = $this->db->prepare('SELECT total_views FROM users WHERE id = :id');
		$query->bindValue(':id', $_SESSION['user']['id']);
		$query->execute();
		$tab = $query->fetch(PDO::FETCH_ASSOC);

		return $tab['total_views'];
	}

	public function sendVerificationRequest()
	{
		die('coucou');
		$mailer = new Mailer();
		$mailer->sendMail('verif@myliinks.com', 'jerome.bsrpro@gmail.com', 'Demande de vérification de compte (Trustbadge)', null, '
			<h1>Demande de vérifcation de compte :</h1>
			<p>
				Utilisateur : '.$_POST['username'].' <br />
				Nom complet : '.$_POST['full_name'].' <br />
				Connu comme : '.$_POST['known_as'].' <br />
				Catégorie : '.$_POST['category'].' <br />
			</p>
			<p>
				Cette demande provient du compte : '.$_SESSION['user']['username'].', avec l\'id : '.$_SESSION['user']['id'].'
			</p>
		', $_POST['id_doc']);
		dump($_FILES);

		//header('Location: /admin/settings');
	}

	/**
	 * @return string
	 * Crée un token unique - Utilisé pour activer le compte
	 */
	private function generateUserToken()
	{
		$token = md5(microtime(TRUE)*100000);
		return $token;
	}

	public function getSlug()
	{
		$query = $this->db->prepare('SELECT slug FROM users WHERE id = :id');
		$query->bindValue(':id', $_SESSION['user']['id']);
		$query->execute();
		$tab = $query->fetch(PDO::FETCH_ASSOC);

		return $tab['slug'];
	}

	/**
	 * @param $template
	 * @return mixed
	 * Charge le template d'email
	 */
	private function loadTemplate($template)
	{
		return include 'Public/Admin/Ressources/php/phpmailer/'.$template.'.php';
	}
}