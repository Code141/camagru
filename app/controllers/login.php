<?php

class login extends controller_public_only
{
	public function	__construct()
	{
		parent::__construct();

		$this->data['title'] = "login";
		$this->files['css'][] = 'login';
		$this->files['views']['center'] = 'login/login';
		$this->files['views']['footer'] = 'login/footer';
	}

	public function main($params = null)
	{
	}

	public function checklogin($params = null)
	{
		$this->data['msg'] = "Checking login...";
		$this->files['views']['center'] = 'msg';
		$this->load->script('php', 'login');

		$register_fields = array(
			"username",
			"password"
		);

		foreach ($register_fields as $field)
			if (!isset($_POST[$field]) || empty($_POST[$field]))
				redirect("login/unset_field/" . $field);

//				htmlentities($_POST[$field]);

		$this->data['username'] = stripslashes($_POST['username']);

		$this->data['password_length'] = strlen($_POST['password']);
		$this->data['encrypted_password'] = hash_password($_POST['password']);

		$this->data['dblogin'] = $this->load->model('login', 'login', $this->data);
		$this->data['dblogin'] = $this->data['dblogin']->fetchAll();
		$count = count($this->data['dblogin']);
		$this->data['dblogin'] = $this->data['dblogin']['0'];
		if ($count != 1)
			redirect ('login/error/unknow_user');
		if ($this->data['encrypted_password'] != $this->data['dblogin']['password'])
			redirect ('login/error/bad_password');
		if ($this->data['dblogin']['validated_account'] != "1")
			redirect ('login/error/account_not_validated/');
		$this->login();


		redirect ($_SESSION['last_url']['controller'] . '/' . $_SESSION['last_url']['action']);
	}

	private	function	login()
	{
		$_SESSION['user']['id'] = $this->data['dblogin']['id'];
		$_SESSION['user']['email'] = $this->data['dblogin']['email'];
		$_SESSION['user']['password_length'] = $this->data['password_length'];
		$_SESSION['user']['username'] = $this->data['dblogin']['username'];
		$_SESSION['user']['n_like'] = $this->data['dblogin']['notif_like'];
		$_SESSION['user']['n_comment'] = $this->data['dblogin']['notif_comment'];
		$_SESSION['user']['n_message'] = $this->data['dblogin']['notif_message'];
	}

	public function forgotten_password($params = null)
	{
		$this->data['title'] = 'forgotten_password';
		echo "forgotten password";
	}

	public function restricted($params = null)
	{
		$this->data['error'] = "Restricted area";
	}

	public function error($params = null)
	{
		$this->data['title'] = "Login error";
		switch ($params[0]):
			case "unknow_user":
				$this->data['error'] = "Unknow user";
			break;
			case "bad_password":
				$this->data['error'] = "Bad password";
			break;
			case "account_not_validated":
				$this->data['error'] = "Account not validated";
			break;
			case "unset_field":
				if (!empty($params[1]))
					$this->data['error'] = "Field " . $params[1] . " is required";
				else
					$this->data['error'] = "All fields must be set";
			break;
			default:
				$this->data['error'] = "Unknow error";
		endswitch;
	}

}
