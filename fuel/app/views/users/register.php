<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ユーザー登録</title>
    <link rel="stylesheet" href="/assets/css/auth.css?v=3">
</head>
<body>
    <div class="auth-container">
        <h1>ユーザー登録</h1>

        <?php if (!empty($error)): ?>
            <p class="error-message">
                <?= e($error) ?>
            </p>
        <?php endif; ?>

        <form class="auth-form" action="/auth/register" method="post">
            <div class="form-row">
                <label>ユーザー名:</label>
                <input type="text" name="name">
            </div>

            <div class="form-row">
                <label>メールアドレス:</label>
                <input type="email" name="email">
            </div>

            <div class="form-row">
                <label>パスワード:</label>
                <input type="password" name="password">
            </div>

            <div class="form-row">
                <label>確認用パスワード:</label>
                <input type="password" name="password_confirm">
            </div>

            <button type="submit" class="auth-button">登録</button>
        </form>
    </div>
</body>
</html>