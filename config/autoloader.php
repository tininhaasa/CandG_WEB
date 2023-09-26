<?php

/**
 *
 * Autoloader
 * 
 * @author Emprezaz.com
 *
 **/

/**
 *
 * Pastas onde as classes serão adicionadas.
 * OBS: Para suporte de uma nova pasta adicione no array.
 *
 **/


/* 
 * importando a biblioteca do Braintree 
 */
// require ROOT . "/vendor/autoload.php";

$paths = array(
  'controllers',
  'controllers/site',
  'controllers/site/terms',
  'controllers/dashboard',
  'helpers',
  'models',
  'models/asaas',
  'models/db',
  'models/admin',
  'models/user',                                              
);

/**
 *
 * Registrando o autoloader
 *
 **/
spl_autoload_register(function ($classname) use ($paths) {

  $found = false;

  foreach ($paths as $path) {

    $file = $path . DIRECTORY_SEPARATOR . $classname . '.php';

    if (file_exists($file)) {
      require $file;
      $found = true;
      break;
    }
  }

  if (!$found) {
    exit('Class ' . $classname . ' not found.');
  }
});
