<?php require_once $_SERVER['DOCUMENT_ROOT']."/admin/db.php" ?>
<?php require_once $_SERVER['DOCUMENT_ROOT']."/admin/force_login.php"?>
<?php require_once $_SERVER['DOCUMENT_ROOT']."/validation.php" ?>
<?php
session_start();
//Create CSRF
try {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(128));
} catch (Exception $e) {
    echo "Huh, that's probably really bad...";
}
?>
<html lang="en">
<body>

<?php


$failure = false;

if ($_REQUEST['name'] && $_REQUEST['csrf_token'] == $_SESSION['csrf_token']) {
    $myname = $mysqli->real_escape_string($_REQUEST['name']);
    $myprice = (double)$_REQUEST['price'];
    $mainIngred = $mysqli->real_escape_string($_REQUEST['ingredient']);
    $vendorID = $_REQUEST['vendor'];

    if (validateString($myname) == false) {
        failValidation("Error in string {$myname} Encoding");
    }
    else if (checkDecimalRange($myprice, 0, 999.99) == false){
        failValidation("Error in price of item");
    }
    else if (checkIntegerRange($vendorID, 1, 999) == false) {
        failValidation("Improper vendor");
    }
    else if (validateString($mainIngred) == false) {
        failValidation("Error in the ingredient");
    }
    else{
        if (!($stmnt = $mysqli->prepare("INSERT INTO products (name, price, ingredient, vendorid) VALUES (?, ?, ?, ?)"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        if (!$stmnt->bind_param("sdsi", $myname, $myprice, $mainIngred, $vendorID)) {
            echo "Binding parameters failed: (" . $stmnt->errno . ") " . $stmnt->error;
        }
        if (!$stmnt->execute()) {
            echo "Execute failed: (" . $stmnt->errno . ") " . $stmnt->error;
        }
    }


}

?>

<form>
    <input type="hidden" value="<?php echo $_REQUEST['csrf_token']; ?>">

    <label>Name:</label>
    <input type="text" name="name"/>

    <label>Price:</label>
    <input type="text" name="price"/>

    <label>Main Ingredient:</label>
    <input type="text" name="ingredient"/>

    <label>Vendor ID:</label>
    <input type="number" name="vendor"/>

    <input type="submit" value="Create Product"/>
</form>

</body>
</html>
