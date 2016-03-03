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

		function receiveGoods()
		{
			$GLOBALS['DB']->exec(
				"INSERT INTO copies
				 (book_id, checked_out)
				 VALUES
				 ({$this->getId()}, 0)"
			);
		}

		function getAvailable()
		{
			$query = $GLOBALS['DB']->query(
				"SELECT COUNT(*) as 'available'
				FROM copies
				WHERE book_id = {$this->getId()} and checked_out = 0;"
			);
			$result = $query->fetchAll(PDO::FETCH_ASSOC);

			return $result[0]['available'];
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
			$GLOBALS['DB']->exec("DELETE FROM books;");
			$GLOBALS['DB']->exec("DELETE FROM authors_books;");
			$GLOBALS['DB']->exec("DELETE FROM copies;");
			$GLOBALS['DB']->exec("DELETE FROM checkouts");
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

		function update($new_title)
		{
			$GLOBALS['DB']->exec("UPDATE books SET title = '{$new_title}' WHERE id = {$this->getId()};");
			$this->setTitle($new_title);
		}

		function checkout($checkout_date, $pid)
		{
			$query = $GLOBALS['DB']->query(
				"SELECT id
				FROM copies
				WHERE checked_out = 0
				AND book_id = {$this->getId()}
				LIMIT 1;"
			);

			$query_processed = $query->fetchAll(PDO::FETCH_ASSOC);
			$copy_id = $query_processed[0]['id'];

			$GLOBALS['DB']->query(
				"UPDATE copies
				SET checked_out = 1
				WHERE id = {$copy_id};"
			);

			$GLOBALS['DB']->exec(
				"INSERT INTO checkouts
				 (book_id, copy_id, patron_id, checkout_date)
				 VALUES ({$this->getId()}, {$copy_id}, {$pid},'{$checkout_date}');"
			);

		}

		static function searchByAuthor($search_term)
		{
			$returned_books = $GLOBALS['DB']->query(
				"SELECT books.*
				FROM authors
				JOIN authors_books ON authors.id = authors_books.author_id
				JOIN books ON authors_books.book_id = books.id
				WHERE authors.name LIKE '{$search_term}';"
			);

			$books = array();
			foreach($returned_books as $book) {
					$title = $book['title'];
					$id = $book['id'];
					$new_book = new Book($title, $id);
					array_push($books, $new_book);
			}
			return $books;
		}

	}
 ?>
