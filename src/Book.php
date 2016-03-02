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
			$GLOBALS['DB']->exec(
			"INSERT INTO books
			(title)
			VALUES
			('{$this->getTitle()}')"
		);
			$this->id = $GLOBALS['DB']->lastInsertId();
		}

		static function getAll()
		{
			$returned_books = $GLOBALS['DB']->query("SELECT * FROM books;");
            $books = array();
            foreach($returned_books as $book) {
                $title = $book['title'];
                $id = $book['id'];
                $new_book = new Book($title, $id);
                array_push($books, $new_book);
            }
            return $books;
		}

		static function deleteAll()
		{
			$GLOBALS['DB']->exec("DELETE FROM books");
		}
	}
 ?>
