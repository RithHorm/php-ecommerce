<?php
require '../config/db.php'; 

// ADD Slider
if (isset($_POST['action']) && $_POST['action'] === 'add') {
    // Handle image upload
    $imageName = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Generate a unique name for the image file to prevent overwriting
        $imageName = time() . '_' . basename($_FILES['image']['name']);

        // Move the uploaded file to the 'uploads/' directory
        move_uploaded_file($_FILES['image']['tmp_name'], '../sliderUploads/' . $imageName);
        
    }
    

    // Insert slider details into the database, including the image file name
    $stmt = $pdo->prepare("INSERT INTO slider (title, subtitle, description, image, link, status, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->execute([
        $_POST['title'],
        $_POST['subtitle'],
        $_POST['description'],
        $imageName,  // Store the file name in the database
        $_POST['link'],
        $_POST['status']
    ]);

    header('Location: ../../FrontEnd/admin/slider.php');
    exit;
}


// EDIT Slider
// if (isset($_POST['action']) && $_POST['action'] === 'edit') {
//     $stmt = $pdo->prepare("UPDATE slider SET title = ?, subtitle = ?, description = ?, image = ?, link = ?, status = ? WHERE id = ?");
//     $stmt->execute([
//         $_POST['title'],
//         $_POST['subtitle'],
//         $_POST['description'],
//         $_POST['image'],
//         $_POST['link'],
//         $_POST['status'],
//         $_POST['id']
//     ]);
//     header('Location: ../../FrontEnd/admin/slider.php');
//     exit;
// }
if ($_POST['action'] === 'edit') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $subtitle = $_POST['subtitle'];
    $description = $_POST['description'];
    $link = $_POST['link'];
    $status = $_POST['status'];

    // Get the existing image
    $stmt = $pdo->prepare("SELECT image FROM slider WHERE id = ?");
    $stmt->execute([$id]);
    $existingSlider = $stmt->fetch(PDO::FETCH_ASSOC);
    $image = $existingSlider['image']; // Default to existing image

    // Handle new image upload if a new one is provided
    if (!empty($_FILES['image']['name'])) {
        $imageName = time() . '_' . $_FILES['image']['name'];
        $imageTmp = $_FILES['image']['tmp_name'];
        $uploadPath = '../../BackEnd/sliderUploads/' . $imageName;

        if (move_uploaded_file($imageTmp, $uploadPath)) {
            $image = $imageName;
        }
    }

    // Update the database
    $stmt = $pdo->prepare("UPDATE slider SET title = ?, subtitle = ?, description = ?, image = ?, link = ?, status = ? WHERE id = ?");
    $stmt->execute([$title, $subtitle, $description, $image, $link, $status, $id]);

    header("Location: ../../FrontEnd/admin/slider.php");
    exit;
}


// DELETE Slider
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM slider WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    header('Location: ../../FrontEnd/admin/slider.php');
    exit;
}
?>
