<?php
require_once __DIR__ . '/../config/db.php';  // Include the DB connection

// Get all products (Admins only)
function getAllProducts() {
    $user = verifyJWT();  // Verify JWT and get user details

    if ($user['role'] !== 'admin') {
        http_response_code(403); // Forbidden
        echo json_encode(["message" => "Access denied. Admins only."]);
        exit();
    }

    global $pdo;
    $sql = "SELECT id, name, title, description, price, picture FROM products";  // Include picture field
    $stmt = $pdo->query($sql);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($products);
}

// Get a single product by ID
function getProductById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id, name, title, description, price, picture FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        echo json_encode($product);
    } else {
        http_response_code(404);  // Not Found
        echo json_encode(["message" => "Product not found"]);
    }
}

// Create a new product (Admins only)
function createProduct() {
    $user = verifyJWT();  // Verify JWT and get user details

    if ($user['role'] !== 'admin') {
        http_response_code(403);  // Forbidden
        echo json_encode(["message" => "Access denied. Admins only."]);
        exit();
    }

    if (empty($_POST['name']) || empty($_POST['title']) || empty($_POST['description']) || empty($_POST['price'])) {
        http_response_code(400);  // Bad Request
        echo json_encode(["message" => "All fields are required"]);
        exit();
    }

    $name = $_POST['name'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $picturePath = null;

    // Handle the picture upload
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] === 0) {
        $picture = $_FILES['picture'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/avif'];

        if (in_array($picture['type'], $allowedTypes)) {
            // Define target directory and file path
            $targetDir = __DIR__ . "/../uploads/";
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $fileName = uniqid() . basename($picture["name"]);
            $targetFile = $targetDir . $fileName;

            if (move_uploaded_file($picture["tmp_name"], $targetFile)) {
                $picturePath = "/uploads/" . $fileName;  // Store the relative file path
            } else {
                echo json_encode(["message" => "Image upload failed"]);
                return;
            }
        } else {
            echo json_encode(["message" => "Invalid image file"]);
            return;
        }
    }

    global $pdo;
    $sql = "INSERT INTO products (name, title, description, price, picture) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $title, $description, $price, $picturePath]);

    echo json_encode(["message" => "Product created successfully"]);
}

// Delete a product (Admins only)
function deleteProduct($id) {
    $user = verifyJWT();  // Verify JWT and get user details

    if ($user['role'] !== 'admin') {
        http_response_code(403);  // Forbidden
        echo json_encode(["message" => "Access denied. Admins only."]);
        exit();
    }

    global $pdo;
    
    // Fetch the current product from the database to get the image path
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        http_response_code(404);  // Not Found
        echo json_encode(["message" => "Product not found"]);
        exit();
    }

    // If the product has an associated image, delete the image file
    if ($product['picture'] && file_exists(__DIR__ . '/../' . $product['picture'])) {
        unlink(__DIR__ . '/../' . $product['picture']);  // Delete the image file
    }

    // Delete the product from the database
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    // Respond with success message
    echo json_encode(["message" => "Product and associated image deleted successfully"]);
}


function editProduct($id) {
    $user = verifyJWT();  // Verify JWT and get user details

    if ($user['role'] !== 'admin') {
        http_response_code(403);  // Forbidden
        echo json_encode(["message" => "Access denied. Admins only."]);
        exit();
    }

    // Fetch the current product from the database
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        http_response_code(404);  // Not Found
        echo json_encode(["message" => "Product not found"]);
        exit();
    }

    // Initialize variables
    $name = $product['name']; // Default to old value
    $title = $product['title']; // Default to old value
    $description = $product['description']; // Default to old value
    $price = $product['price']; // Default to old value
    $picturePath = $product['picture']; // Default to old image

    // If a new product name is provided, update it
    if (isset($_POST['name']) && !empty($_POST['name'])) {
        $name = $_POST['name'];
    }

    // If a new title is provided, update it
    if (isset($_POST['title']) && !empty($_POST['title'])) {
        $title = $_POST['title'];
    }

    // If a new description is provided, update it
    if (isset($_POST['description']) && !empty($_POST['description'])) {
        $description = $_POST['description'];
    }

    // If a new price is provided, update it
    if (isset($_POST['price']) && !empty($_POST['price'])) {
        $price = $_POST['price'];
    }

    // Handle the image upload
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
        // If a new image is uploaded, delete the old image first
        if ($product['picture'] && file_exists(__DIR__ . '/../' . $product['picture'])) {
            unlink(__DIR__ . '/../' . $product['picture']); // Delete old image
        }

        // Handle the new image upload
        $picture = $_FILES['picture'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/avif'];

        if (!in_array($picture['type'], $allowedTypes)) {
            http_response_code(400);  // Bad Request
            echo json_encode(["message" => "Invalid image file. Allowed types: jpeg, png, webp", 'image/avif']);
            return;
        }

        // Define target directory and file path for the new image
        $targetDir = __DIR__ . "/../uploads/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);  // Create directory if not exists
        }

        // Generate a unique file name for the image
        $fileName = uniqid() . basename($picture["name"]);
        $targetFile = $targetDir . $fileName;

        // Move the uploaded image to the target directory
        if (move_uploaded_file($picture["tmp_name"], $targetFile)) {
            $picturePath = "/uploads/" . $fileName;  // Store the relative file path
        } else {
            http_response_code(500);  // Internal Server Error
            echo json_encode(["message" => "Failed to move the uploaded file"]);
            return;
        }
    }

    // Update the product in the database with the new data
    $sql = "UPDATE products SET name = ?, title = ?, description = ?, price = ?, picture = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $title, $description, $price, $picturePath, $id]);

    // Respond with success message and the updated product data
    echo json_encode([
        "message" => "Product updated successfully",
        "updated_product" => [
            "id" => $id,
            "name" => $name,
            "title" => $title,
            "description" => $description,
            "price" => $price,
            "picture" => $picturePath
        ]
    ]);
}







?>
