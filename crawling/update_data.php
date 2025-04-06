<?php
// Include your database connection logic here (replace with your actual connection details)
$dsn = 'mysql:host=localhost;dbname=crawling';
$username = 'username';
$password = 'pass!';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

// Process the data sent from the client
$id = $_POST['id'];
$finalBuyingPrice = $_POST['final_buying_price'];
$finalSellingPrice = $_POST['final_selling_price'];
$ordered_size = $_POST['ordered_size'];

// Update the values in the database
try {
    $stmt = $pdo->prepare('UPDATE published SET final_buying_price = ?, final_selling_price = ? ,ordered_size = ? WHERE id = ?');
    $stmt->execute([$finalBuyingPrice, $finalSellingPrice, $ordered_size, $id]);
    
    echo json_encode(['success' => true, 'message' => 'Data updated successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error updating data: ' . $e->getMessage()]);
}
?>
