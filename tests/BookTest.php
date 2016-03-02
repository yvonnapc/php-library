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

		function test_addAuthors_getAuthor()
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

	}

?>
