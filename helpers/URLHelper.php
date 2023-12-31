<?php

/**
*
* Helper que serve para adicionar a url em um arquivo ou link.
*
* @author Cristina Stanck
* 
**/
class URLHelper
{

	protected $title;
	protected $styles;
	protected $scripts;
	protected $version;

	public function __construct()
	{

		$this->styles  = array();
		$this->scripts = array();


		$this->version = (ENV == 'dev' OR 1 == 1) ? '?version=1.4.5': '';

	}

	public function getURL($file = null)
	{

		$url = (ENV === 'dev') ? LOCAL_URL : '/';
		$url = rtrim($url, '/');

		if(is_null($file)){
			return $url;
		}
 
		$file = ltrim($file, '/');
		$file = $url . '/' . $file;

		return $file;

	}

	public function getLocation()
	{
		
		$url = $_SERVER['REQUEST_URI'];

		$path = LOCAL_URL;

		$url = ltrim($url, '/');

		if(strstr($url, '/')){

			$url = explode("/", $url);
			$path = $url;
			return $path;

		}	

		return $url;

	}

	public function getLocationAdmin()
	{
		
		$url = $_SERVER['REQUEST_URI'];

		$path = LOCAL_URL;

		if(ENV !== 'local' || ENV !== 'prod'){

			$url = str_replace(array($path, "/"), "", $url);


		}	

		$url = ltrim($url, '/');

		if(strstr($url, '/')){
			
			$url = explode("/", $url);
			$path = $url[0];
			return $path;

		}	
		return $url;

	}

	public function setTitle($title = null)
	{

		$this->title = $title;

	}

	public function getTitle()
	{

		echo $this->title;

	}

	public function setStyles($style)
	{

		foreach($style as $value){
			array_push($this->styles, $value);
		}

	}

	public function getStyles()
	{

		foreach($this->styles as $value)
		{
			echo '<link rel="stylesheet" type="text/css" href="'.$this->getURL().'/'.$value.$this->version.'">';
		}

	}

	public function setScripts($script)
	{

		foreach($script as $value){
			array_push($this->scripts, $value);
		}

	}

	public function getScripts()
	{
		foreach($this->scripts as $value)
		{	
			echo '<script defer type="text/javascript" src="'.$this->getURL().'/'.$value.$this->version.'"></script>';
		}

	}

}