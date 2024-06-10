<?php
include 'connection.php';

header('Content-Type: application/json');

$response = array();

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['id_product'])) {
            $productId = $_POST['id_product'];

            $sql = "DELETE FROM products WHERE id_product = :id_product";
            $stmt = $connection->prepare($sql);
            $stmt->bindParam(':id_product', $productId);

            if ($stmt->execute()) {
                $response['error'] = false;
                $response['message'] = 'Produk berhasil dihapus';
            } else {
                $response['error'] = true;
                $response['message'] = 'Gagal menghapus produk';
            }
        } else {
            $response['error'] = true;
            $response['message'] = 'ID produk tidak ditemukan';
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
