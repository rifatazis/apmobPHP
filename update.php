<?php
include 'connection.php';

header('Content-Type: application/json');

$response = array();

// Logging POST dan FILES data
file_put_contents('log.txt', print_r($_POST, true));
file_put_contents('log.txt', print_r($_FILES, true), FILE_APPEND);

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Memproses data POST
        $productId = $_POST['id_product']; 
        $productName = $_POST['product_name'];
        $productDetails = $_POST['product_details'];
        $productPrice = $_POST['product_price'];

        // Inisialisasi variabel gambar
        $productImage = null;

        // Periksa apakah ada gambar yang diunggah
        if (isset($_FILES['product_image']['tmp_name']) && is_uploaded_file($_FILES['product_image']['tmp_name'])) {
            // Lakukan konversi ke data binary langsung
            $productImage = file_get_contents($_FILES['product_image']['tmp_name']);
            // Logging data gambar binary
            file_put_contents('log.txt', "Binary Image Length: " . strlen($productImage) . "\n", FILE_APPEND);
        }

        $sql = "UPDATE products SET product_name = :product_name, product_details = :product_details, product_price = :product_price";

        // Jika ada gambar yang diunggah, tambahkan ke query
        if ($productImage !== null) {
            $sql .= ", product_image = :product_image";
        }

        $sql .= " WHERE id_product = :id_product";

        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':id_product', $productId);
        $stmt->bindParam(':product_name', $productName);
        $stmt->bindParam(':product_details', $productDetails);
        $stmt->bindParam(':product_price', $productPrice);

        if ($productImage !== null) {
            $stmt->bindParam(':product_image', $productImage, PDO::PARAM_LOB);
            // Logging bind param untuk gambar
            file_put_contents('log.txt', "Bind Image Param: " . strlen($productImage) . " bytes\n", FILE_APPEND);
        }

        if ($stmt->execute()) {
            $response['error'] = false;
            $response['message'] = 'Produk berhasil diperbarui';
        } else {
            $response['error'] = true;
            $response['message'] = 'Gagal memperbarui produk';
            file_put_contents('log.txt', "SQL Error Info: " . print_r($stmt->errorInfo(), true), FILE_APPEND);
        }
    } else {
        $response['error'] = true;
        $response['message'] = 'Request tidak valid';
    }
} catch (PDOException $e) {
    $response['error'] = true;
    $response['message'] = $e->getMessage();
    file_put_contents('log.txt', "PDOException: " . $e->getMessage(), FILE_APPEND);
}

echo json_encode($response);
?>
