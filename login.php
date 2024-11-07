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

    // Подготовка SQL-запроса для получения пользователя по имени
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    // Проверка пароля
    if ($user && password_verify($password, $user['password_hash'])) {
        // Устанавливаем user_id в сессию
        $_SESSION['user_id'] = $user['id'];  
        
        // Перенаправляем на главную страницу после успешного входа
        header('Location: index.php');
        exit;
    } else {
        $error_message = 'Неверное имя пользователя или пароль.';
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в личный кабинет</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Подключаем внешний CSS -->
</head>
<body>
    <div class="login-container">
        <h2>Вход в систему</h2>
        
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
            <button type="submit">Войти</button>
        </form>
        
        <p>Еще нет аккаунта? <a href="register.php">Зарегистрироваться</a></p>
    </div>
</body>
</html>