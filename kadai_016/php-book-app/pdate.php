<?php
$dsn = 'mysql:dbname=php_book_app;host=localhost;charset=utf8mb4';
$user = 'root';
$password = 'root';

if (isset($_POST['submit'])) {
  try {
      $pdo = new PDO($dsn, $user, $password);

      $sql_update = '
          UPDATE books
          SET book_code = :book_code,
              book_name = :book_name,
              price = :price,
              stock_quantity = :stock_quantity,
              genre_code = :genre_code
          WHERE id = :id
      ';
      $stmt_update = $pdo->prepare($sql_update);

      $stmt_update->bindValue(':book_code', $_POST['book_code'], PDO::PARAM_INT);
      $stmt_update->bindValue(':book_name', $_POST['book_name'], PDO::PARAM_STR);
      $stmt_update->bindValue(':price', $_POST['price'], PDO::PARAM_INT);
      $stmt_update->bindValue(':stock_quantity', $_POST['stock_quantity'], PDO::PARAM_INT);
      $stmt_update->bindValue(':genre_code', $_POST['genre_code'], PDO::PARAM_INT);
      $stmt_update->bindValue(':id', $_GET['id'], PDO::PARAM_INT);

      $stmt_update->execute();

      $count = $stmt_update->rowCount();

      $message = "書籍を{$count}件編集しました。";

      header("Location: read.php?message={$message}");
  } catch (PDOException $e) {
      exit($e->getMessage());
  }
}

if (isset($_GET['id'])) {
    try {
        $pdo = new PDO($dsn, $user, $password);

        $sql_select_book = 'SELECT * FROM books WHERE id = :id';
        $stmt_select_book = $pdo->prepare($sql_select_book);
        $stmt_select_book->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
        $stmt_select_book->execute();
        $book = $stmt_select_book->fetch(PDO::FETCH_ASSOC);

        if ($book === FALSE) {
            exit('idパラメータの値が不正です。');
        }

        // genresテーブルからgenre_codeを取得
        $sql_select_genres = 'SELECT genre_code FROM genres';
        $stmt_select_genres = $pdo->query($sql_select_genres);
        $genre_codes = $stmt_select_genres->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        exit($e->getMessage());
    }
} else {
    exit('idパラメータの値が存在しません。');
}
?>
<title>書籍編集</title>

<header>
    <nav>
        <a href="index.php">書籍管理アプリ</a>
    </nav>
</header>

<h1>書籍編集</h1>

<form action="update.php?id=<?= htmlspecialchars($_GET['id'], ENT_QUOTES) ?>" method="post" class="registration-form">
    <label for="book_code">書籍コード</label>
    <input type="number" id="book_code" name="book_code" value="<?= htmlspecialchars($book['book_code'], ENT_QUOTES) ?>" required>

    <label for="book_name">書籍名</label>
    <input type="text" id="book_name" name="book_name" value="<?= htmlspecialchars($book['book_name'], ENT_QUOTES) ?>" required>

    <label for="price">単価</label>
    <input type="number" id="price" name="price" value="<?= htmlspecialchars($book['price'], ENT_QUOTES) ?>" required>

    <label for="stock_quantity">在庫数</label>
    <input type="number" id="stock_quantity" name="stock_quantity" value="<?= htmlspecialchars($book['stock_quantity'], ENT_QUOTES) ?>" required>

    <label for="genre_code">ジャンルコード</label>
    <select id="genre_code" name="genre_code" required>
        <option disabled value>選択してください</option>
        <?php
        foreach ($genre_codes as $genre_code) {
            $selected = ($genre_code == $book['genre_code']) ? 'selected' : '';
            echo "<option value='{$genre_code}' {$selected}>{$genre_code}</option>";
        }
        ?>
    </select>

    <button type="submit" name="submit" value="update">更新</button>
</form>

<footer>
    <p>&copy; 書籍管理アプリ All rights reserved.</p>
</footer>