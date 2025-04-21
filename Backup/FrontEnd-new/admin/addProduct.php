<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Add Product for Admin</title>
</head>
<body class="w-full h-screen flex justify-center items-center bg-gray-200">
    <form action="../../BackEnd/controllers/addProductCode.php" method="POST" enctype="multipart/form-data" class="w-[700px] bg-white px-10 py-8 rounded-lg ">
        <h2 class="text-center text-xl font-semibold mb-3">Add New Product</h2>
        <label for="" class="font-semibold">Product Name</label><br>
        <input type="text" name="prod_name" placeholder="Product Name" class="w-full px-2 py-1 outline-gray-100 border rounded-md border-gray-300 my-2">
        <div class="flex flex-2">
       <div class="flex-1">
       <label for="" class="font-semibold">Product Title</label><br>
       <input type="text" name="prod_title" placeholder="Product Title" class="w-[95%] px-2 py-1 outline-gray-100 border rounded-md border-gray-300 my-2">
       </div>
       <div class="flex-1">
       <label for="" class="font-semibold">Price</label><br>
       <input type="number" name="prod_price" placeholder="Price $$" class="w-full px-2 py-1 outline-gray-100 border rounded-md border-gray-300 my-2">
       </div>
        </div>
        <label for="" class="font-semibold">Description</label><br>
        <input type="text" name="prod_desc" placeholder="Product Description" class="w-full px-2 py-1 outline-gray-100 border rounded-md border-gray-300 my-2">
        <label for="" class="font-semibold">Product Image</label><br>
        <input type="file" name="prod_image" class="w-full px-2 py-1 outline-gray-100 border rounded-md border-gray-300 my-2">
        <div class="flex flex-2">
       <div class="flex-1">
       <label for="" class="font-semibold">Create Date</label><br>
       <input type="date" name="prod_create" class="w-[95%] px-2 py-1 outline-gray-100 border rounded-md border-gray-300 my-2">
       </div>
       <div class="flex-1">
       <label for="" class="font-semibold">Update Date</label><br>
       <input type="date" name="prod_update" class="w-full px-2 py-1 outline-gray-100 border rounded-md border-gray-300 my-2">
       </div>
        </div>
        <button type="submit" name="addProductBtn" class="mt-9 rounded-3xl w-full bg-blue-400 py-2 text-white font-semibold
        ">Add Product</button>
    </form>
</body>
</html>