<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use FloCMS\Captcha\CaptchaManager;

$captcha = new CaptchaManager();

if (isset($_GET['image'])) {
    $captcha->output();
    exit;
}

$message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valid = $captcha->verify($_POST['captcha'] ?? null);

    // verify() uses the code up, so a wrong answer needs a new image
    if ($valid) {
        $message = 'Captcha is valid.';
    } else {
        $message = 'Captcha is invalid.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Captcha Example</title>
</head>
<body>
    <?php if ($message): ?>
        <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>

    <form method="post">
        <p>
            <img src="?image=1" alt="Captcha Image">
        </p>
        <p>
            <input type="text" name="captcha" placeholder="Enter captcha">
        </p>
        <button type="submit">Verify</button>
    </form>
</body>
</html>