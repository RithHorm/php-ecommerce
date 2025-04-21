<?php 
    include('../../BackEnd/config/db.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Add Product for Admin</title>
</head>
<body class="w-full h-screen flex justify-center items-center bg-gray-200">
    <?php
        if(isset($_GET['id']))
        {
            $product_id = $_GET['id'];

            $query = "SELECT * FROM products WHERE id = :prod_id LIMIT 1";
            $statement = $pdo->prepare($query);
            $data = [':prod_id' => $product_id];
            $statement->execute($data);

            $result = $statement->fetch(PDO::FETCH_ASSOC);
        }
    ?>
    <form action="../../BackEnd/controllers/addProductCode.php" method="POST" enctype="multipart/form-data" class="w-[700px] bg-white px-10 py-8 rounded-lg ">
        <h2 class="text-center text-xl font-semibold mb-3">Edit Product Detail</h2>
        <label for="" class="font-semibold">Product ID</label><br>
        <input type="text" name="product_id" value="<?= $result['id']?>" class="w-[100px] px-2 py-1 outline-gray-100 border rounded-md border-gray-300 my-2"><br>
        <label for="" class="font-semibold">Product Name</label><br>
        <input type="text" name="prod_name" value="<?= $result['name']?>" placeholder="Product Name" class="w-full px-2 py-1 outline-gray-100 border rounded-md border-gray-300 my-2">
        <div class="flex flex-2">
       <div class="flex-1">
       <label for="" class="font-semibold">Product Title</label><br>
       <input type="text" name="prod_title" value="<?= $result['title']?>" placeholder="Product Title" class="w-[95%] px-2 py-1 outline-gray-100 border rounded-md border-gray-300 my-2">
       </div>
       <div class="flex-1">
       <label for="" class="font-semibold">Price</label><br>
       <input type="number" name="prod_price" value="<?= $result['price']?>" placeholder="Price $$" class="w-full px-2 py-1 outline-gray-100 border rounded-md border-gray-300 my-2">
       </div>
        </div>
        <label for="" class="font-semibold">Description</label><br>
        <input type="text" name="prod_desc" value="<?= $result['description']?>" placeholder="Product Description" class="w-full px-2 py-1 outline-gray-100 border rounded-md border-gray-300 my-2">
        <label for="" class="font-semibold">Product Image</label><br>
        <!-- img -->
        <img class="w-[80px]" src="../../../BackEnd/uploads/<?= $result['picture']?>" value="<?= $result['picture']?>" alt="">
        <input type="hidden" name="prod_image" value="<?php echo $result['picture']?>" class="w-full px-2 py-1 outline-gray-100 border rounded-md border-gray-300 my-2">
        <input type="file" name="new_image" class="w-full px-2 py-1 outline-gray-100 border rounded-md border-gray-300 my-2">
        <div class="flex flex-2">
       <div class="flex-1">
       <label for="" class="font-semibold">Create Date</label><br>
       <input type="" name="prod_create" value="<?=$result['created_at']?>" class="w-[95%] px-2 py-1 outline-gray-100 border rounded-md border-gray-300 my-2">
       </div>
       <div class="flex-1">
       <label for="" class="font-semibold">Update Date</label><br>
       <input type="" name="prod_update" value="<?=$result['updated_at']?>" class="w-full px-2 py-1 outline-gray-100 border rounded-md border-gray-300 my-2">
       </div>
        </div>
        <button type="submit" name="editProductBtn" class="mt-9 rounded-3xl w-full bg-blue-400 py-2 text-white font-semibold
        ">Save Product</button>
    </form>
</body>
</html>