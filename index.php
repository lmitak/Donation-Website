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

/*
    $authorize = function (Request $request,  Silex\Application $app){
        $request->getSession()->start();

        // provjera podataka o ulogiranosti kroz sesiju
        if ($request->hasPreviousSession() && $request->getSession()->has('user')){
            return;
        }
        return $app->redirect('login');

    };
*/
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

         if ($korisnik) {
             $pw=$korisnik->get('password');

             if(password_verify($parameters['password'],$pw)){
                 // provjera za ulogiranost - postavi sesiju i redirektaj na osnovnu stranicu
                 $app['session']->set('user', array('username' => $parameters['name']));
                 return $app->redirect('main');
             }else
                 return $app->redirect('prijava');

         }else
             return $app->redirect('prijava');
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

                $parameters['password']=password_hash($parameters['password'], PASSWORD_DEFAULT);

                $korisnik = ORM::for_table('korisnik')->create();

                $korisnik->username=$parameters['name'];
                $korisnik->email=$parameters['email'];
                $korisnik->password=$parameters['password'];

                $korisnik->save();

                return $app->redirect('main');
            }

            else return $app->redirect('registracija');
        }
        else return $app->redirect('registracija');
    });

    /*edit: salji id iz maina */
    $app->get('/info={id}' , function($id) use ($app) {

        include_once('logic/idiormUse.php');

        $tvrtka = ORM::for_table('tvrtka')->where('idTvrtke',$id)->find_one();
        $naziv = $tvrtka->get('naziv');
        $adresa = $tvrtka->get('adresa');
        $rating = $tvrtka->get('rating');
        $kolicina = $tvrtka->get('kolPopusta');
        $vrijeme = $tvrtka->get('vrijemePopusta');

        $komentari = ORM::for_table('komentari')->where('idTvrtke',$id)->find_many();

        $isUser = false;
        if($app['session']->get('user') != null)
        {
            $korisnik = ORM::for_table('korisnik')->select('idKorisnika')->where('username',$app['session']->get('user'))->find_one();
            $isUser = $korisnik->get('idKorisnika') == $id;
        }
        if(isset($_SESSION['anonymous'])){
            $antispamming = ORM::for_table('antispamming')->where('cookie', $_SESSION['anonymous'])->find_one();
        }else{
            $antispamming = array("rated" => false, "commented" => false);
        }

        switch($tvrtka->get('tip')){
            case 1:
                $tip = 'Pekara';
                break;
            case 2:
                $tip = 'Mesnica';
                break;
            case 3:
                $tip = 'Voćarna';
                break;
            case 4:
                $tip = 'Trgovina';
        }

        return $app['twig']->render('izgled.twig', array(
            'naziv' => $naziv,
            'tip' => $tip,
            'adresa' => $adresa,
            'rating' => $rating,
            'kolicina' => $kolicina,
            'vrijeme' => $vrijeme,
            'isUser' => $isUser,
            'komentari' => $komentari,
            'antispamming' => $antispamming
        ));
    });

    $app->get('/profil', function() use ($app){

        include_once('logic/idiormUse.php');
        //$korisnik_i_tvrtke = ORM::for_table("korisnik")->left_outer_join('tvrtka', array("tvrtka.idKorisnika", "=", "korisnik.idKorisnika"))
        //   ->where('username', 'luka')->find_many();
        $korisnik = ORM::for_table("korisnik")->where('username', 'stipe')->find_one();
        $tvrtke = ORM::for_table("tvrtka")->where('idKorisnika', $korisnik->get("idKorisnika"))->find_many();
        //fake session

        $app['session']->set('user', $korisnik->get('username'));
        //treba se staviti session user
        return $app['twig']->render('profil.twig', array('korisnik' => $korisnik, 'tvrtke' => $tvrtke));


    });

    $app->get('/dodajFirmu', function () use ($app){
        include_once('logic/idiormUse.php');

        if($korisnik = ORM::for_table("korisnik")->where('username', $app['session']->get('user'))->find_one()){
            $tipovi = ORM::for_table("tipTvrtke")->find_many();
            return $app['twig']->render('dodajFirmuForma.twig', array('tipovi' => $tipovi));
        }else
            return $app->redirect('login');
    });

    $app->post('/addComp', function (Request $request) use ($app){
        $parameters = $request->request->all();

        include_once('logic/idiormUse.php');

        $korisnik = ORM::for_table("korisnik")->where('username', $app['session']->get('user'))->find_one();

        if((empty($korisnik)) || ($parameters['naziv'] == null) || ($parameters['autocomplete_places'] == null) ||
            ($parameters['tip'] == null) || ($parameters['latitude'] == null) || ($parameters['longitude'] == null)){
            return "Error in input";

        }else{
            $tvrtka = ORM::for_table("tvrtka")->create();

            $tvrtka->idKorisnika = $korisnik->get("idKorisnika");
            $tvrtka->naziv = $parameters['naziv'];
            $tvrtka->adresa = $parameters['autocomplete_places'];
            $tvrtka->tip = $parameters['tip'];
            $tvrtka->datumPrijave = date("Y-m-d");
            $tvrtka->web = $parameters['web'];
            $tvrtka->latitude = $parameters['latitude'];
            $tvrtka->longitude = $parameters['longitude'];
            if($tvrtka->save()){
                return $app->redirect('profil');
            }else{
                return "Error while saving";
            }
        }
    });

    $app->get('/editUser', function () use ($app){
        include_once('logic/idiormUse.php');
        $korisnik = ORM::for_table("korisnik")->where('username', $app['session']->get('user'))->find_one();

        return $app['twig']->render('edit_profile.twig', array('korisnik' => $korisnik));
    });

    $app->get('/urediTvrtku={idTvrtke}', function ($idTvrtke) use ($app) {
        include_once('logic/idiormUse.php');
        $tvrtka = ORM::for_table('tvrtka')->where('idTvrtke', $idTvrtke)->find_one();
        $tipovi = ORM::for_table("tipTvrtke")->find_many();

        return $app['twig']->render('edit_tvrtke.twig', array('tvrtka' => $tvrtka, 'tipovi' => $tipovi));
    });

    $app->post('/uredi firmu', function(Request $request) use ($app){
        $parameters = $request->request->all();

        include_once('logic/idiormUse.php');

        $korisnik = ORM::for_table("korisnik")->where('username', $app['session']->get('user'))->find_one();

        if((empty($korisnik)) || ($parameters['naziv'] == null) || ($parameters['autocomplete_places'] == null) ||
            ($parameters['tip'] == null) || ($parameters['latitude'] == null) || ($parameters['longitude'] == null)){
            return "Error in input";

        }else{
            $tvrtka = ORM::for_table('tvrtka')->where('idTvrtke', $parameters['id'])->find_one();
            $tvrtka->set(array(
                'naziv' => $parameters['naziv'],
                'tip' => $parameters['tip'],
                'adresa' => $parameters['autocomplete_places'],
                'latitude' => $parameters['latitude'],
                'longitude' => $parameters['longitude'],
                'web' => $parameters['web']
            ));
            if($tvrtka->save()){
                return $app->redirect('profil');
            }else{
                return "Error while saving";
            }
        }
    });



    $app->run();
/*napraivt čemo authorize funkciju tako da ne trebmao svaki puta pozivat bazu i u svakoj stranici gledat da li je to user ili ne*/




}catch (Exception $e){
    echo $e->getMessage();
}