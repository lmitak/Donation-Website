<?php

require_once __DIR__.'/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

try{
    $app = new Silex\Application();

    $app['debug'] = true;

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/views',
    ));

    $app->register(new FranMoreno\Silex\Provider\ParisServiceProvider());
    $app->register(new Silex\Provider\SessionServiceProvider());

    $app->get('/main', function() use($app){

        return $app['twig']->render('main_site.twig');
    });

    $app->run();


}catch (Exception $e){
    echo $e->getMessage();
}