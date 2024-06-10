<?php
include 'connection.php';

header('Content-Type: application/json');

try {
    $sql = "SELECT * FROM brands";
    $stmt = $connection->prepare($sql);
    $stmt->execute();

    $brands = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($brands as &$brand) {
        $brand['logo'] = 'data:image/jpeg;base64,' . base64_encode($brand['logo']);
    }

    $response = array(
        'message' => 'Brands retrieved successfully',
        'data' => $brands
    );

    echo json_encode($response);
} catch (PDOException $e) {
    $response = array(
        'message' => 'Error: ' . $e->getMessage(),
        'data' => []
    );

    echo json_encode($response);
}
?>
