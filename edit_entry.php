<?php
session_start();

// Если пользователь не авторизован, перенаправляем на страницу входа
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Получаем ID записи из URL
if (isset($_GET['id'])) {
    $entry_id = $_GET['id'];

    include('config.php');

    // Получаем запись из базы данных
    $stmt = $pdo->prepare('SELECT * FROM entries WHERE id = :id AND user_id = :user_id');
    $stmt->execute(['id' => $entry_id, 'user_id' => $_SESSION['user_id']]);
    $entry = $stmt->fetch();

    // Если запись не найдена, перенаправляем на главную
    if (!$entry) {
        header('Location: index.php');
        exit;
    }

    // Обработка формы редактирования
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $entry_text = $_POST['entry_text'];

        // Обновляем запись в базе данных
        $stmt = $pdo->prepare('UPDATE entries SET entry_text = :entry_text WHERE id = :id AND user_id = :user_id');
        $stmt->execute(['entry_text' => $entry_text, 'id' => $entry_id, 'user_id' => $_SESSION['user_id']]);

        // Перенаправляем на главную страницу после успешного редактирования
        header('Location: index.php');
        exit;
    }
} else {
    // Если ID записи не передан, перенаправляем на главную
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать запись</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<header>
    <h1>Редактировать запись</h1>
</header>

<div class="container">
    <form method="post">
        <textarea name="entry_text" placeholder="Текст записи" required><?= htmlspecialchars($entry['entry_text']) ?></textarea><br>
        <button type="submit">Обновить</button>
    </form>
    <p><a href="index.php">Назад на главную</a></p>
</div>

</body>
</html>