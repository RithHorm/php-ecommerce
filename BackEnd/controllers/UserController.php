<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

// Get all users (Admins only)
function getAllUsers() {
    $user = verifyJWT();  // Verify JWT and get user details

    // Only allow admins to fetch all users
    if ($user['role'] !== 'admin') {
        http_response_code(403); // Forbidden
        echo json_encode(["message" => "Access denied. Admins only."]);
        exit();
    }

    global $pdo;
    $sql = "SELECT id, name, email, role FROM users"; // Exclude passwords for security
    $stmt = $pdo->query($sql);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($users);
}

// Get a single user (Admins can see anyone, users can see only themselves)
function getUserById($id) {
    $user = verifyJWT(); // Verify JWT and get user details

    // If not admin, ensure the user can only fetch their own data
    if ($user['role'] !== 'admin' && $user['user_id'] != $id) {
        http_response_code(403); // Forbidden
        echo json_encode(["message" => "Access denied. You can only view your own profile."]);
        exit();
    }

    global $pdo;
    $stmt = $pdo->prepare("SELECT id, name, email, role FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userData) {
        echo json_encode($userData);
    } else {
        http_response_code(404); // Not Found
        echo json_encode(["message" => "User not found"]);
    }
}

// Edit user details (Users can edit their own, Admins can edit any user)
function editUser($id) {
    $user = verifyJWT(); // Verify JWT and get user details

    // Only allow users to edit their own account, unless they are an admin
    if ($user['role'] !== 'admin' && $user['user_id'] != $id) {
        http_response_code(403); // Forbidden
        echo json_encode(["message" => "Access denied. You can only edit your own profile."]);
        exit();
    }

    global $pdo;
    $data = json_decode(file_get_contents("php://input"));

    // Ensure at least one field is provided for update
    if (empty($data->name) && empty($data->password) && empty($data->role)) {
        http_response_code(400); // Bad Request
        echo json_encode(["message" => "Please provide at least one field to update"]);
        exit();
    }

    // Prepare update fields
    $fields = [];
    $values = [];

    if (!empty($data->name)) {
        $fields[] = "name = ?";
        $values[] = $data->name;
    }

    if (!empty($data->password)) {
        $fields[] = "password = ?";
        $values[] = password_hash($data->password, PASSWORD_BCRYPT);
    }

    if (!empty($data->role) && $user['role'] === 'admin') {
        $fields[] = "role = ?";
        $values[] = $data->role;
    }

    $values[] = $id;
    $sql = "UPDATE users SET " . implode(", ", $fields) . " WHERE id = ?";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute($values)) {
        echo json_encode(["message" => "User updated successfully"]);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(["message" => "User update failed"]);
    }
}

// Delete user (Admins can delete any user)
function deleteUser($id) {
    $user = verifyJWT();  // Verify JWT and get user details

    // Only allow admins to delete users
    if ($user['role'] !== 'admin') {
        http_response_code(403); // Forbidden
        echo json_encode(["message" => "Access denied. Admins only."]);
        exit();
    }

    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["message" => "User deleted successfully"]);
    } else {
        http_response_code(404); // Not Found
        echo json_encode(["message" => "User not found"]);
    }
}


?>
