<?php
error_reporting(0);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
error_reporting(E_ALL);
ini_set("error_reporting", E_ALL);
error_reporting(E_ALL & ~E_NOTICE);

$dsn = 'mysql:host=localhost;dbname=crawling';
$username = 'erald';
$password = 'erald1232!';

// Retrieve the POST data containing the row data
$rowData = json_decode($_POST['rowData'], true);

try {
    // Create a PDO instance
    $pdo = new PDO($dsn, $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"));
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Insert the row data into another table
    $stmt = $pdo->prepare("
        INSERT INTO ordered (id,unique_id,image, site_name, product_url, brand, type, gender, size,old_price,discount,new_price, final_buying_price, final_selling_price,ordered_size) 
        VALUES ( :id,:unique_id, :image_path, :site_name, :product_url, :brand, :type, :gender, :size,:old_price,:discount,:new_price, :final_buying_price, :final_selling_price, :ordered_size)
    ");

    // Bind parameters
    
    $stmt->bindParam(':id', $rowData['id']);
    $stmt->bindParam(':image_path', $rowData['image']);
    $stmt->bindParam(':site_name', $rowData['site_name']);
    $stmt->bindParam(':product_url', $rowData['product_url']);
    $stmt->bindParam(':brand', $rowData['brand']);
    $stmt->bindParam(':unique_id', $rowData['unique_id']);
    $stmt->bindParam(':type', $rowData['type']);
    $stmt->bindParam(':gender', $rowData['gender']);
    $stmt->bindParam(':size', $rowData['size']);
    $stmt->bindParam(':old_price', $rowData['old_price']);
    $stmt->bindParam(':discount', $rowData['discount']);
    $stmt->bindParam(':new_price', $rowData['new_price']);
    $stmt->bindParam(':final_buying_price', $rowData['final_buying_price']);
    $stmt->bindParam(':final_selling_price', $rowData['final_selling_price']);
    $stmt->bindParam(':ordered_size', $rowData['ordered_size']);

    // Execute the statement
    $stmt->execute();

    // Return success message
    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    // Return error message
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
