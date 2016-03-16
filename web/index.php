<?php

require('../vendor/autoload.php');

$app = new Silex\Application();
$app['debug'] = true;

// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
  'monolog.logfile' => 'php://stderr',
));

// Register view rendering
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

// Our web handlers

$app->get('/', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return $app['twig']->render('index.twig');
});

$app->post('/validarFirma', function($mensaje,$firma) use($app) {
	$data = hash($firma, $mensaje);
	return $data;
});

use Phalcon\Http\Response; 

$app->get('/status', function() use($app) {
  
  $data = "Http 201";
header('Content-Type: application/json');
//echo json_encode($data);
   return $data;
});

$app->run();
