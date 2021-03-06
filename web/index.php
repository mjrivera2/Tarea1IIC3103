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
	$mensaje = $_REQUEST['mensaje'];
	$firma = $_REQUEST['hash'];
	$data2 = hash('sha256', $mensaje);
	if(empty($mensaje) or empty($firma)){
		header('Content-Type: application/json');
		return $app->json('', 400);
	}
	elseif(empty($data2)){
		header('Content-Type: application/json');
		return $app->json('', 500);
	}
	elseif(strtolower($data2) == strtolower($firma)){
		header('Content-Type: application/json');
		$bool = true;
		$json_data = array('valido'=>$bool,'mensaje'=>$mensaje);
		$json = json_encode($json_data);
		return $json;
		
//		return $json['valido'];
	}
	else {
		header('Content-Type: application/json');
		$bool = false;
		$json_data = array('valido'=>$bool,'mensaje'=>$mensaje);
		$json = json_encode($json_data);
		return $json;
	}

});


$app->get('/status', function() use($app) {
header('Content-Type: application/json');
   return $app->json('', 201);
});

$app->get('/texto', function() use($app) {
  
  $mensaje = file_get_contents('https://s3.amazonaws.com/files.principal/texto.txt');
	$firma = $_REQUEST['hash'];
	$data2 = hash('sha256', $mensaje);
	if(empty($data2)){
		header('Content-Type: application/json');
		return $app->json('', 500);
	}
	else {
		header('Content-Type: application/json');
		$json_data = array('text'=>$mensaje,'hash'=>$data2);
		$json = json_encode($json_data);
		return $json;
	}
});
$app->run();
