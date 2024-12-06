<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include 'db.php';

if (isset($_GET['query'])) {
    $query = $_GET['query'];
    $currency = isset($_GET['currency']) && !empty($_GET['currency']) ? $_GET['currency'] : null;
    $ram = isset($_GET['ram']) ? $_GET['ram'] : null;
    $storage = isset($_GET['storage']) ? $_GET['storage'] : null;
    $battery = isset($_GET['battery']) ? $_GET['battery'] : null;
    $display = isset($_GET['display']) ? $_GET['display'] : null;
    $sortBy = isset($_GET['sortBy']) ? $_GET['sortBy'] : 'mp.mo_brand';
    $sortOrder = isset($_GET['sortOrder']) && strtolower($_GET['sortOrder']) === 'desc' ? 'DESC' : 'ASC';

    $sql = "SELECT mp.mo_phoneid, mp.mo_brand, mp.mo_model, s.s_ram, s.s_storage, s.s_battery, s.s_display, p.p_price, p.p_currency
            FROM mobilePhone mp
            INNER JOIN specifications s ON mp.mo_phoneid = s.s_phoneid
            LEFT JOIN price p ON mp.mo_phoneid = p.p_phoneid
            WHERE (mp.mo_brand ILIKE :query OR mp.mo_model ILIKE :query)";
    
    $params = ['query' => "%$query%"];

    if ($currency) {
        $sql .= " AND p.p_currency = :currency";
        $params['currency'] = $currency;
    }
    if ($ram) {
        $sql .= " AND s.s_ram = :ram";
        $params['ram'] = $ram;
    }
    if ($storage) {
        $sql .= " AND s.s_storage = :storage";
        $params['storage'] = $storage;
    }
    if ($battery) {
        $sql .= " AND s.s_battery = :battery";
        $params['battery'] = $battery;
    }
    if ($display) {
        $sql .= " AND s.s_display = :display";
        $params['display'] = $display;
    }

    $sql .= " ORDER BY $sortBy $sortOrder";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $phones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($phones);
} else {
    echo json_encode(['error' => 'No query provided']);
}
?>