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

        include_once('logic/idiormUse.php');

        $tipovi = ORM::for_table('tipTvrtke')
            ->select('tipTvrtke.*')
            ->find_array();

        return $app['twig']->render('main_site.twig', array('tipovi' => $tipovi));
    });

	$app->get('/registracija', function() use($app){

        return $app['twig']->render('registracija.twig');
    });
	
	$app->get('/Info', function() use($app){

        return $app['twig']->render('izgled.twig');
    });

    $app->get('/prijava', function() use($app){

        return $app['twig']->render('prijava.twig');

    });


    $authorize = function (Request $request,  Silex\Application $app){
        $request->getSession()->start();

        // provjera podataka o ulogiranosti kroz sesiju
        if ($request->hasPreviousSession() && $request->getSession()->has('user')){
            return;
        }
        return $app->redirect('login');

    };

    // POST ruta za login
	// obradi login podatke i postavi sesiju
	 $app->post('/prijava', function (Request $request) use ($app) {

         include_once('logic/idiormUse.php');


         $parameters = $request->request->all();

         $korisnik = ORM::for_table('korisnik')
             ->where(array(
                 'username' => $parameters['name'],
                 'password' => $parameters['password'],
             ))
             ->find_one();


         // provjera za ulogiranost - postavi sesiju i redirektaj na osnovnu stranicu
         if ($korisnik) {
             $app['session']->set('user', array('username' => $parameters['name']));

             return $app->redirect('main');
         }
         else return $app->redirect('prijava');
     });

    // GET ruta za logout
    // izlogira (brise podatke iz sesije) i vraća na login ekran
    $app->get('/logout',  function () use ($app) {
        $app['session']->remove('user');
        return $app->redirect('prijava');
    });

    $app->post('/registracija', function (Request $request) use ($app) {


        include_once('logic/idiormUse.php');

        $parameters = $request->request->all();
        if( ! ($provjera = ORM::for_table('korisnik')
            ->where_any_is(array(
                array('username' => $parameters['name']),
                array('email' => $parameters['email'])))
            ->find_many())){

            if($parameters['password'] === $parameters['password2']){

                $korisnik = ORM::for_table('korisnik')->create();

                $korisnik->username=$parameters['name'];
                $korisnik->email=$parameters['email'];
                $korisnik->password=$parameters['password'];

                $korisnik->save();

                $app->redirect('main');
            }
        }
        else
            return $app->redirect('registracija');
    });


    $app->run();





}catch (Exception $e){
    echo $e->getMessage();
}