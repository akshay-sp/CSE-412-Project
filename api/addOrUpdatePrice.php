<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $sql = "INSERT INTO price (priceId, phoneId, vendorId, price, currency, lastUpdatedDate) 
            VALUES (:priceId, :phoneId, :vendorId, :price, :currency, :lastUpdatedDate)
            ON CONFLICT (priceId) DO UPDATE SET 
            price = EXCLUDED.price, 
            currency = EXCLUDED.currency, 
            lastUpdatedDate = EXCLUDED.lastUpdatedDate";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);
    echo json_encode(['status' => 'success']);
}
?>