<?php
require '../../BackEnd/config/db.php'; // assumes $pdo is defined

if (!isset($_GET['id'])) {
    die('Slider ID not provided.');
}

$stmt = $pdo->prepare("SELECT * FROM slider WHERE id = ?");
$stmt->execute([$_GET['id']]);
$slider = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$slider) {
    die('Slider not found.');
}
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
                    <h4 class="font-bold">Edit Slider</h4>
                    
                  </div>
                  <div class="flex-auto p-6">
                    <form role="form" action="../../BackEnd/controllers/slideController.php" enctype="multipart/form-data" method="POST">
                      <div class="mb-4">
                        <!-- <input type="hidden" name="action" value="add" class="focus:shadow-primary-outline dark:bg-gray-950 dark:placeholder:text-white/80 dark:text-white/80 text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding p-3 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none" /> -->
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" value="<?= $slider['id'] ?>">
                      </div>
                      <div class="mb-4">
                      <label>Title</label>
                        <input type="text" name="title" value="<?= htmlspecialchars($slider['title']) ?>" required class="focus:shadow-primary-outline dark:bg-gray-950 dark:placeholder:text-white/80 dark:text-white/80 text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding p-3 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none" />
                      </div>
                      <div class="mb-4">
                      <label>Sub Title</label>
                        <input type="text" name="subtitle" value="<?= htmlspecialchars($slider['subtitle']) ?>" required placeholder="Sub Title" class="focus:shadow-primary-outline dark:bg-gray-950 dark:placeholder:text-white/80 dark:text-white/80 text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding p-3 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none" />
                      </div>
                      <div class="mb-4">
                        <!-- <input type="number" name="prod_price" placeholder="Price" class="focus:shadow-primary-outline dark:bg-gray-950 dark:placeholder:text-white/80 dark:text-white/80 text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding p-3 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none" /> -->
                        <label>Description</label>
                        <textarea style="height: 120px;" class="focus:shadow-primary-outline dark:bg-gray-950 dark:placeholder:text-white/80 dark:text-white/80 text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding p-3 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none" name="description" required><?= htmlspecialchars($slider['description']) ?></textarea>
                      </div>
                      <div class="mb-4">
                        <label class="mb-4">Current Image</label>
                        <img src="http://localhost:8080/php-rest-api-latest/backend/sliderUploads/<?= htmlspecialchars($slider['image']) ?>" alt="Slider Image" style="width: 365px; height: 150px;">
                      </div>
                      <div class="mb-4">
                        <label>Upload Image (Optional)</label>
                        <input type="file" name="image" placeholder="Upload Image" class="mt-2 focus:shadow-primary-outline dark:bg-gray-950 dark:placeholder:text-white/80 dark:text-white/80 text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding p-3 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none" />
                      </div>
                      <div class="mb-4">
                        <input type="text" name="link" value="<?= htmlspecialchars($slider['link']) ?>" class="focus:shadow-primary-outline dark:bg-gray-950 dark:placeholder:text-white/80 dark:text-white/80 text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding p-3 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none" />
                      </div>
                      <!-- <div class="mb-4">
                        <input type="text" name="link" placeholder="Image Link" class="focus:shadow-primary-outline dark:bg-gray-950 dark:placeholder:text-white/80 dark:text-white/80 text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding p-3 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none" />
                      </div> -->
                      <label>Slider Status: </label>
                      <!-- <select name="status" required class="mb-4">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select> -->
                    <select name="status" required>
                        <option value="1" <?= $slider['status'] == 1 ? 'selected' : '' ?>>Active</option>
                        <option value="0" <?= $slider['status'] == 0 ? 'selected' : '' ?>>Inactive</option>
                    </select>
                      <!-- <div class="mb-4"> -->
                        <div class="text-center">
                            <button type="submit" name="addProductBtn" class="inline-block w-full px-16 py-3.5 mt-6 mb-0 font-bold leading-normal text-center text-white align-middle transition-all bg-blue-500 border-0 rounded-lg cursor-pointer hover:-translate-y-px active:opacity-85 hover:shadow-xs text-sm ease-in tracking-tight-rem shadow-md bg-150 bg-x-25">Add</button>
                        </div>
                        <div class="text-center">
                            <a href="slider.php" class="inline-block w-full px-16 py-3.5 mt-6 mb-0 font-bold leading-normal text-center text-white align-middle transition-all bg-blue-500 border-0 rounded-lg cursor-pointer hover:-translate-y-px active:opacity-85 hover:shadow-xs text-sm ease-in tracking-tight-rem shadow-md bg-150 bg-x-25">Back</a>
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

