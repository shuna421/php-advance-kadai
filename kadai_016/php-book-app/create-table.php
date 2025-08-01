<?php
$dsn = 'mysql:dbname=php_book_app;host=localhost;charset=utf8mb4';
$user = 'root';
$password = 'root';

try {
    $pdo = new PDO($dsn, $user, $password);

    // genresテーブル作成
    $sql_genres = "
        CREATE TABLE IF NOT EXISTS genres (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            genre_code INT(11) NOT NULL UNIQUE,
            genre_name VARCHAR(50) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";
    $pdo->exec($sql_genres);

    // booksテーブル作成
    $sql_books = "
        CREATE TABLE IF NOT EXISTS books (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            book_code INT(11) NOT NULL,
            book_name VARCHAR(50) NOT NULL,
            price INT(11) NOT NULL,
            stock_quantity INT(11) NOT NULL,
            genre_code INT(11) NOT NULL,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (genre_code) REFERENCES genres(genre_code)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";
    $pdo->exec($sql_books);

    echo "テーブルの作成が完了しました。";
} catch (PDOException $e) {
    exit("エラーが発生しました: " . $e->getMessage());
}
?>