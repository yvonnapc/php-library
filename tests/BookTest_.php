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
	}

?>
