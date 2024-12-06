<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include 'db.php';

$phoneId = isset($_GET['phoneId']) ? intval($_GET['phoneId']) : null;
$vendorName = isset($_GET['vendorName']) ? $_GET['vendorName'] : null;

if (!$phoneId || !$vendorName) {
    echo json_encode(['error' => 'Missing phoneId or vendorName parameter.']);
    exit;
}

$sql = "SELECT mp.mo_brand AS brand, mp.mo_phoneId AS phoneId, mp.mo_model AS model, s.s_ram AS ram, s.s_storage AS storage, s.s_battery AS battery, s.s_display AS display, p.p_price AS price, p.p_currency AS currency
        FROM mobilePhone mp
        INNER JOIN price p ON mp.mo_phoneId = p.p_phoneId
        INNER JOIN vendor v ON v.v_priceId = p.p_priceId
        INNER JOIN specifications s ON mp.mo_phoneId = s.s_phoneId
        WHERE v.v_name = :vendorName
        AND mp.mo_phoneId != :phoneId;";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':vendorName', $vendorName, PDO::PARAM_STR);
    $stmt->bindValue(':phoneId', $phoneId, PDO::PARAM_INT);
    $stmt->execute();
    $otherPhones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['phones' => $otherPhones]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
