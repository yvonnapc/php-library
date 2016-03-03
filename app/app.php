<?php

	require_once __DIR__.'/../vendor/autoload.php';

	$app = new Silex\Application();

	$server = 'mysql:host=localhost;dbname=library';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

	$app->register(new Silex\Provider\TwigServiceProvider(), array('twig.path' => __DIR__.'/../views'));

	use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();


  $app->get("/", function() use($app){
    return $app['twig']->render('index.html.twig');
  });



	// For Checking A Book Out
	// $app->post('/catalog/{pid}/{bid}', function($pid, $bid) use($app){
	//
	// 	$book = Book::find($bid);
	// 	$book->checkout($_POST['checkout_date'], $pid);
	// 	return $app['twig']->render('index.html.twig');
	// });

	return $app;

?>
