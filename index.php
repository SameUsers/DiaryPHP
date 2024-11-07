<?php
session_start();

// Если пользователь не авторизован, перенаправляем на страницу входа
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include('config.php');

// Получаем все записи текущего пользователя
$stmt = $pdo->prepare('SELECT * FROM entries WHERE user_id = :user_id ORDER BY created_at DESC');
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$entries = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный дневник</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<header>
    <h1>Мой личный дневник</h1>
</header>

<div class="container">
    <nav>
        <a href="add_entry.php">Добавить запись</a> | 
        <a href="logout.php">Выход</a>
    </nav>

    <section>
        <h2>Все записи</h2>
        <?php if ($entries): ?>
            <ul>
                <?php foreach ($entries as $entry): ?>
                    <li>
                        <p><strong>Дата:</strong> <?= htmlspecialchars($entry['created_at']) ?></p>
                        <p><?= nl2br(htmlspecialchars($entry['entry_text'])) ?></p>
                        <a href="edit_entry.php?id=<?= $entry['id'] ?>">Редактировать</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>У вас еще нет записей.</p>
        <?php endif; ?>
    </section>
</div>

</body>
</html>