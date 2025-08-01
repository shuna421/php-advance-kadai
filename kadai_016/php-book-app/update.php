<?php
$dsn = 'mysql:dbname=php_book_app;host=localhost;charset=utf8mb4';
$user = 'root';
$password = 'root';

// submitパラメータがある時（更新ボタン押下時）
if (isset($_POST['submit'])) {
    try {
        $pdo = new PDO($dsn, $user, $password);

        // UPDATE文（書籍用カラムに変更）
        $sql = '
            UPDATE books
            SET book_code = :book_code,
                book_name = :book_name,
                price = :price,
                stock_quantity = :stock_quantity,
                genre_code = :genre_code
            WHERE id = :id
        ';
        $stmt = $pdo->prepare($sql);

        // プレースホルダにPOSTデータをバインド
        $stmt->bindValue(':book_code', $_POST['book_code'], PDO::PARAM_INT);
        $stmt->bindValue(':book_name', $_POST['book_name'], PDO::PARAM_STR);
        $stmt->bindValue(':price', $_POST['price'], PDO::PARAM_INT);
        $stmt->bindValue(':stock_quantity', $_POST['stock_quantity'], PDO::PARAM_INT);
        $stmt->bindValue(':genre_code', $_POST['genre_code'], PDO::PARAM_INT);
        $stmt->bindValue(':id', $_GET['id'], PDO::PARAM_INT);

        $stmt->execute();

        // 更新後は書籍一覧ページへリダイレクト
        header('Location: read.php');
        exit();
    } catch (PDOException $e) {
        exit($e->getMessage());
    }
}

// idパラメータがある時に編集用データ取得
if (isset($_GET['id'])) {
    try {
        $pdo = new PDO($dsn, $user, $password);

        $sql = 'SELECT * FROM books WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
        $stmt->execute();

        $book = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($book === false) {
            exit('該当する書籍がありません。');
        }

        // ジャンル選択用にgenresテーブルからも取得
        $sql_genres = 'SELECT genre_code, genre_name FROM genres ORDER BY genre_code';
        $stmt_genres = $pdo->query($sql_genres);
        $genres = $stmt_genres->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        exit($e->getMessage());
    }
} else {
    exit('idパラメータが指定されていません。');
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>書籍編集</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <h1>書籍編集</h1>
    <p>編集する内容を入力してください。</p>
    <form action="update.php?id=<?= htmlspecialchars($_GET['id'], ENT_QUOTES, 'UTF-8') ?>" method="post">
        <div>
            <label for="book_code">書籍コード<span>【必須】</span></label>
            <input type="number" id="book_code" name="book_code" value="<?= htmlspecialchars($book['book_code'], ENT_QUOTES, 'UTF-8') ?>" required>

            <label for="book_name">書籍名<span>【必須】</span></label>
            <input type="text" id="book_name" name="book_name" value="<?= htmlspecialchars($book['book_name'], ENT_QUOTES, 'UTF-8') ?>" maxlength="50" required>

            <label for="price">価格<span>【必須】</span></label>
            <input type="number" id="price" name="price" value="<?= htmlspecialchars($book['price'], ENT_QUOTES, 'UTF-8') ?>" min="0" required>

            <label for="stock_quantity">在庫数<span>【必須】</span></label>
            <input type="number" id="stock_quantity" name="stock_quantity" value="<?= htmlspecialchars($book['stock_quantity'], ENT_QUOTES, 'UTF-8') ?>" min="0" required>

            <label for="genre_code">ジャンル<span>【必須】</span></label>
            <select id="genre_code" name="genre_code" required>
                <option disabled value="">選択してください</option>
                <?php foreach ($genres as $genre): ?>
                    <option value="<?= htmlspecialchars($genre['genre_code'], ENT_QUOTES, 'UTF-8') ?>" <?= $genre['genre_code'] == $book['genre_code'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($genre['genre_name'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" name="submit" value="update">更新</button>
    </form>
</body>

</html>