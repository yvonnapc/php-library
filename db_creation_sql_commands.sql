CREATE DATABASE library;

USE library;

CREATE TABLE book (id serial PRIMARY KEY, title VARCHAR (255));

CREATE TABLE authors (id serial PRIMARY KEY, name VARCHAR(255));

CREATE TABLE checkouts (id serial PRIMARY KEY, book_id int, copy_id int, patron_id int, checkout_date DATE);

CREATE TABLE patrons (id serial PRIMARY KEY, name VARCHAR(255));
Query OK, 0 rows affected (0.07 sec)

CREATE TABLE copies (id serial PRIMARY KEY, book_id int, checked_out BOOLEAN);

CREATE TABLE authors_books (id serial PRIMARY KEY, author_id int, book_id int);
