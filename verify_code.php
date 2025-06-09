<?php
session_start();

$error = "";
$timeout = 120;

if (!isset($_SESSION["start_time"])) {
    header("Location: reset_password.php");
    exit();
}

if (!isset($_SESSION["attempts"])) {
    $_SESSION["attempts"] = 0;
}

if (!isset($_SESSION["reset_code"])) {
    header("Location: reset_password.php");
    exit();
}

if (time() - $_SESSION["start_time"] > $timeout) {
    session_destroy();
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $code = $_POST["code"];
    $_SESSION["attempts"]++;

    if ($_SESSION["attempts"] > 10) {
        $error = "Too many attempts. Try again later.";
    } elseif ((int)$code === $_SESSION["reset_code"]) {
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid code!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Code</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9f9f9;
        }
        .verify-box {
            margin-top: 80px;
            padding: 25px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .verify-title {
            font-weight: 600;
        }
    </style>
    <script>
        let countdown = <?= $timeout - (time() - $_SESSION["start_time"]) ?>;
        function startCountdown() {
            const timerElement = document.getElementById("countdown");
            setInterval(() => {
                if (countdown <= 0) {
                    window.location.href = 'login.php';
                }
                timerElement.textContent = "You have " + countdown + " seconds to enter your code.";
                countdown--;
            }, 1000);
        }
        window.onload = startCountdown;
    </script>
</head>
<body>
    <div class="container d-flex justify-content-center">
        <div class="col-md-4 verify-box">
            <h3 class="text-center verify-title">Enter Recovery Code</h3>
            <p class="text-center text-muted" id="countdown"></p>
            <?php if ($error): ?>
                <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">4-Digit Code</label>
                    <input name="code" class="form-control" required maxlength="4">
                </div>
                <button class="btn btn-primary w-100 mb-2" type="submit">Submit Code</button>
                <a href="login.php" class="btn btn-danger w-100">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>