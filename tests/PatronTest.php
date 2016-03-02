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
	}

?>
