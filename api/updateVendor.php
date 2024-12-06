<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $v_vendorId = $_POST['v_vendorId'];
    $v_name = $_POST['v_name'];
    $v_website = $_POST['v_website'];
    $v_avgRating = $_POST['v_avgRating'];

    $sql = "UPDATE vendor SET v_name = :v_name, v_website = :v_website, v_avgRating = :v_avgRating
            WHERE v_vendorId = :v_vendorId";
    
    $stmt = $pdo->prepare($sql);
    
    $stmt->bindParam(':v_vendorId', $v_vendorId);
    $stmt->bindParam(':v_name', $v_name);
    $stmt->bindParam(':v_website', $v_website);
    $stmt->bindParam(':v_avgRating', $v_avgRating);

    if ($stmt->execute()) {
        echo "Vendor details updated successfully!";
    } else {
        echo "Error updating vendor details.";
    }
}
?>