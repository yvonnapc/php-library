CREATE DATABASE library;

USE library;

CREATE TABLE books (id serial PRIMARY KEY, title VARCHAR (255));

CREATE TABLE authors (id serial PRIMARY KEY, name VARCHAR(255));

CREATE TABLE checkouts (id serial PRIMARY KEY, book_id int, copy_id int, patron_id int, checkout_date DATE);

CREATE TABLE patrons (id serial PRIMARY KEY, name VARCHAR(255));
Query OK, 0 rows affected (0.07 sec)

CREATE TABLE copies (id serial PRIMARY KEY, book_id int, checked_out BOOLEAN);

CREATE TABLE authors_books (id serial PRIMARY KEY, author_id int, book_id int);


SELECT a.name as "The Author"
FROM authors a
JOIN authors_books ab ON a.id = ab.author_id
WHERE ab.book_id = 10;

DELETE authors, authors_books
FROM authors
INNER JOIN authors_books ON (authors.id = authors_books.author_id)
WHERE authors.id = 50;





    SELECT authors.*
     FROM books
     JOIN authors_books ON (books.id = authors_books.book_id)
     JOIN authors ON authors_books.author_id = authors.id
     WHERE books.id = 293;
