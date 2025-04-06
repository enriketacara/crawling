<?php
// Database configuration
$dsn = 'mysql:host=localhost;dbname=crawling';
$username = 'username';
$password = 'pass!';

try {
    // Create a PDO instance
    $pdo = new PDO($dsn, $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"));
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get unique_id and table_name from the request
    $uniqueId = $_POST['unique_id'];
    $table_name = $_POST['table_name'];
    $product_url = $_POST['product_url'];
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
    // Update the size column
    $stmt = $pdo->prepare("UPDATE $table_name SET size = :size WHERE unique_id = :unique_id");
    $stmt->bindParam(':size', $updated_sizes); // Assuming $size is the new size value
    $stmt->bindParam(':unique_id', $uniqueId);
    $stmt->execute();
    //Update published
    // Update the size column
    $stmt = $pdo->prepare("UPDATE published SET size = :size WHERE unique_id = :unique_id");
    $stmt->bindParam(':size', $updated_sizes); // Assuming $size is the new size value
    $stmt->bindParam(':unique_id', $uniqueId);
    $stmt->execute();

    // Return success response
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    // Handle errors
    echo json_encode(['error' => $e->getMessage()]);
}
?>
