<?php

/**
 *
 * Arquivo com as configurações do banco de dados.
 *
 * @author Cristina Stanck
 *
 **/

// $config = array(
// 	'dsn'      => 'mysql:dbname=fishingbook;host=127.0.0.1',
// 	'username' => 'root',
// 	'password' => null
// );

$config['dsn'] 		= 'mysql:dbname=CandG;host=localhost';
$config['username'] = '';
$config['password'] = '';

// Caso seja o ambiente de produção a configuração é trocada
if (ENV == 'prod') {
	$config['dsn'] 		= 'mysql:dbname=fishingbook;host=localhost';
	
	$config['username'] = 'fishingbook_user';
	$config['password'] = 'Zlcg74*9';
}

return $config;
