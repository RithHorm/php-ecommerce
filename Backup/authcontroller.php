<?php
require_once '../config/db.php';
require_once '../config/jwt_secret.php';
require_once '../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function register() {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->name) && !empty($data->email) && !empty($data->password)) {
        // Validate email format (must contain "@gmail.com")
        if (!filter_var($data->email, FILTER_VALIDATE_EMAIL) || !str_ends_with($data->email, "@gmail.com")) {
            echo json_encode(["message" => "Email must be a valid Gmail address (@gmail.com)"]);
            return;
        }

        // Validate password length (must be greater than 8 characters)
        if (strlen($data->password) < 8) {
            echo json_encode(["message" => "Password must be at least 8 characters long"]);
            return;
        }

        global $pdo;
        $hashed_password = password_hash($data->password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$data->name, $data->email, $hashed_password])) {
            echo json_encode(["message" => "User registered successfully"]);
        } else {
            echo json_encode(["message" => "User registration failed"]);
        }
    } else {
        echo json_encode(["message" => "All fields are required"]);
    }
}


function login() {
    $data = json_decode(file_get_contents("php://input"), true); // Decode as an associative array

    // Extract only 'email' and 'password', ignore other fields
    $email = $data['email'] ?? null;
    $password = $data['password'] ?? null;

    if (!empty($email) && !empty($password)) {
        global $pdo;
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Generate JWT Token
            $payload = [
                "iss" => "localhost",
                "iat" => time(),
                "exp" => time() + (60 * 60), // Token expires in 1 hour
                "user_id" => $user["id"]
            ];
            $token = JWT::encode($payload, JWT_SECRET, 'HS256');

            echo json_encode(["message" => "Login successful", "token" => $token]);
        } else {
            echo json_encode(["message" => "Invalid email or password"]);
        }
    } else {
        echo json_encode(["message" => "Email and password are required"]);
    }
}

?>
