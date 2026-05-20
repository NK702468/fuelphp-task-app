<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
    <link rel="stylesheet" href="/assets/css/auth.css?v=3">
</head>
<body>
    <div class="auth-container login-container">
        <h1>ログイン</h1>
        <?php if (!empty($error)): ?>
            <p class="error-message">
                <?= e($error) ?>
            </p>
        <?php endif; ?>

        <form class="auth-form" action="/auth/login" method="post">
            <div class="form-row">
                <label>メールアドレス:</label>
                <input type="email" name="email">
            </div>

            <div class="form-row">
                <label>パスワード:</label>
                <input type="password" name="password">
            </div>

            <button type="submit" class="login-button">ログイン</button>

            <a href="/auth/register" class="register-link">ユーザー登録</a>
        </form>
    </div>
</body>
</html>