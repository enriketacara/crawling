
<?php
require_once('update_status.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Database configuration
$dsn = 'mysql:host=localhost;dbname=crawling';
$username = 'erald';
$password = 'erald1232!';

try {
    $pdo = new PDO($dsn, $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"));
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Loop through the range of values for the 's' parameter (assuming there are multiple products on the page)
    for ($pageNumber = 1; $pageNumber <= 1; $pageNumber++) {
        // Construct the URL with the current 's' parameter value
        $website="www.off-market.it";
        $websitehttp="https://www.off-market.it";
        $url = 'https://www.off-market.it/collections/all?page=' . $pageNumber;
        
        $options = [
            'http' => [
                'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36",
            ],
        ];
        sleep(mt_rand(11, 15));

        $context = stream_context_create($options);
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

        // Extract product information
        $products = [];
        $productNodes = $xpath->query('//li[@class="column"]//product-card');

        foreach ($productNodes as $productNode) {
            $imageNode = $xpath->query('.//img[@class="product-primary-image"]/noscript/img/@src', $productNode)->item(0);
            $imageSrc = $imageNode ? $imageNode->textContent : '-';
        
           // Extract brand
           $brandNode = $xpath->query('.//div[@class="product-card-vendor"]/a', $productNode)->item(0);
           $brand = $brandNode ? $brandNode->textContent : '-';

            // Extract product href
            $productHref = $xpath->query('.//a[@class="product-card-title"]/@href', $productNode)->item(0);
            $productHref = $productHref ? $productHref->textContent : '-';

            // Extract product title
            $productTitle = $xpath->query('.//a[@class="product-card-title"]/text()', $productNode)->item(0);
            $productTitle = $productTitle ? $productTitle->textContent : '-';

            // Extract price, amount now, previous amount, and discount
            $priceNode = $xpath->query('.//div[@class="price"]', $productNode)->item(0);
            $priceOutlet = $xpath->query('.//div[@class="outlet"]//span[@class="amount"]', $priceNode)->item(0);
            $priceOutlet = $priceOutlet ? $priceOutlet->textContent : '-';
            
            $priceDetail = $xpath->query('.//div[@class="detail"]//span[@class="amount text_prod_small"]', $priceNode)->item(0);
            $priceDetail = $priceDetail ? $priceDetail->textContent : $priceOutlet;
            
            $discountNode = $xpath->query('.//span[@class="onsale"]', $priceNode)->item(0);
            $discount = $discountNode ? $discountNode->textContent : '0%';

            $productUrl=$websitehttp.$productHref;

            $options2 = [
                'http' => [
                    'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36",
                ],
            ];
            sleep(mt_rand(11, 15));

            $context2 = stream_context_create($options2);
           // Make a request to the URL in $product_all_data
           $productDetailHtml = file_get_contents($productUrl, false, $context2);
           if ($productDetailHtml === false) {
            die('Error fetching content: ' . error_get_last()['message']);
        }  
           $productDetailDom = new DOMDocument();
           libxml_use_internal_errors(true);
           $productDetailDom->loadHTML($productDetailHtml);
           libxml_clear_errors();
           $productDetailXpath = new DOMXPath($productDetailDom);
           
           $sizes = [];
           $sizeNodes = $productDetailXpath->query('//div[@class="variations"]/fieldset[@class="product-form__input product-form__input--block"]/label/span');
           foreach ($sizeNodes as $sizeNode) {
               $size = $sizeNode->textContent;
               if (!in_array($size, $sizes)) {
                   $sizes[] = $size;
               }
           }

           $sizesString = implode(', ', $sizes);
           $breadcrumbsNode = $productDetailXpath->query('//nav[@class="breadcrumbs"]')->item(0);
           $breadcrumbsText = $breadcrumbsNode ? $breadcrumbsNode->textContent : '';
           
           // Check for the presence of "donna" or "uomo" (case-insensitive) in the breadcrumbs
           if (stripos($breadcrumbsText, 'donna') !== false) {
               $gender = 'donna';
           } elseif (stripos($breadcrumbsText, 'uomo') !== false) {
               $gender = 'uomo';
           } else {
               $gender = '-';
           }
         
           $dataMsrcNode = $productDetailXpath->query('//a[@class="product-single__media-zoom"]/@data-msrc')->item(0);
           $dataMsrc = $dataMsrcNode ? $dataMsrcNode->textContent : '';

           $imageUrl = parse_url($dataMsrc, PHP_URL_SCHEME) . "https://" . parse_url($dataMsrc, PHP_URL_HOST) . parse_url($dataMsrc, PHP_URL_PATH);
           $image = basename($imageUrl);

           $skuNode = $productDetailXpath->query('//span[contains(@class, "sku_product")]')->item(0);
           $skuContent = $skuNode ? trim(str_replace('SKU:', '', $skuNode->textContent)) : '-';


           $savePath = '/var/www/html/billing-system/PHP/Crawling/off_market/'; // Set the directory where you want to save the image
           // Combine the save path and file name
           $saveFilePath = $savePath . $image;
            sleep(mt_rand(11, 15));

            // Download the image
           file_put_contents($saveFilePath, file_get_contents($imageUrl));
           $image_path="./off_market/".$image;
            $products[] = [
                'image_src' => $dataMsrc,
                'product_href' => $productUrl,
                'brand' => $brand,
                'product_title' => $productTitle,
                'size' => $sizesString,
                'gender' => $gender,
                'price_outlet' => $priceOutlet,
                'price_detail' => $priceDetail,
                'discount' => $discount,
            ];
          
            // echo 'Marca: ' . $brand . '<br>';
            // echo 'Image: ' . $imageUrl . '<br>';
            // echo 'Prodotto: ' . $productTitle . '<br>';
            // echo 'Product All Data: ' . $productUrl . '<br>';
            //  echo 'Taglia: ' . $sizesString . '<br>';
            // echo 'Gender: ' . $gender . '<br>';
            // echo 'Previous Price: ' . $priceDetail . '<br>';
            // echo 'Discount: ' . $discount . '<br>';
            // echo 'Newest Price: ' . $priceOutlet . '<br>';
            // echo '---------------------------<br>';
            // Insert data into the database
            $status="unpublished";
            $table_name="off_market";
            $stmt = $pdo->prepare('INSERT INTO off_market (unique_id,image,site_name, product_url, brand,type,gender,size, old_price, discount, new_price,image_path,status,table_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?)');
            $stmt->execute([$skuContent,$image_path,$website, $productUrl, $brand, $productTitle,$gender,$sizesString, $priceOutlet, $discount,$priceDetail,$image_path,$status,$table_name]);
      exit;
        }
    }
    updateStatus($pdo, $table_name);
} catch (Exception $e) {
    // Output any errors that occurred during the request
    echo 'Error: ' . $e->getMessage();
}
?>