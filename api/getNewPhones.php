<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include 'db.php';

$limit = 15;

$sql = "SELECT mp.mo_phoneid AS phoneId, mp.mo_brand AS brand, mp.mo_model AS model, s.s_ram AS ram, s.s_storage AS storage, s.s_battery AS battery, s.s_display AS display, p.p_price AS price, p.p_currency AS currency, p.p_lastupdateddate AS lastUpdatedDate
        FROM mobilePhone mp
        INNER JOIN specifications s ON mp.mo_phoneid = s.s_phoneid
        LEFT JOIN price p ON mp.mo_phoneid = p.p_phoneid
        ORDER BY p.p_lastupdateddate DESC
        LIMIT :limit;";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    $newestPhones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($newestPhones);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>