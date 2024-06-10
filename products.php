<?php

include 'connection.php';

header('Content-Type: application/json');

try {
    if (isset($_GET['sport_id'])) {
        $sportId = $_GET['sport_id'];
        $query = "SELECT * FROM products WHERE sport_id = :sport_id";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':sport_id', $sportId, PDO::PARAM_INT);
        $stmt->execute();

        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($products as &$product) {
            $product['product_image'] = base64_encode($product['product_image']);
        }

        $response = array(
            'message' => 'Produk berhasil diambil',
            'data' => $products
        );

        echo json_encode($response);
    } else {
        echo json_encode(['error' => 'ID Olahraga tidak ditemukan']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

?>
