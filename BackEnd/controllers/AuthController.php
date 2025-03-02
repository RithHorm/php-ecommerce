<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/jwt_secret.php';
require_once __DIR__ . '/../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Ensure Admin Exists (Run once)
function createAdminIfNotExists() {
    global $pdo;
    
    $email = 'admin@gmail.com';
    $password = 'admin123';
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Check if admin exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $admin = $stmt->fetch();

    if (!$admin) {
        // Create admin user
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute(['Admin', $email, $hashedPassword, 'admin']);
        echo "Admin account created.\n";
    }
}

// Register User (Only for Normal Users)
function register() {
    global $pdo;

    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->name) || !isset($data->email) || !isset($data->password)) {
        echo json_encode(["message" => "Name, Email, and Password are required"]);
        http_response_code(400);
        return;
    }

    // Hash password
    $hashedPassword = password_hash($data->password, PASSWORD_BCRYPT);

    // Default role is "user"
    $role = 'user';

    // Insert user
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$data->name, $data->email, $hashedPassword, $role])) {
        echo json_encode(["message" => "User registered successfully"]);
    } else {
        echo json_encode(["message" => "Registration failed"]);
    }
}

// Login (Admin & User)
function login() {
    global $pdo;

    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->email) || !isset($data->password)) {
        echo json_encode(["message" => "Email and Password required"]);
        http_response_code(400);
        return;
    }

    // Fetch user
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$data->email]);
    $user = $stmt->fetch();

    if ($user && password_verify($data->password, $user['password'])) {
        // Generate JWT token
        $payload = [
            "user_id" => $user['id'],
            "email" => $user['email'],
            "role" => $user['role'],
            "exp" => time() + (60 * 60) // Token expires in 1 hour
        ];
        $token = JWT::encode($payload, JWT_SECRET, 'HS256');

        echo json_encode(["token" => $token, "role" => $user['role']]);
    } else {
        echo json_encode(["message" => "Invalid credentials"]);
        http_response_code(401);
    }
}

// Run Admin Check on Startup
createAdminIfNotExists();

?>
