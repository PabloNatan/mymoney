<?php
Class User {

	private $user_id;
	private $user_name;
	private $user_login;
	private $user_password;
	private $user_birthday;

	
	public function __construct($name = '', $login = '', $password = '', $birthday = '')
	{
		$this->user_name = $name;
		$this->user_login = $login;
		$this->user_password= password_hash($password, PASSWORD_DEFAULT);
		$this->user_birthday = $birthday;
	}

	public function __get($attribute)
	{
		return $this->$attribute;
	}

	public function __set($atribute, $value)
	{
		$this->$atribute = $value;
	}

	public function loadById($id)
	{
		$sql = new Sql();

		$result = $sql->select(
			"SELECT * FROM tb_user WHERE user_id = :user_id", array(':user_id'=>$id)
		);

		$this->__set('user_id', $result[0]['user_id']);
		$this->__set('user_name', $result[0]['user_name']);
		$this->__set('user_login', $result[0]['user_login']);
		$this->__set('user_password', $result[0]['user_password']);
		$this->__set('user_birthday', $result[0]['user_birthday']);
		
	}

	public function insert()
	{
		$sql = new Sql();

		//recover from database, logins
		$result = $sql->select("SELECT * FROM tb_user WHERE user_login = :user_login", array('user_login'=>$this->__get('user_login')));

		//if there is no return we can insert a new user with this id
		if(count($result)  === 0)
		{
			$result = $sql->select(
					"CALL sp_user_insert(:user_name, :user_login, :user_password, :user_birthday)",
				 array(
				 	':user_name'=>$this->__get('user_name'),
				 	':user_login'=>$this->__get('user_login'),
				 	':user_password'=>$this->__get('user_password'),
				 	':user_birthday'=>$this->__get('user_birthday')
				 )
			);

			//if the new insert occurs, we retrieve the data from the new user
			if(count($result) > 0)
			{
				
				$this->__set('user_id', $result[0]['user_id']);
				$this->__set('user_name', $result[0]['user_name']);
				$this->__set('user_login', $result[0]['user_login']);
				$this->__set('user_password', $result[0]['user_password']);
				$this->__set('user_birthday', $result[0]['user_birthday']);
			}
		}
		else 
		{
			throw new Exception("Login já cadastrado favor selecione outro");
		}
	}

	public function update($name = '' , $login = '', $password = '', $birthday = '')
	{

		if($this->verifyLoginExist($login))
		{
			if($name != '') $this->__set('user_name', $name);
			if($login != '') $this->__set('user_login', $login);
			if($password != '') $this->__set('user_password', password_hash($password, PASSWORD_DEFAULT));
			if($birthday != '') $this->__set('user_birthday', $birthday);
			
			$sql = new Sql();

			$sql->query("
				UPDATE tb_user SET user_name = :user_name, user_login = :user_login, user_password = :user_password, user_birthday = :user_birthday 
				WHERE user_id = :user_id",
					 array(
					 	':user_name'=>$this->__get('user_name'),
					 	':user_login'=>$this->__get('user_login'),
					 	':user_password'=>$this->__get('user_password'),
					 	':user_birthday'=>$this->__get('user_birthday'),
					 	':user_id'=>$this->__get('user_id')
					 ));
		}
		else 
		{
			throw new Exception("Login já está em uso favor escoolha outro!!");
		}

	}

	public function delete()
	{
		$sql = new Sql();
		
		$sql->query("DELETE FROM tb_user WHERE user_id = :user_id", array(':user_id'=> $this->__get('user_id')));

		$this->__set('user_id', '');
		$this->__set('user_name', '');
		$this->__set('user_login', '');
		$this->__set('user_password', '');
		$this->__set('user_birthday', '');


	}

	public function verifyLoginExist($login)
	{
		$sql = new Sql();

		$result = $sql->select(
			"SELECT * FROM  tb_user WHERE user_login = :user_login",
			array(
				':user_login'=>$login)
		);

		return count($result) > 0 ? true : false;
	}

	public function login($user_login, $user_password)
	{
		$sql = new Sql();

		$result = $sql->select("SELECT * FROM tb_user WHERE user_login = :user_login", array(':user_login'=>$user_login));

		if($this->verifyLoginExist($user_login))
		{
			if(password_verify($user_password, $result[0]['user_password']))
			{

				return true;
			} 
			else
			{
				throw new Exception("Senha inválida!!!");
			}
		}
		else
		{
			throw new Exception("Usuário e ou senha inválidos");
		}
	}

	public function __toString()
	{
		return array(
			'user_id'=>__get('user_id'),
			':user_name'=>__get('user_name'),
			'user_login'=>__get('user_login'),
			'user_password'=>__get('user_password'),
			':user_birthday'=>__get('user_birthday')
		);
	}
}
?>