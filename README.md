[BaseClass basic usage](#baseclass-basic-usage)

- [Extending](#extending)
- [Creating instances of your class](#creating-instances-of-your-class)
- [Accessing your class properties](#accessing-your-class-properties)
- [Mutating your class properties](#mutating-your-class-properties)
- [Getting your class transformed](#getting-your-class-transformed)
  - [Getting string](#getting-string)
  - [Getting array](#getting-array)
- [Comparing objects](#comparing-objects)

---

[Usage with databases](#usage-with-databases)

- [Database connection](#database-connection)
- [Link classes to tables](#link-classes-to-tables)
- [Basic query operations](#basic-query-operations)
  - [get](#get)
  - [select](#select)
  - [where](#where)
  - [find](#find--findorfail)
  - [orderBy](#orderby)
  - [groupBy](#groupby)
  - [limit](#limit)
  - [first](#first)
  - [last](#last)
  - [insert](#insert)
  - [save](#save)
  - [update](#update)
- [Delete](#delete)
  - [Soft deletes](#soft-deletes)
- [Debugging](#debugging)

---

# BaseClass basic usage

## Extending

When using a class that will have all the base functionalities already set up, just extend the BaseClass like this:

```php
namespace Ipoo;

namespace Ipoo\Your\Namespace;

use Ipoo\Src\BaseClass;

class Book extends BaseClass {
    protected array $attributes = ['isbn', 'title', 'author', 'price'];

    protected array $hidden = ['isbn'];
}
```

> Note that the `$attributes` variable will store the properties of the class and the `$hidden` variable will store the properties that shouldn't be shown.

## Creating instances of your class

Here are some ways of creating instances for your class:

```php
// Constructor without arguments
$book = new Book();

// Constructor with properties
$book = new Book([
    'isbn' => 1,
    'title' => 'Book Title',
    'author' => 'Book Author',
    'price' => 10
]);
```

## Accessing your class properties

There are multiple ways of getting the values of your class:

```php
// Using __get() magic method
$title = $book->title; // 'Book Title'
```

You can also make your custom accessors following the convention:</br>
For the attribute `your_attribute_name`, its accessor should be called `getYourAttributeNameAttribute()`:

```php
public function getTitleAttribute(): string
{
    return $this->title;
}
```

You can even modify the returned value:

```php
public function getPriceAttribute(): string
{
    return "$" . $this->price;
}

$book->getPriceAttribute(); // '$10'
$book->get('price'); // '$10'
$book->get(['price']); // ['price' => '$10']
```

## Mutating your class properties

There are multiple ways of modifying the value of your class' attributes:

```php
// Setting the value of a single attribute using __set() magic method
$book->title = 'Another Title';

// Setting the value of a single attribute using fill() method
$book->fill('title', 'Another Title');

// Setting multiple attribute values using fill() method
$book->fill(['title' => 'Another Title']);
```

## Getting your class transformed

You can also get your class represented as a `string` or an `array`:

### Getting string

```php
// Using __toString() magic method
// If casting it as a string will automatically call __toString() magic method

$bookString = "$book";
echo $book;

// Using toString() method
$bookString = $book->toString();
```

By default, `__toString()` won't return hidden attributes. To get those attributes you must use `toString()` method with a flag set to true

```php
// Using toString() method with hidden attributes
$bookString = $book->toString(includeHidden: true);
```

### Getting array

```php
// Using toArray() method to get the class represented as an associative array
$bookArray = $book->toArray(); // ['title' => 'Book Title', ...]
```

By default, `toArray()` won't return hidden attributes. To get those attributes you must use this method with a flag set to true

```php
// Using toArray() method to get the class represented as an associative array with its hidden attributes
$bookArray = $book->toArray(includeHidden: true); // ['isbn' => 1,'title' => 'Book Title', ...]
```

## Comparing objects

There is a way to compare objects by defining the keys that will be used for the comparisson as those should be the keys that differenciate the objects:

```php
protected array $unique = ['isbn'];

$book2 = new Book([
    'isbn' => 2,
    'title' => 'Book Title',
    'author' => 'Book Author',
    'price' => 10
]);

$book->isEqualTo($book2); // false

$book2->fill('isbn', 1);

$book->isEqualTo($book2); // true
```

# Usage with databases

## Database connection

The only configuration needed to connect to your database is a `.env` file that contains the credentials. You will find a `.env.example` file that has all the secret keys needed so you just have to rename it or make a copy.

## Link classes to tables

Your classes can be asigned to a table in your database to interact with its data. To get your PDO going, the only requirement is adding the `$table` variable to your class and extend `BaseClass`:

```php
class Book extends BaseClass {
    protected string $table = 'books';
}
```

Just with that, you can start querying your database.

## Basic query operations

The following examples will use a variable called `$bookObj` that is simply defined as:

```php
$bookObj = new Book();
```

### get

The `get()` method will execute the generated query and return an array filled with instances of the class queried. If it is called alone, it will return all columns and all data without any filter.

```php
$books = $bookObj->get(); // SELECT * FROM books
```

> This method must always be called to execute the query that you are building.

### select

The `select()` method can receive one or more column names to get from the database:

```php
$books = $bookObj->select(); // SELECT * FROM books
$books = $bookObj->select('isbn'); //SELECT isbn FROM books
$books = $bookObj->select(['isbn', 'title']); // SELECT isbn, title FROM books
```

### where

The `where()` method can receive the column to compare, the operator you want to use and the value to compare with:

```php
$books = $bookObj->where('isbn', '=', '1234'); // SELECT FROM books WHERE isbn = '1234'
// or
$books = $bookObj->where('isbn', '1234'); // SELECT FROM books WHERE isbn = '1234'
```

> If you don't use any operator, it will default to `"="`. Valid operators are `"="`, `"<>"`. `"like"`;

You can also chain multiple where clauses:

```php
$books = $bookObj->where('isbn', '1234')->where('title', 'like', '%title%');
// SELECT * FROM books WHERE isbn = '1234' AND title LIKE '%title%'
```

### find / findOrFail

You can look for a specific id in the table using `find()`:

```php
$book = $bookObj->find(1); // SELECT FROM books WHERE id = 1
```

You can also use `findOrFail()` which will throw a `PDOException` if the record is not found:

```php
try {
    $book = $bookObj->findOrFail(1); // SELECT FROM books WHERE id = 1
} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}
```

### orderBy

You can order the records using `orderBy()`:

```php
$bookObj->orderBy('isbn'); // SELECT * FROM books ORDER BY isbn ASC
```

You can specify the direction of the order (ASC or DESC). If no direction is passed, it defaults to ASC.

```php
$bookObj->orderBy('isbn', 'DESC')->orderBy('title'); // SELECT * FROM books ORDER BY isbn DESC, title ASC
```

### groupBy

You can group the records using `groupBy`:

```php
$bookObj->groupBy('author'); // SELECT * FROM books GROUP BY author
```

### limit

You can specify the maximum amount of records returned using `limit()`:

```php
$bookObj->limit(2); // SELECT * FROM books LIMIT 2
```

### first

Get the first record found using `first()`:

```php
$bookObj->first(); // SELECT * FROM books LIMIT 1
```

### last

Get the last record found using `last()`:

```php
$bookObj->last(); // SELECT * FROM books ORDER BY id DESC LIMIT 1
```

### insert

You can also store one or more records at once using `insert()`:

```php
// one record
$bookObj->insert([
    'isbn' => '12345',
    'title' => 'Book Title',
    'author' => 'Brandon Sanderson',
    'price' => 15
]);
// INSERT INTO books (isbn, title, author, price) VALUES ('12345', 'Book Title', 'Brandon Sanderson', 15)

// multiple records
$bookObj->insert([
    [
        'isbn' => '12345',
        'title' => 'Book Title',
        'author' => 'Brandon Sanderson',
        'price' => 15
    ],
    ...
]);
// INSERT INTO books (isbn, title, author, price) VALUES ('12345', 'Book Title', 'Brandon Sanderson', 15), (...)
```

### save

Alternativelly, you can save your object after constructing it using `save()`:

```php
$newBookObj = new Book([
    'isbn' => '12345',
    'title' => 'Book Title',
    'author' => 'Brandon Sanderson',
    'price' => 15
]);

$newBookObj->save();

// INSERT INTO books (isbn, title, author, price) VALUES ('12345', 'Book Title', 'Brandon Sanderson', 15)
```

### update

You can update records using `update()` method:

```php
$bookObj->update(['title' => 'New Title']);
// UPDATE books SET title = 'New Title'
// NOTE: this will set all book titles in table to 'New Title'. Make sure to add a where clause

$bookObj->where('id', 1)->update(['title' => 'New Title']);
// UPDATE books SET title = 'New Title' WHERE id = 1

```

Or update using `save()`

```php
$book = $bookObj->findOrFail(1);

$book->title = "New Title";

$book->save();
```

## Delete

When deleting, the records would normally be erased from the database. However, there is also a way to handle soft deletion.

To delete a record, you would normally do:

```php
$book = $bookObj->findOrFail(1);

$book->delete(); // DELETE FROM books WHERE id = 1
```

### Soft Deletes

If you want to soft delete a record, you must add the following to your class:

```php
use Ipoo\Src\Traits\SoftDeletes;

class Book extends BaseClass {
    use SoftDeletes;
}
```

And also add a column named `deleted_at` that will store the time of deletion.

```php
$book = $bookObj->findOrFail(1);

$book->delete(); // UPDATE books SET deleted_at = NOW() WHERE id = 1
```

In case you were wondering, when using soft deletes, you don't have to add extra conditions to filter deleted records. However, you can retrieve records deleted too:

```php
$bookObj->get(); // SELECT * FROM books WHERE deleted_at = NULL

$bookObj->withDeleted()->get(); // SELECT * FROM books
```

If you want to restore a soft deleted record, just call the `restore()` method that will set the `deleted_at` to `NULL`:

```php
$book = $bookObj->withDeleted()->find(1); // SELECT * FROM books WHERE id = 1

$book->restore(); // UPDATE books SET deleted_at = NULL WHERE id = 1
```

## Debugging

If you want to output your query before executing it, you can use `toSql()` or `toSqlWithBindings()`:

`toSql()` will output the prepared query without the real values:

```php
$bookObj->select(['id', 'isbn'])
    ->where('author', '<>', 'Brandon Sanderson')
    ->orderBy('id')
    ->limit(10)
    ->toSql();
// SELECT id, isbn FROM books WHERE author <> :author ORDER BY id LIMIT 10
```

`toSqlWithBindings()` will output the prepared query asigning the values:

```php
$bookObj->select(['id', 'isbn'])
    ->where('author', '<>', 'Brandon Sanderson')
    ->orderBy('id')
    ->limit(10)
    ->toSql();
// SELECT id, isbn FROM books WHERE author <> 'Brandon Sanderson' ORDER BY id LIMIT 10
```
