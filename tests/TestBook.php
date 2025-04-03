<?php

namespace Ipoo\Tests;

require_once __DIR__ . '/../autoload.php';

$bookObj = new Book();

// Get all books stored
$books = $bookObj->get();

// Get only some fields
$books = $bookObj->select('title')->get();

$books = $bookObj->select(['title', 'title'])->get();

// Get books filtered by field
$books = $bookObj->where('title', '=', "Book Title")->get();
// or
$books = $bookObj->where('title', 'Book Title')->get();

// Filter by multiple fields
$books = $bookObj->where('title', 'Book Title')->where('author_name', '<>', 'Author Name')->where('author_surname', 'like', '%a%')->get();

// Get last stored book
$lastBook = $bookObj->last();
