<?php
	 class Book
		{
		private $title;
		private $id;

		function __construct($title, $id = NULL)
		{
			$this->title = $title;
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

		function getId()
		{
			return $this->id;
		}

		function save()
		{

		}

		static function getAll()
		{

		}

		static function deleteAll()
		{

		}
	}
 ?>
