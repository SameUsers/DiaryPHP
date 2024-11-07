<?php
session_start();

// Если пользователь не авторизован, перенаправляем на страницу входа
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include('config.php');
    
    $entry_text = $_POST['entry_text'];
    
    // Записываем новую запись в базу данных
    $stmt = $pdo->prepare('INSERT INTO entries (user_id, entry_text) VALUES (:user_id, :entry_text)');
    $stmt->execute([
        'user_id' => $_SESSION['user_id'],
        'entry_text' => $entry_text
    ]);

    header('Location: index.php');  // Перенаправляем на главную страницу после сохранения
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить запись</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<header>
    <h1>Добавить новую запись</h1>
</header>

<div class="container">
    <form method="post">
        <textarea name="entry_text" placeholder="Содержание" required></textarea><br>
        <button type="submit">Сохранить</button>
    </form>
    <p><a href="index.php">Назад на главную</a></p>
</div>

</body>
</html>