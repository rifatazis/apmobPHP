<?php
include 'connection.php';
header('Content-Type: application/json');

try {
    if (isset($_GET['brand_id'])) {
        $brandId = $_GET['brand_id'];
        error_log("Brand ID: " . $brandId); 

        $query = "SELECT * FROM sports WHERE brand_id = :brand_id";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':brand_id', $brandId, PDO::PARAM_INT);
        $stmt->execute();

        $sports = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['data' => $sports]);
    } else {
        echo json_encode(['error' => 'Parameter brand_id tidak ditemukan']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
