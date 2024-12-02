<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $sql = "INSERT INTO vendor (vendorId, name, website, avgRating) 
            VALUES (:vendorId, :name, :website, :avgRating)
            ON CONFLICT (vendorId) DO UPDATE SET 
            name = EXCLUDED.name, 
            website = EXCLUDED.website, 
            avgRating = EXCLUDED.avgRating";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);
    echo json_encode(['status' => 'success']);
}
?>