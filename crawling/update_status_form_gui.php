<?php
require_once('update_status.php');

$dsn = 'mysql:host=localhost;dbname=crawling';
$username = 'username';
$password = 'pass!';

try {
    $pdo = new PDO($dsn, $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"));
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $selectedFiles = $_POST['files'];
    
    $response = array(); // Initialize an empty array to store responses
    
    if (!empty($selectedFiles)) {
        foreach ($selectedFiles as $tableName) {
             updateStatus($pdo, $tableName);
             $response[] = array('tableName' => $tableName, 'message' => "$tableName status got updated");
        }
    } else {
        $response[] = array('error' => "Please select the websites for which you want to update the status.");
    }
    
    // Encode the entire response array as JSON and echo it
    echo json_encode($response);
    
} catch (Exception $e) {
    // If an exception occurs, echo the error message as a JSON object
    echo json_encode(array('error' => 'Error: ' . $e->getMessage()));
}
?>
