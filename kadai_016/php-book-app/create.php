<?php
$dsn = 'mysql:dbname=php_book_app;host=localhost;charset=utf8mb4';
$user = 'root';
$password = 'root';

if (isset($_POST['submit'])) {
  try {
      $pdo = new PDO($dsn, $user, $password);

      $sql_insert = '
          INSERT INTO books (book_code, book_name, price, stock_quantity, genre_code)
          VALUES (:book_code, :book_name, :price, :stock_quantity, :genre_code)
      ';
      $stmt_insert = $pdo->prepare($sql_insert);

      $stmt_insert->bindValue(':book_code', $_POST['book_code'], PDO::PARAM_INT);
      $stmt_insert->bindValue(':book_name', $_POST['book_name'], PDO::PARAM_STR);
      $stmt_insert->bindValue(':price', $_POST['price'], PDO::PARAM_INT);
      $stmt_insert->bindValue(':stock_quantity', $_POST['stock_quantity'], PDO::PARAM_INT);
      $stmt_insert->bindValue(':genre_code', $_POST['genre_code'], PDO::PARAM_INT);

      $stmt_insert->execute();

      $count = $stmt_insert->rowCount();

      $message = "書籍を{$count}件登録しました。";

      // 登録成功後
header('Location: read.php?message=登録が完了しました');
exit;
  } catch (PDOException $e) {
      exit($e->getMessage());
  }
}

try {
    $pdo = new PDO($dsn, $user, $password);

    // genresテーブルからgenre_codeとgenre_nameを取得する
    $sql_select = 'SELECT genre_code, genre_name FROM genres ORDER BY genre_code';

    $stmt_select = $pdo->query($sql_select);

    $genres = $stmt_select->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    exit($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>書籍登録</title>
    <!-- CSS省略 -->
</head>
<body>
<header>
    <nav><a href="index.php">書籍管理アプリ</a></nav>
</header>
<main>
    <article class="registration">
    <h1>書籍登録</h1>
    <div class="back"><a href="read.php" class="btn">&lt; 戻る</a></div>
    <form action="create.php" method="post" class="registration-form">
        <div>
            <label for="book_code">書籍コード <span class="required-label">【必須】</span></label>
            <input type="number" id="book_code" name="book_code" min="0" max="100000000" required>

            <label for="book_name">書籍名 <span class="required-label">【必須】</span></label>
            <input type="text" id="book_name" name="book_name" maxlength="50" required>

            <label for="price">単価 <span class="required-label">【必須】</span></label>
            <input type="number" id="price" name="price" min="0" max="100000000" required>

            <label for="stock_quantity">在庫数 <span class="required-label">【必須】</span></label>
            <input type="number" id="stock_quantity" name="stock_quantity" min="0" max="100000000" required>

            <label for="genre_code">ジャンル <span class="required-label">【必須】</span></label>
            <select id="genre_code" name="genre_code" required>
                <option disabled selected value>選択してください</option>
                <?php
                foreach ($genres as $genre) {
                    echo "<option value='{$genre['genre_code']}'>{$genre['genre_name']}</option>";
                }
                ?>
            </select>
            </div>
            <button type="submit" name="submit" value="create" class="submit-btn">登録</button>
        </form>
    </article>
</main>
<footer>
    <p>&copy; 書籍管理アプリ All rights reserved.</p>
</footer>
</body>
</html>