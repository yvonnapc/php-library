<?php

	/**
	* @backupGlobals disabled
	* @backupStaticAttributes disabled
	*/

	$server = "mysql:host=localhost;dbname=library_test";
	$username = "root";
	$password = "root";
	$DB = new PDO($server, $username, $password);

	class PatronTest extends PHPUnit_Framework_TestCase
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
		$name = "Jason Awbrey";
		$test_patron = new Patron($name);
		$test_patron->save();

		//Act
		$result = Patron::getAll();

		//Assert
		$this->assertEquals([$test_patron], $result);
		}

		function test_getAll()
		{
			//Arrange
			$name = "Jason Awbrey";
			$test_patron = new Patron($name);
			$test_patron->save();

			$name = "Yvonna Contreras";
			$test_patron2 = new Patron($name);
			$test_patron2->save();

			//Act
			$result = Patron::getAll();

			//Assert
			$this->assertEquals([$test_patron, $test_patron2], $result);

		}

		function test_deleteAll()
		{
			//Arrange
			$name = "Jason Awbrey";
			$test_patron = new Patron($name);
			$test_patron->save();

			$name = "Yvonna Contreras";
			$test_patron2 = new Patron($name);
			$test_patron2->save();

			//Act
			Patron::deleteAll();
			$result = Patron::getAll();

			//Assert
			$this->assertEquals([], $result);
		}

		function test_find()
		{
			//Arrange
			$name = "Cathedral";
			$test_patron = new Patron($name);
			$test_patron->save();

			$name = "Moby Dick";
			$test_patron2 = new Patron($name);
			$test_patron2->save();

			//Act
			$result = Patron::find($test_patron2->getId());

			//Assert
			$this->assertEquals($test_patron2, $result);
		}
		function test_delete_checkoutHistoryRemoved()
		{
			$title = "Cathedral";
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
			$test_patron->delete();
			$result = $test_patron->checkoutHistory();

			//Assert
			$this->assertEquals([], $result);

		}


		function test_delete()
		{
			//Arrange
			$name = "Yvonna Contreras";
			$test_patron = new Patron($name);
			$test_patron->save();

			$name = "Jason Awbrey";
			$test_patron2 = new Patron($name);
			$test_patron2->save();

			//Act
			$test_patron->delete();

			//Assert
			$this->assertEquals([$test_patron2], Patron::getAll());

		}

		function test_update()
		{
			//Arrange
			$name = "Yvonna Contreras";
			$test_patron = new Patron($name);
			$test_patron->save();

			$new_name = "Jason Awbrey";

			//Act
			$test_patron->update($new_name);

			//Assert
			$this->assertEquals("Jason Awbrey", $test_patron->getName());
		}

		function test_checkoutHistory()
		{
			//Arrange
			$title = "Cathedral";
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
			$this->assertEquals([['title' =>'Cathedral','checkout_date' =>'2016-03-02']], $result);
		}

	}

?>
