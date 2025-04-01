<?php

namespace Ipoo\Tps\Tp1\Ej16;

require_once __DIR__ . '/../../../autoload.php';

$book1 = new Book([
    'isbn' => '978-3-16-148410-0',
    'title' => 'The Great Book',
    'authorName' => 'John',
    'authorSurname' => 'Doe',
    'publisher' => 'Great Publisher',
    'year' => 2020,
]);

$book2 = new Book([
    'isbn' => '978-3-16-148410-1',
    'title' => 'Another Great Book',
    'authorName' => 'Jane',
    'authorSurname' => 'Smith',
    'publisher' => 'Another Publisher',
    'year' => 2018,
]);

$book3 = new Book([
    'isbn' => '978-3-16-148410-2',
    'title' => 'Yet Another Great Book',
    'authorName' => 'Alice',
    'authorSurname' => 'Johnson',
    'publisher' => 'Yet Another Publisher',
    'year' => 2021,
]);

$book4 = new Book([
    'isbn' => '978-3-16-148410-0',
    'title' => 'The Great Book',
    'authorName' => 'John',
    'authorSurname' => 'Doe',
    'publisher' => 'Great Publisher',
    'year' => 2020,
]);

$library = [
    $book1,
    $book2,
    $book3,
];

$publisher = 'Great Publisher';

echo $book1->belongsToPublisher($publisher) ? "Book 1 belongs to $publisher\n" : "Book 1 does not belong to $publisher\n";
echo $book2->belongsToPublisher($publisher) ? "Book 2 belongs to $publisher\n" : "Book 2 does not belong to $publisher\n";
echo $book3->belongsToPublisher($publisher) ? "Book 3 belongs to $publisher\n" : "Book 3 does not belong to $publisher\n";
echo $book4->belongsToPublisher($publisher) ? "Book 4 belongs to $publisher\n" : "Book 4 does not belong to $publisher\n";

echo "Years since publication for Book 1: " . $book1->yearsSincePublication() . "\n";
echo "Years since publication for Book 2: " . $book2->yearsSincePublication() . "\n";
echo "Years since publication for Book 3: " . $book3->yearsSincePublication() . "\n";
echo "Years since publication for Book 4: " . $book4->yearsSincePublication() . "\n";

echo $book1->isEqualTo($book4) ? "Book 1 is equal to Book 4\n" : "Book 1 is not equal to Book 4\n";

var_dump(booksByPublisher($library, $publisher));

/**
 * @param Book[] $library
 * @param string $publisher
 * 
 * @return Book[]
 */
function booksByPublisher(array $library, string $publisher): array
{
    $books = [];
    foreach ($library as $book) {
        if ($book->belongsToPublisher($publisher)) {
            $books[$book->isbn] = $book;
        }
    }

    return $books;
}
