<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Include database connection
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['phoneId'])) {
        echo json_encode(['error' => 'No phoneId provided']);
        exit;
    }

    $phoneId = $_GET['phoneId'];

    // Fetch the selected phone's details, including price and vendor information
    $sql = "SELECT mo_phoneId, mo_brand, mo_model, s_ram, s_storage, s_battery, s_display, 
                   p_price, p_currency, v_name, v_avgrating
            FROM mobilePhone
            JOIN specifications ON mobilePhone.mo_phoneId = specifications.s_phoneId
            JOIN price ON mobilePhone.mo_phoneId = price.p_phoneId
            JOIN vendor ON price.p_priceId = vendor.v_priceId
            WHERE mo_phoneId = :phoneId";

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

    // Fetch other phones from the same brand with matching currency
    $sql = "SELECT mo_phoneId, mo_model, p_price, p_currency, v_name, v_avgrating
            FROM mobilePhone
            JOIN price ON mobilePhone.mo_phoneId = price.p_phoneId
            JOIN vendor ON price.p_priceId = vendor.v_priceId
            WHERE mo_brand = :brand
            AND p_currency = :currency
            AND mo_phoneId != :phoneId";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['brand' => $brand, 'currency' => $currency, 'phoneId' => $phoneId]);
    $sameBrandPhones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch phones with similar specifications and matching currency
    $sql = "SELECT mo_phoneId, mo_brand, mo_model, p_price, p_currency, v_name, v_avgrating
            FROM mobilePhone
            JOIN price ON mobilePhone.mo_phoneId = price.p_phoneId
            JOIN vendor ON price.p_priceId = vendor.v_priceId
            JOIN specifications ON mobilePhone.mo_phoneId = specifications.s_phoneId
            WHERE s_ram = :ram AND s_storage = :storage AND s_display = :display
            AND p_currency = :currency
            AND mo_phoneId != :phoneId";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['ram' => $ram, 'storage' => $storage, 'display' => $display, 'currency' => $currency, 'phoneId' => $phoneId]);
    $similarSpecPhones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Combine results and return
    echo json_encode([
        'phone' => $phone,
        'sameBrandPhones' => $sameBrandPhones,
        'similarSpecPhones' => $similarSpecPhones
    ]);
}
?>