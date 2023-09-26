<?php

/**
 *
 * Controller do erro 404 (Página não encontrada).
 *
 * @author Cristina Stanck
 *
 **/
class Error404 extends Controller
{

	public function index()
	{

		$this->setLayout(
			'site/shared/layout.php',
			'Adicionar peixe',
			array(
				'assets/libs/fontawesome-6.0/css/all.min.css',
				'assets/css/site/error/style.css',
			),
			array()
		);
		$this->view('site/error/index.php');
	}
}
