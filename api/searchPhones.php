<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Include database connection
include 'db.php';

if (isset($_GET['query'])) {
    $query = $_GET['query'];

    // Proper SQL JOIN to combine mobilePhone and price tables
    $sql = "SELECT mp.mo_phoneid, mp.mo_brand, mp.mo_model, p.p_price, p.p_currency
            FROM mobilePhone mp
            LEFT JOIN price p ON mp.mo_phoneid = p.p_phoneid
            WHERE mp.mo_brand ILIKE :query 
               OR mp.mo_model ILIKE :query";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['query' => "%$query%"]);
    $phones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return results as JSON
    echo json_encode($phones);
} else {
    echo json_encode(['error' => 'No query provided']);
}
?>