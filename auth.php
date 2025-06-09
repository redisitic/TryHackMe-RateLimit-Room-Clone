<?php
function get_db_connection() {
    $host = "localhost";
    $dbname = "hammer";
    $username = "root";
    $password = "";

    try {
        return new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    } catch (PDOException $e) {
        die("Database connection failed.");
    }
}

function verify_user($email, $password) {
    $db = get_db_connection();
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        return true;
    }
    return false;
}

function user_exists($email) {
    $db = get_db_connection();
    $stmt = $db->prepare("SELECT 1 FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    return $stmt->fetch() !== false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['register'])) {
    header('Content-Type: application/json');

    $data = json_decode(file_get_contents("php://input"), true);
    $email = trim($data['email'] ?? '');
    $password = $data['password'] ?? '';

    if (!$email || !$password) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Email and password are required."]);
        exit();
    }

    try {
        $db = get_db_connection();
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            http_response_code(409);
            echo json_encode(["status" => "error", "message" => "User already exists."]);
            exit();
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $insert = $db->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
        $insert->execute([$email, $hashed]);

        echo json_encode(["status" => "success", "message" => "User registered successfully."]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Database error."]);
    }

    exit();
}