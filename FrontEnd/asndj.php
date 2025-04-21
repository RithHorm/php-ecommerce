<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
        <img src="" alt="">
        <?php 
        $sql = "SELECT image_path FROM images WHERE id = 1"; // Modify query as needed
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $imagePath = $row['image_path'];
        
            // Debugging output
            echo "Fetched Path: " . $imagePath;
        } else {
            die("No image found in the database.");
        }
        ?>
        
        ?>
    </div>
</body>
</html>