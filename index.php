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

        if($app['session']->get('lang') == null){
            $app['session']->set('lang', "hrv");
        }
        if($app['session']->get('lang') == "hrv"){
            include_once("jezici/hrv.php");
        }elseif($app['session']->get('lang') == "eng"){
            include_once("jezici/eng.php");
        }


        include_once('logic/idiormUse.php');

        $tipovi = ORM::for_table('tipTvrtke')
            ->select('tipTvrtke.*')
            ->find_array();

        return $app['twig']->render('main_site.twig', array('tipovi' => $tipovi, 'header' => $header, 'footer' => $footer, 'main' => $main));

    });

    $app->get('/lang ={lang}', function($lang, Request $request) use($app){
        $app['session']->set('lang', $lang);
        return $app->redirect($request->server->getHeaders()['REFERER']);
    });


	$app->get('/registracija', function() use($app){

        if($app['session']->get('lang') == null){
            $app['session']->set('lang', "hrv");
        }
        if($app['session']->get('lang') == "hrv"){
            include_once("jezici/hrv.php");
        }elseif($app['session']->get('lang') == "eng"){
            include_once("jezici/eng.php");
        }

        return $app['twig']->render('registracija.twig', array('header' => $header, 'footer' => $footer, 'registracija' => $registracija));
    });

    $app->get('/prijava', function() use($app){

        if($app['session']->get('lang') == null){
            $app['session']->set('lang', "hrv");
        }
        if($app['session']->get('lang') == "hrv"){
            include_once("jezici/hrv.php");
        }elseif($app['session']->get('lang') == "eng"){
            include_once("jezici/eng.php");
        }

        return $app['twig']->render('prijava.twig', array('header' => $header, 'footer' => $footer, 'prijava' => $prijava));

    });
	

	$app->get('/postavke', function() use($app){

        if($app['session']->get('lang') == null){
            $app['session']->set('lang', "hrv");
        }
        if($app['session']->get('lang') == "hrv"){
            include_once("jezici/hrv.php");
        }elseif($app['session']->get('lang') == "eng"){
            include_once("jezici/eng.php");
        }


	include_once('logic/idiormUse.php');
	
		$korisnik=ORM::for_table('korisnik')->where('isAdmin',0)->find_many();
		

        return $app['twig']->render('postavke.twig',array('korisnik' => $korisnik, 'header' => $header, 'footer' => $footer, 'postavke' => $postavke));

    });

    // POST ruta za login
	// obradi login podatke i postavi sesiju
	 $app->post('/prijava', function (Request $request) use ($app) {
	 
	 include_once('logic/idiormUse.php');
	 
	 
	    $parameters = $request->request->all();
		$korisnik = ORM::for_table('korisnik')->where(array('username' => $parameters['name']))->find_one();
		if($korisnik){
		$pw=$korisnik->get('password');
		
		if(password_verify($parameters['password'],$pw)){
		
  
		// provjera za ulogiranost - postavi sesiju i redirektaj na osnovnu stranicu
		
			 $app['session']->set('user', array('username' => $parameters['name'],'isAdmin' => $korisnik->get('isAdmin')));
	
			return $app->redirect('main');
		}
		else return $app->redirect('prijava');
		
		}
		
		else return $app->redirect('prijava');

    });

    // GET ruta za logout
    // izlogira (brise podatke iz sesije) i vraća na login ekran
    $app->get('/logout',  function () use ($app) {
        $app['session']->remove('user');

        return $app->redirect('prijava');
    });

    // Provjerava da li vec postoji korisnik sa tim korisnickim imenom ili emailom, te provjerava da li je korisnicko ime vece od 4 znaka te password veci od 6 znakova,
// na kraju upisuje u bazu ako su oba passworda ista
    $app->post('/registracija', function (Request $request) use ($app) {


        include_once('logic/idiormUse.php');

        $parameters = $request->request->all();
        if( ! ($provjera = ORM::for_table('korisnik')
            ->where_any_is(array(
                array('username' => $parameters['name']),
                array('email' => $parameters['email'])))
            ->find_many())){
			if(filter_var($parameters['email'], FILTER_VALIDATE_EMAIL)) {
        if (strlen($parameters['name']) >= 4){
		if (strlen($parameters['password']) >= 6){
    

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
			}
			
						else return $app->redirect('registracija');

			}

            else {
			
			
			return $app->redirect('registracija');
			}
        }
        else return $app->redirect('registracija');
    });

    /*edit: salji id iz maina */
    $app->get('/info={id}' , function($id) use ($app) {

        if($app['session']->get('lang') == null){
            $app['session']->set('lang', "hrv");
        }
        if($app['session']->get('lang') == "hrv"){
            include_once("jezici/hrv.php");
        }elseif($app['session']->get('lang') == "eng"){
            include_once("jezici/eng.php");
        }

        include_once('logic/idiormUse.php');

        $tvrtka = ORM::for_table('tvrtka')->where('id',$id)->find_one();
        $naziv = $tvrtka->get('naziv');
        $adresa = $tvrtka->get('adresa');
        $rating = $tvrtka->get('rating');
        $kolicina = $tvrtka->get('kolPopusta');
        $vrijeme = $tvrtka->get('vrijemePopusta');
        $web = $tvrtka->get('web');
        $reg = $tvrtka->get('datumPrijave');

        $komentari = ORM::for_table('komentari')->where('idTvrtke',$id)->find_many();


        //provjeravamo da li je registrirani korisnik
        if($app['session']->get('user') != null)
        {
            //$korisnik = ORM::for_table('korisnik')->select('id')->where('username',$app['session']->get('user')['username'])->find_one();
            //$isUser = $korisnik->get('id') == $id;
            $isUser = true;
        }else{
            $isUser = false;
        }
        if(isset($_SESSION['anonymous'])){
            //$antispamming = ORM::for_table('antispamming')->where('cookie', $_SESSION['anonymous'])->find_one();
            $antispamming = ORM::for_table('antispamming')->where(array('cookie' => $_SESSION['anonymous'], 'idTvrtke' => $id))->find_one();
            if($antispamming == false){
                $antispamming = array("rated" => false, "commented" => false);
            }
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
            'antispamming' => $antispamming,
            'web' => $web,
            'reg' => $reg,
            'header' => $header,
            'footer' => $footer,
            'info' => $info
        ));
    });
	
	
	$app->post('/postavke', function (Request $request) use ($app) {


    include_once('logic/idiormUse.php');

    $parameters = $request->request->all();
    $korisnik = ORM::for_table('korisnik')->find_one($parameters['haha']);
    $korisnik->delete();

    return $app->redirect('postavke');
    });

    $app->get('/profil', function() use ($app){

        if($app['session']->get('lang') == null){
            $app['session']->set('lang', "hrv");
        }
        if($app['session']->get('lang') == "hrv"){
            include_once("jezici/hrv.php");
        }elseif($app['session']->get('lang') == "eng"){
            include_once("jezici/eng.php");
        }

        include_once('logic/idiormUse.php');
        $arrayUser = $app['session']->get('user');

        $korisnik = ORM::for_table("korisnik")->where('username', $app['session']->get('user')['username'])->find_one();
        $tvrtke = ORM::for_table("tvrtka")->where('idKorisnika', $korisnik->get("id"))->find_many();

        return $app['twig']->render
            ('profil.twig', array('korisnik' => $korisnik, 'tvrtke' => $tvrtke, "header" => $header, "footer" => $footer, "profil" => $profil ));

    });

    $app->get('/dodajFirmu', function () use ($app){

        if($app['session']->get('lang') == null){
            $app['session']->set('lang', "hrv");
        }
        if($app['session']->get('lang') == "hrv"){
            include_once("jezici/hrv.php");
        }elseif($app['session']->get('lang') == "eng"){
            include_once("jezici/eng.php");
        }


        include_once('logic/idiormUse.php');

        if($korisnik = ORM::for_table("korisnik")->where('username', $app['session']->get('user')['username'])->find_one()){
            $tipovi = ORM::for_table("tipTvrtke")->find_many();
            return $app['twig']->render
                ('dodajFirmuForma.twig', array('tipovi' => $tipovi, 'header' => $header, 'footer' => $footer, 'firma' => $dodajFirmu));
        }else
            return $app->redirect('login');
    });

    $app->post('/addComp', function (Request $request) use ($app){
        $parameters = $request->request->all();

        include_once('logic/idiormUse.php');

        $korisnik = ORM::for_table("korisnik")->where('username', $app['session']->get('user')['username'])->find_one();

        if((empty($korisnik)) || ($parameters['naziv'] == null) || ($parameters['autocomplete_places'] == null) ||
            ($parameters['tip'] == null) || ($parameters['latitude'] == null) || ($parameters['longitude'] == null)){
            return "Error in input";

        }else{
            $tvrtka = ORM::for_table("tvrtka")->create();

            $tvrtka->idKorisnika = $korisnik->get("id");
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

        if($app['session']->get('lang') == null){
            $app['session']->set('lang', "hrv");
        }
        if($app['session']->get('lang') == "hrv"){
            include_once("jezici/hrv.php");
        }elseif($app['session']->get('lang') == "eng"){
            include_once("jezici/eng.php");
        }

        include_once('logic/idiormUse.php');
        $korisnik = ORM::for_table("korisnik")->where('username', $app['session']->get('user')['username'])->find_one();

        return $app['twig']->
            render('edit_profile.twig', array('korisnik' => $korisnik, 'header' => $header, 'footer' => $footer, 'uredi' => $editUser));
    });

    $app->post('/edit_profile_info', function (Request $request) use ($app){
        include_once('logic/idiormUse.php');
        $parameters = $request->request->all();
        $korisnik = ORM::for_table('korisnik')->where('username',$app['session']->get('user')['username'])->find_one();

        // ako je upisan novi password

        if(($parameters['password_new'] != "") || ($parameters['password_new'] != null)){


            if(password_verify($parameters['password_old'],$korisnik->get("password"))){

                if($provjera = ORM::for_table('korisnik')->where('username',$parameters['username'])->find_one()){

                    //ako je korisnicko ime isto kao i ulogirano  ( sto znaci da ime ostaje isto te moze promjeniti email

                    if($parameters['username'] == $app['session']->get('user')['username']){
                        $app['session']->remove('user');
                        $korisnik->set(array(
                            'username' => $parameters['username'],
                            'password' => password_hash($parameters['password_new'], PASSWORD_DEFAULT),
                            'email' => $parameters['email']
                        ));
                        $korisnik->save();
                        $app['session']->set('user', array('username' => $parameters['username'], 'isAdmin' => false));
                        return $app->redirect('profil');

                    }

                    // ako korisnik postoji u bazi ali nije isti kao i ulogirani ( sto znaci da je ime postavio kao neki postojeci korisnik te ne moze promjeniti podatke)


                    else

                        return "Korisnicko ime vec postoji";

                }

                // sasvim novo korisnicko ime koje ne postoji u bazi ! :)

                else {

                    $app['session']->remove('user');

                    $korisnik->set(array(
                        'username' => $parameters['username'],
                        'password' => password_hash($parameters['password_new'], PASSWORD_DEFAULT),
                        'email' => $parameters['email']
                    ));
                    $korisnik->save();
                    $app['session']->set('user', array('username' => $parameters['username'], 'isAdmin' => false));
                    return $app->redirect('profil');

                }
            }else{
                return "wrong passwords";
            }
        }


        // ako postoji korisnik sa istim imenom u bazi
        else if($provjera = ORM::for_table('korisnik')->where('username',$parameters['username'])->find_one()){

            //ako je korisnicko ime isto kao i ulogirano  ( sto znaci da ime ostaje isto te moze promjeniti email

            if($parameters['username'] == $app['session']->get('user')['username']){

                $app['session']->remove('user');

                $korisnik->set(array(
                    'username' => $parameters['username'],
                    'email' => $parameters['email']
                ));
                $korisnik->save();
                $app['session']->set('user', array('username' => $parameters['username'], 'isAdmin' => false));
                return $app->redirect('profil');

            }

            // ako korisnik postoji u bazi ali nije isti kao i ulogirani ( sto znaci da je ime postavio kao neki postojeci korisnik te ne moze promjeniti podatke)


            else

                return "Korisnicko ime vec postoji";

        }

        // sasvim novo korisnicko ime koje ne postoji u bazi ! :)

        else {

            $app['session']->remove('user');

            $korisnik->set(array(
                'username' => $parameters['username'],
                'email' => $parameters['email']
            ));
            $korisnik->save();
            $app['session']->set('user', array('username' => $parameters['username'], 'isAdmin' => false));
            return $app->redirect('profil');

        }
    });

    $app->get('/urediTvrtku={idTvrtke}', function ($idTvrtke) use ($app) {

        if($app['session']->get('lang') == null){
            $app['session']->set('lang', "hrv");
        }
        if($app['session']->get('lang') == "hrv"){
            include_once("jezici/hrv.php");
        }elseif($app['session']->get('lang') == "eng"){
            include_once("jezici/eng.php");
        }

        include_once('logic/idiormUse.php');
        $tvrtka = ORM::for_table('tvrtka')->where('id', $idTvrtke)->find_one();
        $tipovi = ORM::for_table("tipTvrtke")->find_many();

        return $app['twig']->render
            ('edit_tvrtke.twig', array('tvrtka' => $tvrtka, 'tipovi' => $tipovi, 'header' => $header, 'footer' => $footer, 'uredi' => $uredi_tvrtku));
    });

    $app->post('/uredi firmu', function(Request $request) use ($app){
        $parameters = $request->request->all();

        include_once('logic/idiormUse.php');

        $korisnik = ORM::for_table("korisnik")->where('username', $app['session']->get('user')['username'])->find_one();

        if((empty($korisnik)) || ($parameters['naziv'] == null) || ($parameters['autocomplete_places'] == null) ||
            ($parameters['tip'] == null) || ($parameters['latitude'] == null) || ($parameters['longitude'] == null)){
            return "Error in input";

        }else{
            $tvrtka = ORM::for_table('tvrtka')->where('id', $parameters['id'])->find_one();
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

}catch (Exception $e){
    echo $e->getMessage();
}