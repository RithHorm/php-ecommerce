

<?php
session_start(); 
    include('../config/db.php');



    // edit product / update product
    // if(isset($_POST['editProductBtn'])){
    //     $product_id = $_POST['product_id'];
    //     $prod_name = $_POST['prod_name'];
    //     $prod_title = $_POST['prod_title'];
    //     $prod_price = $_POST['prod_price'];
    //     $prod_desc = $_POST['prod_desc'];
    //     $prod_image = $_POST['prod_image'];
    //     $prod_newimage = $_FILE['new_image']['name'];
    //     $prod_create = $_POST['prod_create'];
    //     $prod_update = $_POST['prod_update'];

    //     if($prod_newimage != ''){
    //         $updateImg = $prod_newimage;
    //     }
    //     else{
    //         $updateImg = $prod_image;
    //     }

    //     try{
    //         $query = "UPDATE products SET name=:prod_name, title=:prod_title, price=:prod_price, description=:prod_desc, picture=:prod_image, created_at=:prod_create, updated_at=:prod_update WHERE id =:prod_id LIMIT 1";
    //         $statement = $pdo->prepare($query);
            
    //         $data = [
    //             'prod_name' => $prod_name,
    //             'prod_title' => $prod_title,
    //             'prod_price' => $prod_price,
    //             'prod_desc' => $prod_desc,
    //             'prod_image' => $prod_image,
    //             'prod_create' => $prod_create,
    //             'prod_update' => $prod_update,
    //             'prod_id' => $product_id,
    //         ];
    //         $query_execute = $statement->execute($data);
    //         if($query_execute){
    //             $_SESSION['message'] = "Update Successfully";
    //             header('location: ../../FrontEnd/admin/product.php');
    //             exit(0);
    //             // include('../../FrontEnd/admin/product.php');
    //         }else{
    //             $_SESSION['message'] = "Update Failed";
    //             header('location: ../../FrontEnd/admin/product.php');
    //             exit(0);
    //         }
    //     }
    //     catch(PDOException $e){
    //         echo $e->getMessage();
    //     }
    // }
  
if (isset($_POST['editProductBtn'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_title = $_POST['product_title'];
    $product_description = $_POST['product_description'];
    $product_price = $_POST['price'];
    $old_image = $_POST['prod_image'];

    $created_at = date("Y-m-d H:i:s"); // You can adjust this if needed
    $updated_at = date("Y-m-d H:i:s");

    // Handle image upload
    $new_image = $_FILES['product_img']['name'];
    $image_tmp = $_FILES['product_img']['tmp_name'];

    if (!empty($new_image)) {
        $upload_path = 'uploads/' . time() . '_' . $new_image; // Unique name
        move_uploaded_file($image_tmp, '../../BackEnd/' . $upload_path);
        $final_image = $upload_path;
    } else {
        $final_image = $old_image;
    }

    try {
        $query = "UPDATE products 
                  SET name = :name, 
                      title = :title, 
                      price = :price, 
                      description = :description, 
                      picture = :picture, 
                      created_at = :created, 
                      updated_at = :updated 
                  WHERE id = :id LIMIT 1";

        $statement = $pdo->prepare($query);

        $data = [
            ':name' => $product_name,
            ':title' => $product_title,
            ':price' => $product_price,
            ':description' => $product_description,
            ':picture' => $final_image,
            ':created' => $created_at,
            ':updated' => $updated_at,
            ':id' => $product_id
        ];

        $query_execute = $statement->execute($data);

        if ($query_execute) {
            $_SESSION['message'] = "Update Successfully";
        } else {
            $_SESSION['message'] = "Update Failed";
        }
        header('Location: ../../FrontEnd/admin/product.php');
        exit(0);

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}






    // if(isset($_POST['addProductBtn'])){
    //     $prod_name = $_POST['prod_name'];
    //     $prod_title = $_POST['prod_title'];
    //     $prod_price = $_POST['prod_price'];
    //     $prod_desc = $_POST['prod_desc'];
    //     $prod_image = $_POST['prod_image'];
    //     $prod_create = $_POST['prod_create'];
    //     $prod_update = $_POST['prod_update'];

    //     $query = "INSERT INTO products (name, title, price, description, picture, created_at, updated_at) VALUEs (:prod_name, :prod_title, :prod_price, :prod_desc, :prod_image, :prod_create, :prod_update)";
    //     $qurey_run = $pdo->prepare($query);

    //     $data = [
    //         ':prod_name' => $prod_name,
    //         ':prod_title' => $prod_title,
    //         ':prod_price' => $prod_price,
    //         ':prod_desc' => $prod_desc,
    //         ':prod_image' => $prod_image,
    //         ':prod_create' => $prod_create,
    //         ':prod_update' => $prod_update,
    //     ];
    //     $query_execute = $qurey_run->execute($data);
        

    //     if($query_execute){
    //         $_SESSION['message'] = "Inserted Successfully";
    //         header('location: ../../FrontEnd/admin/product.php');
    //         exit(0);
    //         // include('../../FrontEnd/admin/product.php');
    //     }else{
    //         $_SESSION['message'] = "Inserted Failed";
    //         header('location: ../../FrontEnd/admin/product.php');
    //         exit(0);
    //     }
    // }

   

if (isset($_POST['addProductBtn'])) {
    $prod_name = $_POST['prod_name'];
    $prod_title = $_POST['prod_title'];
    $prod_price = $_POST['prod_price'];
    $prod_desc = $_POST['prod_desc'];
    $prod_create = date('Y-m-d H:i:s'); // Automatically set current timestamp
    $prod_update = date('Y-m-d H:i:s');

    // Handle Image Upload
    $target_dir = "../uploads/"; // Folder to store images
    
    $image_name = $_FILES['prod_image']['name']; // Get image name
    $image_tmp = $_FILES['prod_image']['tmp_name']; // Temporary path

    // Generate unique name to avoid overwriting
    $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
    $new_image_name = uniqid("IMG_", true) . '.' . $image_ext;
    $target_file = $target_dir . $new_image_name;

    if (move_uploaded_file($image_tmp, $target_file)) {
        // Insert into Database
        $query = "INSERT INTO products (name, title, price, description, picture, created_at, updated_at) 
                  VALUES (:prod_name, :prod_title, :prod_price, :prod_desc, :prod_image, :prod_create, :prod_update)";
        $query_run = $pdo->prepare($query);

        $data = [
            ':prod_name' => $prod_name,
            ':prod_title' => $prod_title,
            ':prod_price' => $prod_price,
            ':prod_desc' => $prod_desc,
            ':prod_image' => $new_image_name, // Store new image name in DB
            ':prod_create' => $prod_create,
            ':prod_update' => $prod_update,
        ];
        
        $query_execute = $query_run->execute($data);

        if ($query_execute) {
            $_SESSION['message'] = "Product Added Successfully!";
        } else {
            $_SESSION['message'] = "Database Insertion Failed!";
        }
    } else {
        $_SESSION['message'] = "Image Upload Failed!";
    }

    header('location: ../../FrontEnd/admin/product.php');
    exit(0);
}




?>