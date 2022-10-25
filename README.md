### QUERY-OPTIMIZATION

### Laravel
Eager Loading

### Python
Prefetch

### PHP
Raw PHP Eager Loading
```
$authors = $pdo->query("SELECT * FROM `authors` LIMIT 10;");

// Setup eager list of IDs
$ids = [];
foreach($authors as $author) {
    $ids[] = (int) $author['id'];
}
$author_ids = implode(',', $ids);

// Get all books and group them
$grouped_books = [];
$all_books = $pdo->query("SELECT * FROM `books` WHERE `author_id` IN ( {$author_ids} )");
foreach($all_books as $book) {
    $grouped_books[$book['author_id']][] = $book;
}

// Final loop
foreach ($authors as $author) {
    var_dump($author);
    var_dump($grouped_books[ $author['id'] ]);
}
```

### References

[Raw PHP Eager Loading](https://kevdees.com/raw-php-and-mysql-eager-loading/)
