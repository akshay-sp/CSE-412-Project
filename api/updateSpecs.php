<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mo_phoneid = $_POST['mo_phoneid'];
    $s_ram = $_POST['s_ram'];
    $s_storage = $_POST['s_storage'];
    $s_battery = $_POST['s_battery'];
    $s_display = $_POST['s_display'];

    $sql = "UPDATE specifications SET s_ram = :s_ram, s_storage = :s_storage, s_battery = :s_battery, s_display = :s_display
            WHERE s_phoneId = :mo_phoneid";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':mo_phoneid', $mo_phoneid);
    $stmt->bindParam(':s_ram', $s_ram);
    $stmt->bindParam(':s_storage', $s_storage);
    $stmt->bindParam(':s_battery', $s_battery);
    $stmt->bindParam(':s_display', $s_display);

    if ($stmt->execute()) {
        echo "Specifications updated successfully!";
    } else {
        echo "Error updating specifications.";
    }
}
?>