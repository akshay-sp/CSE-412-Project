<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['phoneId'])) {
        echo json_encode(['error' => 'No phoneId provided']);
        exit;
    }

    $phoneId = $_GET['phoneId'];

    try {
        $sql = "SELECT mp.mo_phoneId AS phoneId, mp.mo_brand, mp.mo_model, sp.s_ram, sp.s_storage, sp.s_battery, sp.s_display, p.p_price, p.p_currency, v.v_name, v.v_avgrating, v.v_website
                FROM mobilePhone mp
                JOIN specifications sp ON mp.mo_phoneId = sp.s_phoneId
                JOIN price p ON mp.mo_phoneId = p.p_phoneId
                JOIN vendor v ON p.p_priceId = v.v_priceId
                WHERE mp.mo_phoneId = :phoneId";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['phoneId' => $phoneId]);
        $phone = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$phone) {
            echo json_encode(['error' => 'Phone not found']);
            exit;
        }

        $brand = $phone['mo_brand'];
        $ram = $phone['s_ram'];
        $storage = $phone['s_storage'];
        $display = $phone['s_display'];
        $currency = $phone['p_currency'];

        $sql = "
            SELECT mp.mo_phoneId AS phoneId, mp.mo_model, p.p_price, p.p_currency, v.v_name, v.v_avgrating, v.v_website
            FROM mobilePhone mp
            JOIN price p ON mp.mo_phoneId = p.p_phoneId
            JOIN vendor v ON p.p_priceId = v.v_priceId
            WHERE mp.mo_brand = :brand
              AND p.p_currency = :currency
              AND mp.mo_phoneId != :phoneId
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['brand' => $brand, 'currency' => $currency, 'phoneId' => $phoneId]);
        $sameBrandPhones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sql = "
            SELECT mp.mo_phoneId AS phoneId, mp.mo_brand, mp.mo_model, p.p_price, p.p_currency, v.v_name, v.v_avgrating, v.v_website
            FROM mobilePhone mp
            JOIN price p ON mp.mo_phoneId = p.p_phoneId
            JOIN vendor v ON p.p_priceId = v.v_priceId
            JOIN specifications sp ON mp.mo_phoneId = sp.s_phoneId
            WHERE sp.s_ram = :ram 
              AND sp.s_storage = :storage 
              AND sp.s_display = :display
              AND p.p_currency = :currency
              AND mp.mo_phoneId != :phoneId
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'ram' => $ram, 
            'storage' => $storage, 
            'display' => $display, 
            'currency' => $currency, 
            'phoneId' => $phoneId
        ]);
        $similarSpecPhones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'phone' => $phone,
            'sameBrandPhones' => $sameBrandPhones,
            'similarSpecPhones' => $similarSpecPhones
        ]);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}
?>