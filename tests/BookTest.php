<?php

	/**
	* @backupGlobals disabled
	* @backupStaticAttributes disabled
	*/

	$server = "mysql:host=localhost;dbname=library_test";
	$username = "root";
	$password = "root";
	$DB = new PDO($server, $username, $password);

	class BookTest extends PHPUnit_Framework_TestCase
	{
		protected function tearDown()
		{
			Book::deleteAll();
			Author::deleteAll();
			Patron::deleteAll();
		}

		function test_save()
		{
			//Arrange
			$title = "Cathedral";
			$test_book = new Book($title);
			$test_book->save();

			//Act
			$result = Book::getAll();

			//Assert
			$this->assertEquals([$test_book], $result);
		}
		function test_getAll()
		{
			//Arrange
			$title = "Cathedral";
			$test_book = new Book($title);
			$test_book->save();

			$title = "Moby Dick";
			$test_book2 = new Book($title);
			$test_book2->save();

			//Act
			$result = Book::getAll();

			//Assert
			$this->assertEquals([$test_book, $test_book2], $result);
		}

		function test_deleteAll()
		{
			//Arrange
			$title = "Cathedral";
			$test_book = new Book($title);
			$test_book->save();

			$title = "Moby Dick";
			$test_book2 = new Book($title);
			$test_book2->save();

			//Act
			Book::deleteAll();
			$result = Book::getAll();

			//Assert
			$this->assertEquals([], $result);
		}

		function test_find()
		{
			//Arrange
			$title = "Cathedral";
			$test_book = new Book($title);
			$test_book->save();

			$title = "Moby Dick";
			$test_book2 = new Book($title);
			$test_book2->save();

			//Act
			$result = Book::find($test_book2->getId());

			//Assert
			$this->assertEquals($test_book2, $result);
		}

		function test_addAuthor_getAuthors()
		{
			//Arrange
			$title = "Cathedral";
			$test_book = new Book($title);
			$test_book->save();

			$name = "Jason Awbrey";
			$test_author = new Author($name);
			$test_author->save();

			//Act
			$test_book->addAuthor($test_author->getId());
			$result = $test_book->getAuthors();

			//Assert
			$this->assertEquals("Jason Awbrey", $result[0]->getName());
		}

		function test_update()
		{
			//Arrange
			$title = "Cathedral";
			$test_book = new Book($title);
			$test_book->save();

			$new_title = "Not Cathedral";

			//Act
			$test_book->update($new_title);

			//Assert
			$this->assertEquals("Not Cathedral", $test_book->getTitle());
		}

		function test_getAvailable()
		{
			//Arrange
			$title = "Cathedral";
			$test_book = new Book($title);
			$test_book->save();


			//Act
			$test_book->receiveGoods();
			$test_book->receiveGoods();
			$result = $test_book->getAvailable();

			//Assert
			$this->assertEquals(2, $result);
		}

		function test_receiveGoods()
		{
			//Arrange
			$title = "Cathedral";
			$test_book = new Book($title);
			$test_book->save();

			//Act
			$test_book->receiveGoods();
			$result = $test_book->getAvailable();

			//Assert
			$this->assertEquals(1, $result);
		}

		function test_checkout()
		{
			//Arrange
			$title = "Moby Dick";
			$test_book = new Book($title);
			$test_book->save();

			$name = "Jason Awbrey";
			$test_author = new Author($name);
			$test_author->save();

			$test_book->addAuthor($test_author->getId());
			$test_book->receiveGoods();

			$name = "Jason Awbrey";
			$test_patron = new Patron($name);
			$test_patron->save();

			//Act
			$test_book->checkout("2016-03-02", $test_patron->getId());
			$result = $test_patron->checkoutHistory();



			//Assert
			$this->assertEquals([['title' =>'Moby Dick','checkout_date' =>'2016-03-02']], $result);
		}

		function test_searchByAuthor()
		{
			//Arrange
			$title = "Moby Dick";
			$test_book = new Book($title);
			$test_book->save();

			$title = "Cathedral";
			$test_book2 = new Book($title);
			$test_book2->save();

			$name = "Jason Awbrey";
			$test_author = new Author($name);
			$test_author->save();

			$test_book->addAuthor($test_author->getId());
			$test_book2->addAuthor($test_author->getId());

			$search_term = "aso";

			//Act
			$result = Book::searchByAuthor("%$search_term%");

			//Assert
			$this->assertEquals([$test_book, $test_book2], $result);
		}

		function test_searchByTitle()
		{
			//Arrange
			$title = "Moby Dick";
			$test_book = new Book($title);
			$test_book->save();

			$title = "Cathedral";
			$test_book2 = new Book($title);
			$test_book2->save();

			$search_term = "ath";

			//Act
			$result = Book::searchByTitle("%$search_term%");

			//Assert
			$this->assertEquals([$test_book2], $result);

		}

		function test_delete()
		{
			//Arrange
			$title = "Cathedral";
			$test_book = new Book($title);
			$test_book->save();

			//Act
			$test_book->delete();
			$result = Book::getAll();

			//Assert
			$this->assertEquals([], $result);
		}

		function test_deleteCopies()
		{
			//Arrange
			$title = "Cathedral";
			$test_book = new Book($title);
			$test_book->save();

			$test_book->receiveGoods();

			//Act
			$test_book->delete();
			$result = $test_book->getAvailable();

			//Assert
			$this->assertEquals(0, $result);

		}


	}

?>
