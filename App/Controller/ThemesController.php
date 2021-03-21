<?php

namespace App\Controller;
use PDO;

class ThemesController
{
	/**
	 * @var PDO
	 */
	protected $db;

	public function __construct(PDO $db)
	{
		$this->db = $db;
	}

	/**
	 * @return mixed
	 * Retourne le listing des themes gratuits
	 */
	public function getAllFreeThemes()
	{
		$query = $this->db->prepare('SELECT * FROM themes_free');
		$query->execute();

		$tab = $query->fetchAll(PDO::FETCH_ASSOC);
		return $tab;
	}

	/**
	 * @return mixed
	 * Retourne le listing des themes premium
	 */
	public function getAllPremiumThemes()
	{
		$query = $this->db->prepare('SELECT * FROM themes_premium');
		$query->execute();

		$tab = $query->fetchAll(PDO::FETCH_ASSOC);
		return $tab;
	}

	/**
	 * @param $plan
	 * @return array
	 * Charge le theme de l'utilisateur
	 */
	public function loadTheme($plan)
	{
		if($plan == 1)
		{
			# Vérifie si l'utilisateur PRO souhaite utiliser un thème gratuit
			if($this->getCurrentTheme() < 100)
			{
				$query = $this->db->prepare('
				SELECT *
				FROM themes_free
				JOIN users ON users.theme_id = themes_free.id
				WHERE users.username = :username
			');
			}else {
				$query = $this->db->prepare('
				SELECT *
				FROM themes_premium
				JOIN users ON users.theme_id = themes_premium.id
				WHERE users.username = :username
			');
			}
		}else {
			$query = $this->db->prepare('
				SELECT *
				FROM themes_free
				JOIN users ON users.theme_id = themes_free.id
				WHERE users.username = :username
			');
		}
		$query->bindValue(':username', $_GET['url']);
		$query->execute();

		$tab = $query->fetchAll(PDO::FETCH_ASSOC);
		return $tab;
	}

	/**
	 * @param $theme_id
	 * Edite le thème de l'utilisateur
	 */
	public function editTheme($theme_id, $btn_type)
	{
		$user = new UserController($this->db);
		if($theme_id > 100 && $user->getUserPlan() == 0)
		{
			header('Location: themes');
			die();
		}

		$query = $this->db->prepare('
			UPDATE 
				users
			SET 
				theme_id = :theme_id,
				button_type = :button_type
			WHERE 
				id = :id
		');
		$query->bindValue(':theme_id', $theme_id);
		$query->bindValue(':button_type', $btn_type);
		$query->bindValue(':id', $_SESSION['user']['id']);
		$query->execute();

		$query = $this->db->prepare('
			UPDATE
				theme_options
			SET
				btn_type = :btn_type
			WHERE
				id = :user_id	
		');
		$query->bindValue(':btn_type', $btn_type);
		$query->bindValue(':id', $_SESSION['user']['id']);

		header('Location: themes');
	}

	/**
	 * @return mixed
	 * Retourne le type de boutton de l'utilisateur
	 */
	public function getButtonType()
	{
		$query = $this->db->prepare('
			SELECT btn_type
			FROM theme_options
			WHERE id = :id
		');

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
	 * Retourne l'id du theme actuel de l'utilisateur
	 */
	private function getCurrentTheme()
	{
		$query = $this->db->prepare('
				SELECT theme_id
				FROM users
				WHERE users.username = :username
			');

		$query->bindValue(':username', $_GET['url']);
		$query->execute();

		$tab = $query->fetch(PDO::FETCH_ASSOC);
		foreach($tab as $k => $v)
		{
			return $v;
		}

	}

}