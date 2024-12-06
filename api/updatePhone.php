<?php

include('db.php');

function getNextId($table) {
    global $pdo;
    
    $column = '';
    if ($table == 'mobilePhone') {
        $column = 'mo_phoneid';
    } elseif ($table == 'price') {
        $column = 'p_priceid';
    } elseif ($table == 'specifications') {
        $column = 's_specid';
    } elseif ($table == 'vendor') {
        $column = 'v_vendorid';
    }
    
    $sql = "SELECT MAX($column) AS max_id FROM $table";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['max_id'] + 1;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $price = $_POST['price'];
    $currency = $_POST['currency'];
    $ram = $_POST['ram'];
    $storage = $_POST['storage'];
    $battery = $_POST['battery'];
    $display = $_POST['display'];
    $vendor_name = $_POST['vendor_name'];
    $vendor_website = $_POST['vendor_website'];
    $vendor_rating = $_POST['vendor_rating'];

    $mo_phoneid = getNextId('mobilePhone');
    $p_priceid = getNextId('price');
    $s_specid = getNextId('specifications');
    $v_vendorid = getNextId('vendor');

    try {
        $pdo->beginTransaction();

        $sql = "INSERT INTO mobilePhone (mo_phoneid, mo_brand, mo_model) VALUES (:mo_phoneid, :brand, :model)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['mo_phoneid' => $mo_phoneid, 'brand' => $brand, 'model' => $model]);

        $sql = "INSERT INTO price (p_priceid, p_phoneid, p_price, p_currency, p_lastupdateddate) 
                VALUES (:p_priceid, :p_phoneid, :price, :currency, CURRENT_DATE)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['p_priceid' => $p_priceid, 'p_phoneid' => $mo_phoneid, 'price' => $price, 'currency' => $currency]);

        $sql = "INSERT INTO specifications (s_specid, s_phoneid, s_ram, s_storage, s_battery, s_display) 
                VALUES (:s_specid, :s_phoneid, :ram, :storage, :battery, :display)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['s_specid' => $s_specid, 's_phoneid' => $mo_phoneid, 'ram' => $ram, 'storage' => $storage, 'battery' => $battery, 'display' => $display]);

        $sql = "INSERT INTO vendor (v_vendorid, v_priceid, v_name, v_website, v_avgrating) 
                VALUES (:v_vendorid, :v_priceid, :v_name, :v_website, :v_avgrating)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['v_vendorid' => $v_vendorid, 'v_priceid' => $p_priceid, 'v_name' => $vendor_name, 'v_website' => $vendor_website, 'v_avgrating' => $vendor_rating]);

        $pdo->commit();

        echo "Phone information added successfully!";
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error adding phone information: " . $e->getMessage();
    }
}
?>