<?php
namespace App;
class User
{
	private $email;
	private $username;
	private $password;

	public function __construct(array $data)
	{
		foreach($data as $key => $value)
		{
			$method = 'set' . ucfirst(str_replace("_", "", $key));
			/* Vérification de l'existance de la méthode */
			if(method_exists($this, $method))
			{
				$this->$method($value);
			}
		}
	}

	/**
	 * @return mixed
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * @param mixed $email
	 */
	public function setEmail($email)
	{
		$this->email = $email;
	}

	/**
	 * @return mixed
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * @param mixed $username
	 */
	public function setUsername($username)
	{
		$this->username = $username;
	}

	/**
	 * @return mixed
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * @param mixed $password
	 */
	public function setPassword($password)
	{
		$this->password = password_hash($password, PASSWORD_DEFAULT);
	}
}