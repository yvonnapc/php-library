<?php

	require_once __DIR__.'/../vendor/autoload.php';

	$app = new Silex\Application();

	$server = 'mysql:host=localhost;dbname=library';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

	$app->register(new Silex\Provider\TwigServiceProvider(), array('twig.path' => __DIR__.'/../views'));
	//$app['debug'] = TRUE;

	use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();


  $app->get("/", function() use($app){
    return $app['twig']->render('index.html.twig');
  });

	$app->get("/librarian/catalog/search", function() use($app){
		return $app['twig']->render('search.html.twig', array('librarian' => True));
	});

	$app->get("/librarian/catalog", function() use($app){
		$books = Book::getAll();
		return $app['twig']->render('catalog.html.twig', array('books' => $books, 'librarian' => True));
	});

	$app->post("/librarian/catalog/search", function() use($app){
		$book = new Book($_POST['title']);
		$book->save();
		$author = new Author($_POST['author']);
		$author->save();
		$book->addAuthor($author->getId());
		return $app['twig']->render('search.html.twig', array('librarian' => True));
	});

	$app->get("/librarian/catalog/{bid}", function($bid) use($app){
		$book = Book::find($bid);
		$authors = $book->getAuthors();
		return $app['twig']->render('book.html.twig', array('book' => $book, 'authors' => $authors, 'librarian' => TRUE));
	});

	$app->post("/librarian/catalog/{bid}", function($bid) use($app){
		$book = Book::find($bid);
		$number_of_copies_received = $_POST['num_copies'];
		for($i = 1; $i<=$number_of_copies_received; $i++)
		{
			$book->receiveGoods();
		}
		$authors = $book->getAuthors();
		return $app['twig']->render('book.html.twig', array('book' => $book, 'authors' => $authors, 'librarian' => TRUE));
	});

	$app->post("/librarian/catalog/add_author/{bid}", function($bid) use($app){
		$author_name = $_POST['add_author'];
		$new_author = new Author($author_name);
		$new_author->save();
		$book = Book::find($bid);
		$book->addAuthor($new_author->getId());
		$authors = $book->getAuthors();
		return $app['twig']->render('book.html.twig', array('book' => $book, 'authors' => $authors, 'librarian' => TRUE));
	});

	$app->patch("/librarian/catalog/{bid}", function($bid) use($app){
		$book = Book::find($bid);
		$book->update($_POST['title']);
		return $app['twig']->render('book.html.twig', array('book' => $book, 'librarian' => TRUE));
	});

	$app->get("/librarian/catalog/search_by_author", function() use($app){
		$search_term = $_GET['author'];
		$books = Book::searchByAuthor("%$search_term%");
		return $app['twig']->render('catalog.html.twig', array('books' => $books, 'librarian' => True));
	});

	$app->get("/librarian/catalog/search_by_title", function() use($app){
		$search_term = $_GET['title'];
		$books = Book::searchByTitle("%$search_term%");
		return $app['twig']->render('catalog.html.twig', array('books' => $books, 'librarian' => True));
	});

	$app->delete("/librarian/catalog/search", function() use ($app)
	{
		$book = Book::find($_POST['book_id']);
		$book->delete();
		return $app['twig']->render('search.html.twig', array('librarian' => True));
	});
/*


											Patron Routes


*/
	$app->get("/patrons", function() use($app){
		return $app['twig']->render('patrons.html.twig', array('patrons' => Patron::getAll()));
	});

	$app->post("/patrons", function() use($app){
		$patron = new Patron($_POST['name']);
		$patron->save();
		return $app['twig']->render('patrons.html.twig', array('patrons' => Patron::getAll()));
	});

	$app->get("/patron/{pid}/catalog/search", function($pid) use($app){
		$patron = Patron::find($pid);
		$checkout_history = $patron->checkoutHistory();
		return $app['twig']->render('search.html.twig', array('patron' => $patron, 'checkouts' => $checkout_history));
	});

	$app->get("/patron/{pid}/catalog", function($pid) use ($app)
	{
		$patron = Patron::find($pid);
		$books = Book::getAll();
		return $app['twig']->render('catalog.html.twig', array('patron' => $patron, 'books' => $books));
	});

	$app->get("/patron/{pid}/catalog/search_by_title", function($pid) use($app){
		$search_term = $_GET['title'];
		$patron = Patron::find($pid);
		$books = Book::searchByTitle("%$search_term%");
		return $app['twig']->render('catalog.html.twig', array('patron' => $patron, 'books' => $books));
	});

	$app->get("/patron/{pid}/catalog/search_by_author", function($pid) use($app){
		$search_term = $_GET['author'];
		$patron = Patron::find($pid);
		$books = Book::searchByAuthor("%$search_term%");
		return $app['twig']->render('catalog.html.twig', array('patron' => $patron, 'books' => $books));
	});

	$app->get("/patron/{pid}/catalog/{bid}", function($pid, $bid) use($app){
		$book = Book::find($bid);
		$authors = $book->getAuthors();
		$patron = Patron::find($pid);
		return $app['twig']->render('book.html.twig', array('patron' => $patron, 'book' => $book, 'authors' => $authors));
	});

	$app->post("/patron/{pid}/catalog/{bid}/checkout", function($pid, $bid) use($app){
		$checkout_date = date("Y-m-d");
		$due_date = strtotime(date("Y-m-d", strtotime($checkout_date)) . " +1 month");
		$due_date = date("Y-m-d",$due_date);
		$book = Book::find($bid);
		$book->checkout($checkout_date, $pid);
		$patron = Patron::find($pid);
		return $app['twig']->render('search.html.twig', array('patron' => $patron));
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
