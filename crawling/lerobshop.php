<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('update_status.php');
 // Database configuration
$dsn = 'mysql:host=localhost;dbname=crawling';
$username = 'username';
$password = 'pass!';

try {

    $pdo = new PDO($dsn, $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"));

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    for ($pageNumber = 1; $pageNumber <= 1; $pageNumber++) {

        $url = 'https://lerobshop.com/collections/scarpe-da-donna?page=' . $pageNumber;
        $website="www.lerobshop.com";
        $websitehttp="https://www.lerobshop.com";
        $options = [
            'http' => [
                'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36",
            ],
        ];
        
        $context = stream_context_create($options);
        sleep(mt_rand(11, 15));

        $html = file_get_contents($url, false, $context);
        
        if ($html === false) {
            die('Error fetching content: ' . error_get_last()['message']);
        }
        
        $dom = new DOMDocument();

        // Load the HTML content into the DOMDocument
        libxml_use_internal_errors(true); // Disable libxml errors
        $dom->loadHTML($html);
        libxml_clear_errors(); // Clear libxml errors

        // Create a DOMXPath
        $xpath = new DOMXPath($dom);

//            // Extract product information
           $products = [];
           $productNodes = $xpath->query('//div[contains(@class, "product-block__inner")]');
  
           foreach ($productNodes as $productNode) {
            $brandNode = $xpath->query('.//span[contains(@class, "vendor")]', $productNode)->item(0);
            $brand = $brandNode ? $brandNode->textContent : '-';

            $typeNode = $xpath->query('.//span[contains(@class, "type")]', $productNode)->item(0);
            $type = $typeNode ? $typeNode->textContent : '-';
        
            $discountNode = $xpath->query('.//span[contains(@class, "product-label")]', $productNode)->item(0);
            $discount = $discountNode ? trim($discountNode->textContent) : '0%';
        
            $priceNode = $xpath->query('.//div[contains(@class, "price")]', $productNode)->item(0);
            $oldPriceNode = $xpath->query('.//span[contains(@class, "was-price")]', $priceNode)->item(0);
            $oldPrice = $oldPriceNode ? $oldPriceNode->textContent : '-';
        
            $newPriceNode = $xpath->query('.//span[not(contains(@class, "was-price"))]', $priceNode)->item(0);
            $newPrice = $newPriceNode ? $newPriceNode->textContent : '-';
        
            $barcodeNode = $xpath->query('.//a[@class="image-inner"]/@aria-label', $productNode)->item(0);
            $barcode = $barcodeNode ? $barcodeNode->textContent : '-';

            $imgSrcNode = $xpath->query('.//img[@class="rimage__image"]/@src', $productNode)->item(0);
            $imgSrc = $imgSrcNode ? $imgSrcNode->textContent : '-';
            $imgSrc = explode('?', $imgSrc)[0];

            $image_url="https:".$imgSrc;

            $hrefNode = $xpath->query('.//a[@class="image-inner"]/@href', $productNode)->item(0);
            $href = $hrefNode ? $hrefNode->textContent : '-';
        
            $image = basename($imgSrc);
            $savePath = '/var/www/html/billing-system/PHP/Crawling/lerobshop/'; 
            $saveFilePath = $savePath . $image;
               sleep(mt_rand(11, 15));

               file_put_contents($saveFilePath, file_get_contents($image_url));
            $image_path="./lerobshop/".$image;
            $productUrl=$websitehttp.$href;

            $products[] = [
                'brand' => $brand,
                'type' => $type,
                'old_price' => $oldPrice,
                'discount' => $discount,
                'new_price' => $newPrice,
            ];

    echo '---------------------------<br>';
$product_url="https://lerobshop.com".$href;

            $status="unpublished";
            $gender="donna";
            $tagliaString="Check at product url";
            $table_name="lerobshop";
            $stmt = $pdo->prepare('INSERT INTO lerobshop (unique_id,image,site_name, product_url, brand,type,gender,size, old_price, discount, new_price,image_path,status,table_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?)');
            $stmt->execute([$barcode,$image_path,$website, $product_url, $brand, $type,$gender,$tagliaString, $oldPrice, $discount, $newPrice,$image_path,$status,$table_name]);

        }
     }
     updateStatus($pdo, $table_name);
} catch (Exception $e) {
    // Output any errors that occurred during the request
    echo 'Error: ' . $e->getMessage();
}
?>