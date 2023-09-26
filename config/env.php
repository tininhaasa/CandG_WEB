<?php

/**
 *
 * Definição do ambiente (local ou online)
 *
 * @author Cristina Stanck
 *
 **/

/**
 *
 * Verificação do ambiente atual
 *
 **/
$env = 'prod';

if (!empty($_SERVER['SERVER_NAME']) and $_SERVER['SERVER_NAME'] === 'localhost'  or $_SERVER['SERVER_NAME'] === '192.168.0.148' or $_SERVER['SERVER_NAME'] === '192.168.0.168' or $_SERVER['SERVER_NAME'] === '192.168.100.134' or $_SERVER['SERVER_NAME'] === '192.168.1.9' or $_SERVER['SERVER_NAME'] === '192.168.100.27' or $_SERVER['SERVER_NAME'] === '192.168.100.36' or $_SERVER['SERVER_NAME'] === '192.168.100.203') {
  $env = 'dev';
}
/**
 *
 * Controle do erro reporting de acordo com o ambiente.
 *
 **/
$error = 1;

if ($env == 'prod') {
  $error = false;
}

error_reporting(0);
