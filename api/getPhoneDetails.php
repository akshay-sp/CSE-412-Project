<?php
// Include database connection
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $phoneId = $_GET['phoneId'];

    // Validate phoneId input
    if (!$phoneId) {
        echo json_encode(['error' => 'No phoneId provided']);
        exit;
    }

    try {
        // Fetch phone details
        $sql = "SELECT * FROM mobilePhone WHERE mo_phoneId = :phoneId";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['phoneId' => $phoneId]);
        $phoneDetails = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$phoneDetails) {
            echo json_encode(['error' => 'Phone not found']);
            exit;
        }

        // Fetch specifications
        $sql = "SELECT s_ram, s_storage, s_battery, s_display 
                FROM specifications 
                WHERE s_phoneId = :phoneId";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['phoneId' => $phoneId]);
        $specifications = $stmt->fetch(PDO::FETCH_ASSOC);

        $phoneDetails['specifications'] = $specifications;

        echo json_encode($phoneDetails);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}
?>