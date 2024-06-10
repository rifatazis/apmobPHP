<?php
require 'connection.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $name = $_POST['name'];
    $bio = $_POST['bio'];

    $query = "UPDATE user SET username = ?, name = ?, bio = ? WHERE id = ?";
    $stmt = $connection->prepare($query);

    if ($stmt->execute([$username, $name, $bio, $id])) {
        $response['error'] = false;
        $response['message'] = 'Profile updated successfully';
        $response['data'] = array(
            'id' => $id,
            'username' => $username,
            'name' => $name,
            'bio' => $bio
        );
    } else {
        $response['error'] = true;
        $response['message'] = 'Failed to update profile';
    }
} else {
    $response['error'] = true;
    $response['message'] = 'Invalid request';
}

echo json_encode($response);
?>
