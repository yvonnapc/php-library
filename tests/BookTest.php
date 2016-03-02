<?php

	require_once 'src/Book.php';

	class BookTest extends PHPUnit_Framework_TestCase
	{
		function test_getTitle()
		{
		//Arrange
		$title = "Cathedral";
		$test_book = new Book($title, $author);
		//Act
		$result = $test_book->getTitle($title);
		//Assert
		$this->assertEquals('Cathedral', $result);
		}
	}

?>
