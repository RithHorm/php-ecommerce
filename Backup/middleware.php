<?php
require_once '../config/jwt_secret.php';
require_once '../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function verifyJWT() {
    // Get all headers from the request
    $headers = getallheaders();

    // Check if the Authorization header is set
    if (!isset($headers['Authorization'])) {
        echo json_encode(["message" => "Access denied. No token provided."]);
        http_response_code(401);  // Unauthorized
        exit();
    }

    // Extract the token from the Authorization header
    $token = str_replace("Bearer ", "", $headers['Authorization']);

    try {
        // Decode the JWT token
        $decoded = JWT::decode($token, new Key(JWT_SECRET, 'HS256'));

        // Return the user ID from the decoded token
        return $decoded->user_id;
    } catch (Exception $e) {
        // Handle error if token is invalid or expired
        echo json_encode(["message" => "Invalid token", "error" => $e->getMessage()]);
        http_response_code(401);  // Unauthorized
        exit();
    }
}
?>
