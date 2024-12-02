<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $phoneId = $_GET['phoneId'];
    $sql = "SELECT p.price, p.currency, v.name as vendorName, v.website, p.lastUpdatedDate 
            FROM price p 
            JOIN vendor v ON p.vendorId = v.vendorId 
            WHERE p.phoneId = :phoneId";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['phoneId' => $phoneId]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}
?>