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
  ?>
  hola
<?php
});

use Phalcon\Http\Response; 

$app->get('/status', function() use($app) {
  
  $response = new Response();

        // Change the HTTP status
        $response->setStatusCode(201, "Created");

        $response->setJsonContent(
            array(
                'status' => 'OK',
            )
        );
        return $response;
});

$app->run();
