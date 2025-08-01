<?php
$dsn = 'mysql:dbname=php_book_app;host=localhost;charset=utf8mb4';
$user = 'root';
$password = 'root';

try {
    $pdo = new PDO($dsn, $user, $password);

    // orderパラメータの値を取得（なければNULL）
    $order = $_GET['order'] ?? null;

    // keywordパラメータの値を取得（なければ空文字）
    $keyword = $_GET['keyword'] ?? '';

    // SQL文をorderによって切り替え
    if ($order === 'desc') {
        $sql_select = 'SELECT * FROM books WHERE book_name LIKE :keyword ORDER BY updated_at DESC';
    } else {
        $sql_select = 'SELECT * FROM books WHERE book_name LIKE :keyword ORDER BY updated_at ASC';
    }

    $stmt_select = $pdo->prepare($sql_select);

    $partial_match = "%{$keyword}%";

    $stmt_select->bindValue(':keyword', $partial_match, PDO::PARAM_STR);

    $stmt_select->execute();

    // 変数名は $books に統一
    $books = $stmt_select->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    exit($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>書籍一覧</title>
    <link rel="stylesheet" href="css/style.css">

    <!-- Google Fontsの読み込み -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <nav>
            <a href="index.php">書籍管理アプリ</a>
        </nav>
    </header>
    <main>
    <?php if (!empty($_GET['message'])) : ?>
        <p class="message"><?php echo htmlspecialchars($_GET['message'], ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>

        <article class="books">
            <h1>書籍一覧</h1>
          
            ?>
            <div class="books-ui">
                <div>
                    <a href="read.php?order=desc&keyword=<?= htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8') ?>">
                        <img src="images/desc.png" alt="降順に並び替え" class="sort-img">
                    </a>
                    <a href="read.php?order=asc&keyword=<?= htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8') ?>">
                        <img src="images/asc.png" alt="昇順に並び替え" class="sort-img">
                    </a>
                    <form action="read.php" method="get" class="search-form">
                        <input type="hidden" name="order" value="<?= htmlspecialchars($order, ENT_QUOTES, 'UTF-8') ?>">
                        <input type="text" class="search-box" placeholder="書籍名で検索" name="keyword" value="<?= htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8') ?>">
                    </form>
                </div>
                <a href="create.php" class="btn">書籍登録</a>
            </div>
            <table class="books-table">
                <tr>
                    <th>書籍コード</th>
                    <th>書籍名</th>
                    <th>単価</th>
                    <th>在庫数</th>
                    <th>ジャンルコード</th>
                    <th>編集</th>
                    <th>削除</th>
                </tr>
                <?php
                foreach ($books as $book) {
                    echo "<tr>
                        <td>" . htmlspecialchars($book['book_code'], ENT_QUOTES, 'UTF-8') . "</td>
                        <td>" . htmlspecialchars($book['book_name'], ENT_QUOTES, 'UTF-8') . "</td>
                        <td>" . htmlspecialchars($book['price'], ENT_QUOTES, 'UTF-8') . "</td>
                        <td>" . htmlspecialchars($book['stock_quantity'], ENT_QUOTES, 'UTF-8') . "</td>
                        <td>" . htmlspecialchars($book['genre_code'], ENT_QUOTES, 'UTF-8') . "</td>
                        <td><a href='update.php?id=" . htmlspecialchars($book['id'], ENT_QUOTES, 'UTF-8') . "'><img src='images/edit.png' alt='編集' class='edit-icon'></a></td>
                        <td><a href='delete.php?id=" . htmlspecialchars($book['id'], ENT_QUOTES, 'UTF-8') . "'><img src='images/delete.png' alt='削除' class='delete-icon'></a></td>
                    </tr>";
                }
                ?>
            </table>
        </article>
    </main>
    <footer>
        <p class="copyright">&copy; 書籍管理アプリ All rights reserved.</p>
    </footer>
</body>

</html>