<?php
$dsn = 'mysql:dbname=php_db;host=localhost;charset=utf8mb4';
$user = 'root';
// MAMPを利用しているMacユーザーの方は、''ではなく'root'を代入してください
$password = 'root';

try {
    $pdo = new PDO($dsn, $user, $password);

    // usersテーブルからidカラムとnameカラムのデータを取得するためのSQL文を変数$sqlに代入する
    $sql = 'SELECT id, name FROM users';

    // SQL文を実行する
    $stmt = $pdo->query($sql);

    // SQL文の実行結果を配列で取得する
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    exit($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP+DB</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
<table>
        <tr>
            <th>ID</th>
            <th>書籍コード</th>
            <th>書籍名</th>
            <th>値段</th>
            <th>在庫数</th>
            <th>ジャンルコード</th>
            <th>更新日時</th>
        </tr>
        <?php
// 配列の中身を順番に取り出し、表形式で出力する
foreach ($results as $result) {
    echo "<tr>";
    echo "<td>{$result['id']}</td>";
    echo "<td>{$result['book_code']}</td>";
    echo "<td>{$result['book_name']}</td>";
    echo "<td>{$result['price']}</td>";
    echo "<td>{$result['stock_quantity']}</td>";
    echo "<td>{$result['genre_code']}</td>";
    echo "<td>{$result['updated_at']}</td>";
    echo "</tr>";
}
?>
    </table>
</body>

</html>