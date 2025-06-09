<?php
session_start();
require_once "auth.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];

    if (!user_exists($email)) {
        $error = "Invalid email address!";
    } else {
        $_SESSION["reset_code"] = rand(1000, 9999);
        $_SESSION["email"] = $email;
        $_SESSION["attempts"] = 0;
        $_SESSION["start_time"] = time();
        header("Location: verify_code.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9f9f9;
        }
        .reset-box {
            margin-top: 80px;
            padding: 25px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .reset-title {
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center">
        <div class="col-md-4 reset-box">
            <h3 class="text-center reset-title">Reset Password</h3>
            <?php if ($error): ?>
                <div class="alert alert-danger text-center" role="alert">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input name="email" type="email" class="form-control" required>
                </div>
                <button class="btn btn-primary w-100" type="submit">Submit</button>
            </form>
        </div>
    </div>
</body>
</html>
