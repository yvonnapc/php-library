<?php
	 class Book
		{
		private $title;
		private $author;
		private $id;

		function __construct($title, $author, $id = NULL)
		{
			$this->title = $title;
			$this->author = $author;
			$this->id = $id;
		}

		function getTitle()
		{
			return $this->title;
		}

		function setTitle($title)
		{
			$this->title = $title;
		}

		function getAuthor()
		{
			return $this->author;
		}

		function setAuthor($author)
		{
			$this->author = $author;
		}

		function getId()
		{
			return $this->id;
		}
		
		function save()
		{

		}

		function getAll()
		{

		}

		function deleteAll()
		{

		}
	}
 ?>
