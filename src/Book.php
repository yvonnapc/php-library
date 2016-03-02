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
			$GLOBALS['DB']->exec("DELETE FROM authors_books");
		}

		static function find($search_id)
		{
			$found_book = null;
			$books = Book::getAll();
			foreach($books as $book){
				$book_id = $book->getId();
				if ($book_id == $search_id)
					{
						$found_book = $book;
					}
			} return $found_book;
		}

		function addAuthor($author_id)
		{
			$GLOBALS['DB']->exec(
				"INSERT INTO authors_books
				(author_id, book_id)
				VALUES (
					{$author_id},
					{$this->getId()}
				)"
			);
		}

		function getAuthors()
		{
			$query = $GLOBALS['DB']->query(
				"SELECT authors.*
				 FROM books
				 JOIN authors_books ON (books.id = authors_books.book_id)
				 JOIN authors ON (authors_books.author_id = authors.id)
				 WHERE books.id = {$this->getId()}"
			);
			$authors = [];
			foreach($query as $author)
			{
				$name = $author['name'];
				$id = $author['id'];
				$new_author = new Author($name, $id);
				$authors[] = $new_author;
			}
			return $authors;
		}

		function delete()
		{
			// $GLOBALS['DB']->exec("DELETE FROM books WHERE id = {$this->getId()};");
		}

	}
 ?>
