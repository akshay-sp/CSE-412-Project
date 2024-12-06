<?php
include 'db.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mo_phoneid'])) {
        $phoneId = (int)$_POST['mo_phoneid'];

        $pdo->beginTransaction();

        $stmt = $pdo->prepare(
            "DELETE FROM vendor 
             WHERE v_priceId IN (
                 SELECT p_priceId FROM price WHERE p_phoneId = :phoneId
             )"
        );
        $stmt->execute(['phoneId' => $phoneId]);

        $stmt = $pdo->prepare("DELETE FROM price WHERE p_phoneId = :phoneId");
        $stmt->execute(['phoneId' => $phoneId]);

        $stmt = $pdo->prepare("DELETE FROM specifications WHERE s_phoneId = :phoneId");
        $stmt->execute(['phoneId' => $phoneId]);

        $stmt = $pdo->prepare("DELETE FROM mobilePhone WHERE mo_phoneId = :phoneId");
        $stmt->execute(['phoneId' => $phoneId]);

        $stmt = $pdo->prepare("DELETE FROM vendor WHERE v_vendorid = :phoneId");
        $stmt->execute(['phoneId' => $phoneId]);

        $pdo->commit();

        echo "<p>Phone with ID $phoneId and all related data have been successfully deleted.</p>";
    } else {
        echo "<p>Invalid request. Please provide a valid Phone ID.</p>";
    }
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?>