<?php
session_start();

// Если пользователь уже авторизован, перенаправляем на главную
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error_message = ''; // Переменная для хранения сообщения об ошибке

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include('config.php');
    
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Проверка на существование пользователя с таким же именем
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user) {
        $error_message = 'Пользователь с таким именем уже существует.';
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Подготовка SQL-запроса для вставки нового пользователя
        $stmt = $pdo->prepare('INSERT INTO users (username, password_hash) VALUES (:username, :password)');
        $stmt->execute(['username' => $username, 'password' => $password_hash]);

        // Перенаправление на страницу логина после успешной регистрации
        header('Location: login.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Подключаем внешний CSS -->
</head>
<body>
    <div class="login-container">
        <h2>Регистрация нового пользователя</h2>
        
        <?php if ($error_message): ?>
            <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>
        
        <form method="post" class="login-form">
            <div class="input-group">
                <input type="text" name="username" placeholder="Имя пользователя" required>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Пароль" required>
            </div>
            <button type="submit">Зарегистрироваться</button>
        </form>
        
        <p>Уже есть аккаунт? <a href="login.php">Войти</a></p>
    </div>
</body>
</html>