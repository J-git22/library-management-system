<?php
require_once 'app/config/config.php';

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $realBooks = [
        "Things Fall Apart",
        "The Great Gatsby",
        "To Kill a Mockingbird",
        "1984",
        "Pride and Prejudice",
        "The Catcher in the Rye",
        "The Lord of the Rings",
        "The Hobbit",
        "Fahrenheit 451",
        "Moby Dick",
        "Jane Eyre",
        "The Alchemist",
        "The Kite Runner",
        "Brave New World",
        "Animal Farm",
        "The Chronicles of Narnia",
        "Lord of the Flies",
        "A Tale of Two Cities",
        "Little Women",
        "The Grapes of Wrath",
        "Catch-22",
        "Wuthering Heights"
    ];

    $stmt = $pdo->query("SELECT id, title FROM books");
    $books = $stmt->fetchAll(PDO::FETCH_OBJ);

    $index = 0;
    foreach ($books as $book) {
        $title = $realBooks[$index % count($realBooks)];
        
        $updateBook = $pdo->prepare("UPDATE books SET title = :title WHERE id = :id");
        $updateBook->execute([
            ':title' => $title,
            ':id' => $book->id
        ]);
        
        echo "Updated Book ID {$book->id} to '{$title}'\n";
        $index++;
    }

    echo "Finished updating books.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
