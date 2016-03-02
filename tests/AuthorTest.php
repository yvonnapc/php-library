<?php

	/**
	* @backupGlobals disabled
	* @backupStaticAttributes disabled
	*/

	$server = "mysql:host=localhost;dbname=library_test";
	$username = "root";
	$password = "root";
	$DB = new PDO($server, $username, $password);

	class AuthorTest extends PHPUnit_Framework_TestCase
	{
		protected function tearDown()
		{
			Book::deleteAll();
			Author::deleteAll();
			Author::deleteAll();
		}

		function test_save()
		{
			//Arrange
			$name = "Jason Awbrey";
			$test_author = new Author($name);
			$test_author->save();

			//Act
			$result = Author::getAll();

			//Assert
			$this->assertEquals([$test_author], $result);
		}
		function test_getAll()
		{
			//Arrange
			$name = "Jason Awbrey";
			$test_author = new Author($name);
			$test_author->save();

			$name = "Yvonna Contreras";
			$test_author2 = new Author($name);
			$test_author2->save();

			//Act
			$result = Author::getAll();

			//Assert
			$this->assertEquals([$test_author, $test_author2], $result);
		}

		function test_deleteAll()
		{
			//Arrange
			$name = "Jason Awbrey";
			$test_author = new Author($name);
			$test_author->save();

			$name = "Yvonna Contreras";
			$test_author2 = new Author($name);
			$test_author2->save();

			//Act
			Author::deleteAll();
			$result = Author::getAll();

			//Assert
			$this->assertEquals([], $result);
		}

		function test_find()
		{
			//Arrange
			$name = "Jason Awbrey";
			$test_author = new Author($name);
			$test_author->save();

			//Act
			$result = Author::find($test_author->getId());

			//Assert
			$this->assertEquals([$test_author], $result);
		}
	}

?>
