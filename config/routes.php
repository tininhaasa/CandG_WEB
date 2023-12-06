<?php

/**
 *
 * Definição das rotas e seus respectivos controllers e actions
 *
 * @author Cristina Stanck
 *
 **/

// rotas normais
$commonRoutes = array(
	'/'               			 		=> 'HomeController/index',
	'termos'						=> 'TermsController/index',
	'conduta'						=> 'TermsController/condut',
	'seguranca'						=> 'TermsController/security',
);

// rotas POST
$commonPost = array(

);

$commonRoutes = array_merge($commonRoutes, $commonPost);

return $commonRoutes;