<?php
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $sql = "UPDATE mobilePhone SET brand = :brand, model = :model WHERE phoneId = :phoneId";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);
    echo json_encode(['status' => 'success']);
}
?>