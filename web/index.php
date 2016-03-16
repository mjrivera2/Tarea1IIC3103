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

$app->post('/validarFirma', function() use($app) {
	$mensaje = $_GET['mensaje'];
	$firma = $_GET['hash'];
	$data2 = hash('sha256', $mensaje);
	if(strtolower($data2) == strtolower($firma)){
		$code = "Http 200";
		$as = "{\n\"valido\": true\n\"mensaje\":".$mensaje
		header('Content-Type: application/json');
		return $as;
	}
	header('Content-Type: application/json');
	return $mensaje;
});


$app->get('/status', function() use($app) {
  
  $data = "Http 201";
header('Content-Type: application/json');
//echo json_encode($data);
   return $data;
});

$app->run();
