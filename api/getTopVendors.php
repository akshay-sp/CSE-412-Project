<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include 'db.php';

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$limit = 15;

$sql = "SELECT v.v_vendorId AS vendorId, v.v_name AS vendorName, COALESCE(v.v_avgRating, 0) AS avgRating, mp.mo_brand AS phoneBrand, mp.mo_model AS phoneModel, v.v_website AS vendorwebsite
        FROM vendor v
        LEFT JOIN mobilePhone mp ON v.v_vendorId = mp.mo_phoneid
        ORDER BY v.v_avgRating DESC
        LIMIT :limit;";

error_log("Executing SQL query: " . $sql);

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->execute();

$stmt->setFetchMode(PDO::FETCH_ASSOC);

$topVendors = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($topVendors)) {
    echo json_encode(['message' => 'No vendor data found']);
    exit;
}

echo json_encode($topVendors);
?>