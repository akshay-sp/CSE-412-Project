<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $sql = "INSERT INTO mobilePhone (phoneId, brand, model) VALUES (:phoneId, :brand, :model)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);

    $sql = "INSERT INTO specifications (phoneId, ram, storage, battery, display) 
            VALUES (:phoneId, :ram, :storage, :battery, :display)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);
    echo json_encode(['status' => 'success']);
}
?>