<?php
include 'connection.php';

// Mengambil ID produk dan ID olahraga dari request
$id_product = isset($_GET['id_product']) ? $_GET['id_product'] : null;
$sport_id = isset($_GET['sport_id']) ? $_GET['sport_id'] : null;

if ($id_product !== null && $sport_id !== null) {
    try {
        $query = "SELECT * FROM products WHERE id_product = :id_product AND sport_id = :sport_id";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':id_product', $id_product);
        $stmt->bindParam(':sport_id', $sport_id);
        $stmt->execute();

        // Jika data ditemukan
        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode(array(
                "error" => false,
                "product" => $result // Mengembalikan data produk
            ));
        } else {
            // Jika data tidak ditemukan
            echo json_encode(array(
                "error" => true,
                "message" => "Data produk tidak ditemukan"
            ));
        }
    } catch (PDOException $e) {
        echo json_encode(array(
            "error" => true,
            "message" => "Gagal mengambil detail produk: " . $e->getMessage()
        ));
    }
} else {
    echo json_encode(array(
        "error" => true,
        "message" => "Parameter ID produk atau ID olahraga tidak valid"
    ));
}
?>
