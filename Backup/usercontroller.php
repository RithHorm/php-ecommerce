<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

// Get all users
function getAllUsers() {
    // Check if the user is authenticated via JWT token
    $user_id = verifyJWT();

    // Fetch all users if authenticated
    global $pdo;
    $sql = "SELECT * FROM users";
    $stmt = $pdo->query($sql);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($users);
}

// Edit user
function editUser($id) {
    // Verify JWT token for authentication
    $user_id = verifyJWT();

    // Only allow the user to edit their own information
    if ($user_id != $id) {
        echo json_encode(["message" => "Access denied. You can only edit your own profile."]);
        http_response_code(403); // Forbidden
        exit();
    }

    // Get data from the request
    $data = json_decode(file_get_contents("php://input"));

    // Check if at least one field is provided (name or password)
    if (!empty($data->name) || !empty($data->password)) {
        global $pdo;
        
        // If the password is provided, hash it
        if (!empty($data->password)) {
            $hashed_password = password_hash($data->password, PASSWORD_BCRYPT);
            $sql = "UPDATE users SET name = ?, password = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$data->name, $hashed_password, $id])) {
                echo json_encode(["message" => "User updated successfully"]);
            } else {
                echo json_encode(["message" => "User update failed"]);
            }
        } else {
            // If no password, just update the name
            $sql = "UPDATE users SET name = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$data->name, $id])) {
                echo json_encode(["message" => "User updated successfully"]);
            } else {
                echo json_encode(["message" => "User update failed"]);
            }
        }
    } else {
        echo json_encode(["message" => "Name or Password required to update"]);
    }
}

?>
