# BaseClass usage

## Extending

When using a class that will have all the base functionalities already set up, just extend the BaseClass like this:

```php
namespace Ipoo;

namespace Ipoo\Your\Namespace;

use Ipoo\BaseClass;

class Book extends BaseClass {
    protected array $attributes = ['isbn', 'title', 'author', 'price']:

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

// create() method without arguments
$book = Book::create();

// create() method with properties
$book = Book::create([
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

// Using get() method for a single attribute
$title = $book->get('title'); // 'Book Title'

// Using get() method for multiple attributes
$title = $book->get(['title']); // ['title' => 'Book Title']
```

You can also make your custom accessors following the convention:</br>
For the attribute `yourAttributeName`, its accessor should be called `getYourAttributeNameAttribute()`:

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

$book->getPrice(); // '$10'
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

### Comparing objects

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
