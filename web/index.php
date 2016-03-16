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
		$code1 = "http 400";
		header('Content-Type: application/json');
		return $code1;
	}
	elseif(empty($data2)){
		$code2 = "http 500";
		header('Content-Type: application/json');
		return $code2;
	}
	elseif(strtolower($data2) == strtolower($firma)){
		$code = "Http 200";
		$as = "{ </br>'valido': true </br>'mensaje':".$mensaje. "</br>}";
		header('Content-Type: application/json');
		return $as;
	}
	else {
		$code = "Http 200";
		$as = "{ </br>'valido': false </br>'mensaje':".$mensaje. "</br>}";
		header('Content-Type: application/json');
		return $as;
	}

});


$app->get('/status', function() use($app) {
  
  $data = "Http 201";
header('Content-Type: application/json');
//echo json_encode($data);
   return $data;
});

$app->get('/texto', function() use($app) {
  
  $mensaje = file_get_contents('https://s3.amazonaws.com/files.principal/texto.txt');
	$firma = $_GET['hash'];
	$data2 = hash('sha256', $mensaje);
	if(empty($data2)){
		$code2 = "http 500";
		header('Content-Type: application/json');
		return $code2;
	}
	else {
		$code = "Http 200";
		$as = "{ </br>'text':".$mensaje." </br>'hash':".$data2. "</br>}";
		header('Content-Type: application/json');
		return $as;
	}
});
$app->run();
