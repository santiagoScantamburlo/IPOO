<?php

namespace Ipoo\Tps\Tp1\Ej16;

use Ipoo\Src\BaseClass;

/**
 * @property string $isbn
 * @property string $title
 * @property string $authorName
 * @property string $authorSurname
 * @property string $publisher
 * @property int $year
 */
class Book extends BaseClass
{
    protected array $attributes = ['isbn', 'title', 'authorName', 'authorSurname', 'publisher', 'year'];

    protected array $unique = ['isbn'];

    public function belongsToPublisher(string $publisher): bool
    {
        return $this->publisher === $publisher;
    }

    public function yearsSincePublication(): int
    {
        return date('Y') - $this->year;
    }
}
