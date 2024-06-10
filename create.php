<?php
include 'connection.php';

header('Content-Type: application/json');

$response = array();

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $productName = $_POST['product_name'];
        $productDetails = $_POST['product_details'];
        $productPrice = $_POST['product_price'];
        $sportId = $_POST['sport_id']; 
        $productImage = file_get_contents($_FILES['product_image']['tmp_name']); 
        
        
        $sql = "INSERT INTO products (product_name, product_details, product_price, product_image, sport_id) VALUES (:product_name, :product_details, :product_price, :product_image, :sport_id)";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':product_name', $productName);
        $stmt->bindParam(':product_details', $productDetails);
        $stmt->bindParam(':product_price', $productPrice);
        $stmt->bindParam(':product_image', $productImage, PDO::PARAM_LOB); 
        $stmt->bindParam(':sport_id', $sportId); 
        if ($stmt->execute()) {
            $productId = $connection->lastInsertId(); 
           
            $response['error'] = false;
            $response['message'] = 'Produk berhasil dibuat';
            $response['product'] = array(
                "id" => $productId,
                "product_name" => $productName,
                "product_details" => $productDetails,
                "product_price" => $productPrice,
          
            );
        } else {
            $response['error'] = true;
            $response['message'] = 'Gagal membuat produk';
        }
    } else {
        $response['error'] = true;
        $response['message'] = 'Request tidak valid';
    }
} catch (PDOException $e) {
    $response['error'] = true;
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>
