<?php

/**
*
* Controller do site.
*
* @author Cristina Stanck
*
**/
class HomeController extends Controller
{

	//home header
	public function index()
	{		
		
		$this->setLayout(
			'site/shared/layout.php',
			'Casting and GrandAxes',
			array(
				'assets/libs/fontawesome-6.0/css/all.min.css',
				'assets/css/site/home/home.css',
			),
			array(
			)
		);
		$this->view('site/home/index.php');

	}

}