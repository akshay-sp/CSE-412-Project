<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['phoneId'])) {
        echo json_encode(['error' => 'No phoneId provided']);
        exit;
    }

    $phoneId = $_GET['phoneId'];

    if (!$phoneId) {
        echo json_encode(['error' => 'No phoneId provided']);
        exit;
    }

    try {
        $sql = "SELECT mp.mo_brand, mp.mo_model, sp.s_ram, sp.s_storage, sp.s_battery, sp.s_display, v.v_name, p.p_price, p.p_currency, v.v_avgrating, v.v_website 
            FROM mobilePhone mp
            LEFT JOIN specifications sp ON sp.s_phoneId = mp.mo_phoneId
            LEFT JOIN price p ON p.p_phoneId = mp.mo_phoneId
            LEFT JOIN vendor v ON p.p_priceId = v.v_priceId
            WHERE mp.mo_phoneId = :phoneId
            ORDER BY p.p_price ASC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['phoneId' => $phoneId]);
        $phoneDetails = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$phoneDetails) {
            echo json_encode(['error' => 'Phone not found']);
            exit;
        }

        echo json_encode($phoneDetails);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}
?>