<?php

require_once __DIR__ . '/../config/jwt_secret.php';
require_once __DIR__ . '/../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Function to verify JWT token and return user data
function verifyJWT() {
    // Get all headers from the request
    $headers = getallheaders();

    // Check if the Authorization header is set
    if (!isset($headers['Authorization'])) {
        http_response_code(401); // Unauthorized
        echo json_encode(["message" => "Access denied. No token provided."]);
        exit();
    }

    // Extract the token from the Authorization header
    $token = str_replace("Bearer ", "", $headers['Authorization']);

    try {
        // Decode the JWT token
        $decoded = JWT::decode($token, new Key(JWT_SECRET, 'HS256'));

        // Check if token has expired
        if ($decoded->exp < time()) {
            http_response_code(401); // Unauthorized
            echo json_encode(["message" => "Token expired. Please log in again."]);
            exit();
        }

        // Return user ID and role
        return [
            "user_id" => $decoded->user_id,
            "role" => $decoded->role
        ];

    } catch (Exception $e) {
        // Handle errors if token is invalid or expired
        http_response_code(401); // Unauthorized
        echo json_encode(["message" => "Invalid token", "error" => $e->getMessage()]);
        exit();
    }
}

// Function to restrict access to admin-only routes
function requireAdmin() {
    $user = verifyJWT();

    // Check if the user is an admin
    if ($user["role"] !== "admin") {
        http_response_code(403); // Forbidden
        echo json_encode(["message" => "Access denied. Admins only."]);
        exit();
    }
}

// Function to restrict access to normal users
function requireUser() {
    $user = verifyJWT();

    // Check if the user is a normal user (not admin)
    if ($user["role"] !== "user") {
        http_response_code(403); // Forbidden
        echo json_encode(["message" => "Access denied. Users only."]);
        exit();
    }
}

// Function to get the logged-in user's ID
function getUserId() {
    $user = verifyJWT();
    return $user["user_id"];
}

?>
