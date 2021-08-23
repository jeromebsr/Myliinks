<?php

namespace App\Controller;
use PDO;
class LinksController
{
	/**
	 * @var PDO
	 */
	protected $db;
	/**
	 * @var array
	 */
	private $and;

	public function __construct(PDO $db)
	{
		$this->db = $db;
	}

	/**
	 * @return mixed
	 * Retourne tous les liens de l'utilisateur actif
	 */
	public function getAllLinks()
	{
		$query = $this->db->prepare('SELECT * FROM users_links WHERE id_user = :id_user ORDER BY order_link ASC');
		$query->bindValue(':id_user', $_SESSION['user']['id']);
		$query->execute();

		$tab = $query->fetchAll(PDO::FETCH_ASSOC);
		return $tab;
	}

	/**
	 * Crée un nouveau lien
	 */
	public function addLink()
	{
		if($this->checkLengh($_POST['link_name']) || $this->checkLengh($_POST['url']))
		{
			if($this->checkLink($_POST['url']))
			{
				$thumbnail = 'logo-'.$this->checkLink($_POST['url']).'.png';
			}else {
				$thumbnail = 'logo-default.png';
			}

			$query = $this->db->prepare('
				SELECT order_link 
				FROM users_links
				WHERE id_user = :id_user
			');
			$query->bindValue(':id_user', $_SESSION['user']['id']);
			$query->execute();
			$tab = $query->fetchAll(PDO::FETCH_ASSOC);
			foreach($tab as $k => $v)
			{
				$order_link = $v['order_link']+1;
			}

			$query = $this->db->prepare('
				INSERT INTO 
					users_links
				SET
					link_name = :link_name,
					url = :url,
					id_user = :id,
					thumbnail = :thumbnail,
				    order_link = :order_link
				');
			$query->bindValue(':link_name', $_POST['link_name']);
			$query->bindValue(':url', $_POST['url']);
			$query->bindValue(':id', $_SESSION['user']['id']);
			$query->bindValue(':thumbnail', $thumbnail);
			$query->bindValue(':order_link', $order_link);

			$query->execute();
			header('Location: /admin');
		}else{
			die('Too many caracters. Max 255.');
		}
	}

	/**
	 * @param $id
	 * @return mixed
	 * Obtient le nom du lien
	 */
	public function getLinkName($id)
	{
		$query = $this->db->prepare('SELECT link_name FROM users_links WHERE id_user = :id_user AND id = :id_link');
		$query->bindValue(':id_user', $_SESSION['user']['id']);
		$query->bindValue(':id_link', $id);
		$query->execute();

		$tab = $query->fetch(PDO::FETCH_ASSOC);
		foreach($tab as $k => $v)
		{
			return $v;
		}
	}

	/**
	 * Edite le nom du lien
	 * @param $id
	 */
	public function editLinkName($id)
	{
		if($this->checkLengh($_POST['link_name_edit']))
		{
			$query = $this->db->prepare('
			UPDATE
				users_links
			SET
				link_name = :link_name
			WHERE 
				id_user = :id_user
			AND 
				id = :id_link					
 		');

			$query->bindValue(':link_name', $_POST['link_name_edit']);
			$query->bindValue(':id_user', $_SESSION['user']['id']);
			$query->bindValue(':id_link', $id);
			$query->execute();

			header('Location: /admin');
		}
	}

	/**
	 * Edite le nom du lien
	 * @param $id
	 */
	public function editLinkUrl($id)
	{
		if($this->checkLink($_POST['link_url_edit']))
		{
			$thumbnail = 'logo-'.$this->checkLink($_POST['link_url_edit']).'.png';
		}else {
			$thumbnail = 'logo-default.png';
		}

		if($this->checkLengh($_POST['link_url_edit']))
		{
			$query = $this->db->prepare('
			UPDATE
				users_links
			SET
				url = :link_url,
				thumbnail = :thumbnail
			WHERE 
				id_user = :id_user
			AND 
				id = :id_link					
 		');

			$query->bindValue(':link_url', $_POST['link_url_edit']);
			$query->bindValue(':id_user', $_SESSION['user']['id']);
			$query->bindValue(':thumbnail', $thumbnail);
			$query->bindValue(':id_link', $id);
			$query->execute();

			header('Location: /admin');
		}
	}

	/**
	 * @param $id
	 * @param $state
	 * Active / Désactive un lien
	 */
	public function editLinkStatus($id, $state)
	{
		if(!isset($state))
		{
			$query = $this->db->prepare('
			UPDATE
				users_links
			SET
				status = 0
			WHERE 
				id_user = :id_user
			AND 
				id = :id_link					
 		');
			$query->bindValue(':id_user', $_SESSION['user']['id']);
			$query->bindValue(':id_link', $id);
			$query->execute();

			header('Location: /admin');
		}else {
			$query = $this->db->prepare('
			UPDATE
				users_links
			SET
				status = 1
			WHERE 
				id_user = :id_user
			AND 
				id = :id_link					
 		');
			$query->bindValue(':id_user', $_SESSION['user']['id']);
			$query->bindValue(':id_link', $id);
			$query->execute();

			header('Location: /admin');
		}
	}

	/**
	 * @param $d
	 * Supprime un lien
	 */
	public function deleteLink($id)
	{
		$query = $this->db->prepare('
			DELETE FROM users_links 
			WHERE id = :id 
			AND id_user = :id_user'
		);
		$query->bindValue(':id', $id);
		$query->bindValue(':id_user', $_SESSION['user']['id']);
		$query->execute();

		header('Location: /admin');
	}

	/**
	 * Met à jour l'ordre des liens
	 */
	public function reorderLinks()
	{
		$q = $this->db->prepare('SELECT order_link, link_name FROM users_links WHERE id_user = :id_user');
		$q->bindValue(':id_user', $_SESSION['user']['id']);
		$q->execute();
		$tab = $q->fetchAll(PDO::FETCH_ASSOC);

		$query = $this->db->prepare('
			UPDATE
				users_links
			SET
			    order_link = :order_link
			WHERE
				id = :link_id
			AND 
				id_user = :id_user	
		');

		foreach($_POST['order_link'] as $k_ol => $order_link)
		{
			$query->bindValue(':order_link', $order_link);
			dump('Order Link : ' .$order_link);
		}

		foreach($_POST['link_id'] as $k_li => $link_id)
		{
			$query->bindValue(':link_id', $link_id);
			dump('Link ID : ' .$link_id);

			$query->bindValue(':id_user', $_SESSION['user']['id']);
		}
		$query->execute();

		//die();

		header('Location: /admin');
	}

	/**
	 * @param $thumbnail
	 * Ajout un thumbnail au lien
	 */
	private function addThumbnail($thumbnail)
	{
		$query = $this->db->prepare('
			INSERT INTO 
				users_links
			SET thumbnail = :thumbnail
						
 		');
		$query->bindValue(':thumbnail', $thumbnail);
		$query->execute();
	}

	/**
	 * @param $link
	 * @return mixed
	 * Recherche le nom du site renvoyé
	 */
	private function checkLink($link)
	{
		$conditions = [
			"facebook",
			"twitter",
			"snapchat",
			"youtube",
			"instagram",
			"spotify",
			"deezer",
			"discord",
			"tiktok",
			"twitch",
			"reddit",
			"pinterest",
			"soundcloud",
			"linkedin",
			"tumblr",
			"messenger",
			"whatsapp",
			"wechat",
			"apple",
			"shopify",
			"myshopify",
			"patreon"
		];

		foreach($conditions as $k => $v)
		{
			if(stristr($link, $v))
			{
				return $v;
			}
		}
	}

	/**
	 * @param $string
	 * @return bool
	 * Vérifie que la chaine fait moins de 255 caractères
	 */
	private function checkLengh($string)
	{
		if(strlen($string) > 255)
		{
			return false;
		}else{
			return true;
		}
	}
}