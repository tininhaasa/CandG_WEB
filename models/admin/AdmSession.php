<?php

/**
*
* Classe que manipula os dados do admin na sessão.
*
* @author Cristina Stanck
* 
**/
class AdmSession
{

	private function control()
	{
		if(!isset($_COOKIE['Adm'])){
			
			setcookie('Adm', "", time() + (86400 * (30 * 6)), "/");

		}

	}

	public function save($data)
	{
		//$this->control();
		$data = json_encode($data);
		setcookie('Adm', $data, time() + (86400 * (30 * 6)), "/");
		return true;
		
	}


	public function has()
	{

		$this->control();

		if(isset($_COOKIE['Adm']) && $_COOKIE['Adm'] != ""){
			return true;
		}

		return false;

	}

	public function get($info)
	{

		// $this->control();

		// if(isset($_SESSION['Adm'][$info])){
		// 	return $_SESSION['Adm'][$info];
		// }
		//$this->control();
		if(isset($_COOKIE['Adm'])){
			$data = json_decode($_COOKIE['Adm'], true);
		}else{
			return false;
		}

		if(isset($data[$info])){
			return $data[$info];
		}else{
			return false;
		}
	}


	public function set($info, $value)
	{
		//$this->control();
		if(isset($_COOKIE['Adm'])){
			$data        = json_decode($_COOKIE['Adm'], true);
			$data[$info] = $value;

			$data = json_encode($data);
			$_COOKIE['Adm'] = $data;
			setcookie('Adm', $data, time() + (86400 * (30 * 6)), "/");
		}
		// $this->control();

		// $_SESSION['Adm'][$info] = $value;

	}

	public function delete()
	{

		// $this->control();
		// unset($_SESSION['Adm']);
		unset($_COOKIE['Adm']);
		setcookie('Adm', "", time() + 10, "/");

	}

	// public function getAlerts(){

	// 	$userData = new UserData;
	// 	$alerts = $userData->getAlertsNonwiewed();

	// 	return $alerts;
	// }

}