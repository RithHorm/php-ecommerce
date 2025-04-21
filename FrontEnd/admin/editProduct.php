<?php 
    include('../../BackEnd/config/db.php');
?>




<!DOCTYPE html>
<html lang="en">
  <head>
  <?php include"include/head.php"?>
   
  </head>

  <body class="m-0 font-sans antialiased font-normal bg-white text-start text-base leading-default text-slate-500">
    <div class="container sticky top-0 z-sticky">
      <div class="flex flex-wrap -mx-3">
        <div class="w-full max-w-full px-3 flex-0"> 
        </div>
      </div>
    </div>
    <main class="mt-0 transition-all duration-200 ease-in-out">
      <section>
        <div class="relative flex items-center min-h-screen p-0 overflow-hidden bg-center bg-cover">
          <div class="container z-1">
            <div class="flex flex-wrap -mx-3">
              <div class="flex flex-col w-full max-w-full px-3 mx-auto lg:mx-0 shrink-0 md:flex-0 md:w-7/12 lg:w-5/12 xl:w-4/12">
                <div class="relative flex flex-col min-w-0 break-words bg-transparent border-0 shadow-none lg:py4 dark:bg-gray-950 rounded-2xl bg-clip-border">
                  <div class="p-6 pb-0 mb-0">
                    <h4 class="font-bold">Edit Product</h4>
                    
                  </div>
                  <div class="flex-auto p-6">
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
                    <form role="form" action="../../BackEnd/controllers/addProductCode.php" enctype="multipart/form-data" method="POST">
                      <div class="mb-4">
                        <label for="" class="font-semibold">Product ID</label><br>
                        <input type="text" value="<?= $result['id']?>" name="product_id" placeholder="ID" class="focus:shadow-primary-outline dark:bg-gray-950 dark:placeholder:text-white/80 dark:text-white/80 text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding p-3 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none" />
                      </div>
                      <div class="mb-4">
                        <label for="" class="font-semibold">Product Name</label><br>
                        <input type="text" value="<?= $result['name']?>" name="product_name" placeholder="Name" class="focus:shadow-primary-outline dark:bg-gray-950 dark:placeholder:text-white/80 dark:text-white/80 text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding p-3 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none" />                      
                    </div>
                      <div class="mb-4">
                        <label for="" class="font-semibold">Product Title</label><br>
                        <input type="text" value="<?= $result['title']?>" name="product_title" placeholder="Title" class="focus:shadow-primary-outline dark:bg-gray-950 dark:placeholder:text-white/80 dark:text-white/80 text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding p-3 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none" />                      
                    </div>
                      <div class="mb-4">
                        <label for="" class="font-semibold">Product Description</label><br>
                        <input type="text" value="<?= $result['description']?>" name="product_description" placeholder="Description" class="focus:shadow-primary-outline dark:bg-gray-950 dark:placeholder:text-white/80 dark:text-white/80 text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding p-3 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none" />                      
                    </div>
                    <div class="mb-4">
                        <label for="" class="font-semibold">Product Price</label><br>
                        <input type="number" value="<?= $result['price']?>" name="price" placeholder="Price" class="focus:shadow-primary-outline dark:bg-gray-950 dark:placeholder:text-white/80 dark:text-white/80 text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding p-3 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none" />                      
                    </div>
                    <div class="mb-4">
                        <label for="" class="font-semibold">Product Image</label><br>
                        <div class="w-[100px] h-[100px] bg-red-400">
                        <img class="h-19" src="../../BackEnd/<?= $result['picture']?>" value="<?= $result['picture']?>" alt="">
                        </div>
                        
                        <input type="hidden" name="prod_image" value="<?php echo $result['picture']?>" class="w-full px-2 py-1 outline-gray-100 border rounded-md border-gray-300 my-2">
                        <input type="file" name="product_img" placeholder="Image" class="focus:shadow-primary-outline dark:bg-gray-950 dark:placeholder:text-white/80 dark:text-white/80 text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding p-3 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none" />                      
                    </div>
                      <div class="mb-4">
                      <div class="text-center">
                        <button type="submit" name="editProductBtn" class="inline-block w-full px-16 py-3.5 mt-6 mb-0 font-bold leading-normal text-center text-white align-middle transition-all bg-blue-500 border-0 rounded-lg cursor-pointer hover:-translate-y-px active:opacity-85 hover:shadow-xs text-sm ease-in tracking-tight-rem shadow-md bg-150 bg-x-25">Save</button>
                      </div>
                      <div class="text-center">
                        <a href="product.php" class="inline-block w-full px-16 py-3.5 mt-6 mb-0 font-bold leading-normal text-center text-white align-middle transition-all bg-blue-500 border-0 rounded-lg cursor-pointer hover:-translate-y-px active:opacity-85 hover:shadow-xs text-sm ease-in tracking-tight-rem shadow-md bg-150 bg-x-25">Back</a>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <div class="absolute top-0 right-0 flex-col justify-center hidden w-6/12 h-full max-w-full px-3 pr-0 my-auto text-center flex-0 lg:flex">
                <div class="relative flex flex-col justify-center h-full bg-cover px-24 m-4 overflow-hidden bg-[url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/signin-ill.jpg')] rounded-xl ">
                  <span class="absolute top-0 left-0 w-full h-full bg-center bg-cover bg-gradient-to-tl from-blue-500 to-violet-500 opacity-60"></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>
  </body>
 <!-- plugin -->
 <?php include"include/pluggin.php"?>
</html>