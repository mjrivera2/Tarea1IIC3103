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
	if(empty($mensaje) or empty($firma)){
		header('Content-Type: application/json');
		return $app->json('', 400);
	}
	elseif(empty($data2)){
		header('Content-Type: application/json');
		return $app->json('', 500);
	}
	elseif(strtolower($data2) == strtolower($firma)){
		$code = "HTTP 200";
		$as = $code."</br>{ </br>'valido': true </br>'mensaje':".$mensaje. "</br>}";
		header('Content-Type: application/json');
		return $as;
	}
	else {
		$code = "HTTP 200";
		$as = $code."</br>{ </br>'valido': false </br>'mensaje':".$mensaje. "</br>}";
		header('Content-Type: application/json');
		return $as;
	}

});


$app->get('/status', function() use($app) {
header('Content-Type: application/json');
   return $app->json('', 201);
});

$app->get('/texto', function() use($app) {
  
  $mensaje = file_get_contents('https://s3.amazonaws.com/files.principal/texto.txt');
	$firma = $_GET['hash'];
	$data2 = hash('sha256', $mensaje);
	if(empty($data2)){
		header('Content-Type: application/json');
		return $app->json('', 500);
	}
	else {
		$code = "HTTP 200";
		http_response_code(200);
		$as = $code."</br>'text': ".$mensaje." </br>'hash': ".$data2. "</br>}";
		header('Content-Type: application/json');
		return $as;
	}
});
$app->run();
