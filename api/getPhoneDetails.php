<?php
// Include database connection
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['phoneId'])) {
        echo json_encode(['error' => 'No phoneId provided']);
        exit;
    }

    $phoneId = $_GET['phoneId'];

    // Validate phoneId input
    if (!$phoneId) {
        echo json_encode(['error' => 'No phoneId provided']);
        exit;
    }

    try {
        // Fetch phone details, specifications, and vendor info in a single query
        $sql = "
            SELECT 
                mp.mo_brand, 
                mp.mo_model, 
                sp.s_ram, 
                sp.s_storage, 
                sp.s_battery, 
                sp.s_display, 
                v.v_name, 
                p.p_price, 
                p.p_currency, 
                v.v_avgrating 
            FROM mobilePhone mp
            LEFT JOIN specifications sp ON sp.s_phoneId = mp.mo_phoneId
            LEFT JOIN price p ON p.p_phoneId = mp.mo_phoneId
            LEFT JOIN vendor v ON p.p_priceId = v.v_priceId
            WHERE mp.mo_phoneId = :phoneId
        ";

        // Prepare and execute the query
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['phoneId' => $phoneId]);
        $phoneDetails = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if phone details were found
        if (!$phoneDetails) {
            echo json_encode(['error' => 'Phone not found']);
            exit;
        }

        // Return the phone details as JSON
        echo json_encode($phoneDetails);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}
?>