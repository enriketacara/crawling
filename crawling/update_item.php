<?php
/*Get data from published table,get the url of the product,and the id of the row,
and then get content of this product and update the row of the table published and add the updated data*/
error_reporting(0);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
error_reporting(E_ALL);
ini_set("error_reporting", E_ALL);
error_reporting(E_ALL & ~E_NOTICE);
// Include your database connection logic here (replace with your actual connection details)
$dsn = 'mysql:host=localhost;dbname=crawling';
$username = 'username';
$password = 'pass';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo = new PDO($dsn, $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"));
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

// Process the data sent from the client
$id=  $_POST['id'];
$image=  $_POST['image_path'];
$site_name=  $_POST['site_name'];
$product_url= $_POST['product_url'];
$brand= $_POST['brand'];
$unique_id= $_POST['unique_id'];
$type=  $_POST['type'];
$gender= $_POST['gender'];
$size= $_POST['size'];
$old_price=  $_POST['old_price'];
$discount=  $_POST['discount'];
$new_price=  $_POST['new_price'];
$final_buying_price=  $_POST['finalBuyingPrice'];
$final_selling_price=  $_POST['finalSellingPrice'];
$table_name=  $_POST['table_name'];
var_dump($table_name);
//get content of the product url here
if($table_name=="off_market"){
    try {
    $options = [
        'http' => [
            'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36",
        ],
    ];
    $context = stream_context_create($options);
   // Make a request to the URL in $product_all_data
   $productDetailHtml = file_get_contents($product_url, false, $context);
   if ($productDetailHtml === false) {
    die('Error fetching content: ' . error_get_last()['message']);
}  
   $productDetailDom = new DOMDocument();
   libxml_use_internal_errors(true);
   $productDetailDom->loadHTML($productDetailHtml);
   libxml_clear_errors();
   $productDetailXpath = new DOMXPath($productDetailDom);
    
   $http_response_code = $http_response_header[0]; 
   if (strpos($http_response_code, '410') !== false) {
       echo "The requested resource is permanently gone (410 Gone).";
       $updated_sizes = 'Deleted - not found at website';
   } else {
   $sizes = [];
   $sizeNodes = $productDetailXpath->query('//div[@class="variations"]/fieldset[@class="product-form__input product-form__input--block"]/label/span');
   foreach ($sizeNodes as $sizeNode) {
    $size = $sizeNode ? $sizeNode->textContent : 'N/A -no sizes available ';
    if (!in_array($size, $sizes)) {
        $sizes[] = $size;
    }
   }
   $updated_sizes = implode(', ', $sizes);
   }
} catch (Exception $e) {
    // Handle the case where the URL does not exist
    $updated_sizes = 'Deleted - not found at website';

}
   }else if($table_name=="calzatureginevra"){
    try {
   // Make a request to the URL in $product_all_data
              $productDetailHtml = file_get_contents($product_url);
              $productDetailDom = new DOMDocument();
              libxml_use_internal_errors(true);
              $productDetailDom->loadHTML($productDetailHtml);
              libxml_clear_errors();
              $productDetailXpath = new DOMXPath($productDetailDom);

              $http_response_code = $http_response_header[0]; 
              if (strpos($http_response_code, '410') !== false) {
                  echo "The requested resource is permanently gone (410 Gone).";
                  $updated_sizes = 'Deleted - not found at website';
              } else {
              $sizes = [];
              $sizeNodes = $productDetailXpath->query('//div[@id="taglia_wrap"]/a[@class="taglia"]');
              var_dump($sizeNodes);
              foreach ($sizeNodes as $sizeNode) {
                $size = $sizeNode ? $sizeNode->textContent : 'N/A -no sizes available';

                  $sizes[] = $size;
              }
              $updated_sizes = implode(', ', $sizes);
            }
            } catch (Exception $e) {
                // Handle the case where the URL does not exist
                $updated_sizes = 'Deleted - not found at website';
              
            }
} else if($table_name=="lerobshop"){
    try {
   // Make a request to the URL in $product_all_data
            //   $productDetailHtml = file_get_contents($product_url);
            //   $productDetailDom = new DOMDocument();
            //   libxml_use_internal_errors(true);
            //   $productDetailDom->loadHTML($productDetailHtml);
            //   libxml_clear_errors();
            //   $productDetailXpath = new DOMXPath($productDetailDom);
  
              $http_response_code = $http_response_header[0]; 
              if (strpos($http_response_code, '410') !== false) {
                  echo "The requested resource is permanently gone (410 Gone).";
                  $updated_sizes = 'Deleted - not found at website';
              } else {
            //   $sizes = [];
            //   $sizeNodes = $productDetailXpath->query('//div[@id="taglia_wrap"]/a[@class="taglia"]');
            //   foreach ($sizeNodes as $sizeNode) {
            //     $size = $sizeNode ? $sizeNode->textContent : 'N/A';

            //       $sizes[] = $size;
            //   }
            //   $updated_sizes = implode(', ', $sizes);
             
            // } catch (Exception $e) {
            //     // Handle the case where the URL does not exist
            //     $updated_sizes = 'N/A';
            }
                $updated_sizes="Check at product url";
            } catch (Exception $e) {
                // Handle the case where the URL does not exist
                $updated_sizes = 'Deleted - not found at website';
              
            }
          
} 
// Update the values in the database
try {
    $stmt = $pdo->prepare('UPDATE published SET size = ? WHERE id = ?');
    $stmt->execute([$updated_sizes, $id]);
    
    $sql = "UPDATE $table_name SET size = ? WHERE unique_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$updated_sizes, $unique_id]);
    
    echo json_encode(['success' => true, 'message' => 'Size updated successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error updating data: ' . $e->getMessage()]);
}

?>

