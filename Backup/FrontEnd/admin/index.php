<?php
session_start();

// Include function.php to handle JWT validation
require_once __DIR__ . '/functions.php'; // Include the function file

// Call the function to verify JWT and get user data
$user = verifyJWTFromSession();

// If the user is not authenticated or not an admin, redirect to sign-in page
if ($user === null || $user->role !== 'admin') {
    header("Location: sign-in.php");
    exit();
}
?>
<?php include "../admin/include/session.php"; ?>  
<!DOCTYPE html>
<html>
  <head>
    <?php include"include/head.php"?>
  </head>

  <body
    class="m-0 font-sans text-base antialiased font-normal dark:bg-slate-900 leading-default bg-gray-50 text-slate-500"
  >
    <div class="absolute w-full bg-blue-500 dark:hidden min-h-75"></div>
    <!-- sidenav  -->
    <?php include"include/sidenav.php"?>
    <!-- end sidenav -->

    <!-- main -->
    <?php include"include/main.php"?>
    <!-- end main -->
    
    <!-- option bar -->
    <?php include"include/optionbar.php"?>

    <!-- end optionbar -->
  </body>
  <!-- plugin -->
  <?php include"include/pluggin.php"?>

</html>
