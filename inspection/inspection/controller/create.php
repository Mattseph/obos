<?php

include './../../../config/constants.php';

// Fetch $_POST
// var_dump($_POST);
// exit;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clean_owner_id = filter_var($_POST['owner_id'], FILTER_SANITIZE_NUMBER_INT);
    $owner_id = filter_var($clean_owner_id, FILTER_VALIDATE_INT);

    $clean_business_id = filter_var($_POST['business_id'], FILTER_SANITIZE_NUMBER_INT);
    $business_id = filter_var($clean_business_id, FILTER_VALIDATE_INT);

    $clean_bldg_billing_id = filter_var($_POST['bldg_billing_id'], FILTER_SANITIZE_NUMBER_INT);
    $bldg_billing_id = filter_var($clean_bldg_billing_id, FILTER_VALIDATE_INT);

    $clean_signage_id = filter_var($_POST['signage_id'], FILTER_SANITIZE_NUMBER_INT);
    $signage_id = filter_var($clean_signage_id, FILTER_VALIDATE_INT);

    $clean_sanitary_id = filter_var($_POST['sanitary_id'], FILTER_SANITIZE_NUMBER_INT);
    $sanitary_id = filter_var($clean_sanitary_id, FILTER_VALIDATE_INT);

    $billings_id = $_POST['billings_id'];
    $items_id = $_POST['items_id'];
    $inspectors_id = $_POST['inspectors_id'];

    $application_type = htmlspecialchars($_POST['application_type']);
    $remarks = htmlspecialchars($_POST['remarks']);

    $power_ratings = $_POST['power_ratings'];
    $quantities = $_POST['quantities'];
    $fees = $_POST['fees'];

    $building_fee = $_POST['building_fee'];
    $sanitary_fee = $_POST['sanitary_fee'];
    $sanitary_quantity = $_POST['sanitary_quantity'];
    $signage_fee = $_POST['signage_fee'];

    $date_inspected = date('Y-m-d H:i:s');
}

// Insert Data to inspection
$inspectionInsert = "INSERT INTO inspection(
    owner_id,
    bus_id,
    signage_id,
    bldg_billing_id,
    application_type,
    remarks,
    date_inspected
    
) VALUES(
    :owner_id,
    :bus_id,
    :signage_id,
    :bldg_billing_id,
    :application_type,
    :remarks,
    :date_inspected
)";

$inspectionStatement = $pdo->prepare($inspectionInsert);
$inspectionStatement->bindParam(':owner_id', $owner_id);
$inspectionStatement->bindParam(':bus_id', $business_id);
$inspectionStatement->bindParam(':signage_id', $signage_id);
$inspectionStatement->bindParam(':bldg_billing_id', $bldg_billing_id);
$inspectionStatement->bindParam(':application_type', $application_type);
$inspectionStatement->bindParam(':remarks', $remarks);
$inspectionStatement->bindParam(':date_inspected', $date_inspected);
$inspectionStatement->execute();

$inspection_id = $pdo->lastInsertId();

// Insert Data to inspection item

$inspectionItemInsert = "INSERT INTO inspection_item(
    inspection_id,
    item_id,
    billing_id,
    power_rating,
    quantity,
    fee
) VALUES(
    :inspection_id,
    :item_id,
    :billing_id,
    :power_rating,
    :quantity,
    :fee
)";


$inspectionItemStatement = $pdo->prepare($inspectionItemInsert);

for ($i = 0; $i < count($items_id); $i++) {
    $inspectionItemStatement->bindParam(':inspection_id', $inspection_id);
    $inspectionItemStatement->bindParam(':item_id', $items_id[$i]);
    $inspectionItemStatement->bindValue(':billing_id', $billings_id[$i]);
    $inspectionItemStatement->bindParam(':power_rating', $power_ratings[$i]);
    $inspectionItemStatement->bindParam(':quantity', $quantities[$i]);
    $inspectionItemStatement->bindParam(':fee', $fees[$i]);
    $inspectionItemStatement->execute();
}

if (filter_has_var(INPUT_POST, 'violations_id')) {
    $violations_id = $_POST['violations_id'];

    // Insert Data to inspection violation
    $violationInsert = "INSERT INTO inspection_violation(
        inspection_id,
        violation_id
    ) VALUES(
        :inspection_id,
        :violation_id
    )";

    $violationStatement = $pdo->prepare($violationInsert);

    for ($i = 0; $i < count($violations_id); $i++) {
        $violationStatement->bindParam(':inspection_id', $inspection_id);
        $violationStatement->bindParam(':violation_id', $violations_id[$i]);
        $violationStatement->execute();
    }
}

$inspectionSanitary = "INSERT INTO inspection_sanitary_billing (
    inspection_id,
    sanitary_id,
    sanitary_quantity
) 
VALUES (
    :inspection_id,
    :sanitary_id,
    :sanitary_quantity
)";

$inspectionSanitaryStatement = $pdo->prepare($inspectionSanitary);
$inspectionSanitaryStatement->bindParam(':inspection_id', $inspection_id);
$inspectionSanitaryStatement->bindParam(':sanitary_id', $sanitary_id);
$inspectionSanitaryStatement->bindParam(':sanitary_quantity', $sanitary_quantity);
$inspectionSanitaryStatement->execute();

// Insert Data to inspection inspector
$inspectorInsert = "INSERT INTO inspection_inspector(
    inspection_id,
    inspector_id
) VALUES(
    :inspection_id,
    :inspector_id
)";

$inspectorStatement = $pdo->prepare($inspectorInsert);

for ($i = 0; $i < count($inspectors_id); $i++) {
    $inspectorStatement->bindParam(':inspection_id', $inspection_id);
    $inspectorStatement->bindParam(':inspector_id', $inspectors_id[$i]);
    $inspectorStatement->execute();
}

// Redirect with $_SESSION Message

$_SESSION['add'] = "
    <div class='msgalert alert--success' id='alert'>
        <div class='alert__message'>
            Annual Equipment List Created Successfully
    </div>
";

header('location:' . SITEURL . 'inspection/inspection/');
