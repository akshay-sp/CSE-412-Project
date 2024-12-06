<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mo_phoneid = $_POST['mo_phoneid'];
    $p_price = $_POST['p_price'];
    $p_currency = $_POST['p_currency'];
    $p_lastUpdatedDate = date('Y-m-d');

    $sql = "UPDATE price SET p_price = :p_price, p_currency = :p_currency, p_lastUpdatedDate = :p_lastUpdatedDate
            WHERE p_phoneId = :mo_phoneid";
    
    $stmt = $pdo->prepare($sql);
    
    $stmt->bindParam(':mo_phoneid', $mo_phoneid);
    $stmt->bindParam(':p_price', $p_price);
    $stmt->bindParam(':p_currency', $p_currency);
    $stmt->bindParam(':p_lastUpdatedDate', $p_lastUpdatedDate);

    if ($stmt->execute()) {
        echo "Price updated successfully!";
    } else {
        echo "Error updating price.";
    }
}
?>