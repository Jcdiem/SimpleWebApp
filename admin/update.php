<?php require_once $_SERVER['DOCUMENT_ROOT']."/admin/db.php" ?>
<?php require_once $_SERVER['DOCUMENT_ROOT']."/admin/force_login.php"?>
<?php require_once $_SERVER['DOCUMENT_ROOT']."/validation.php" ?>
<html lang="en">
<body>

<?php
session_start();
//Create CSRF
try {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(128));
} catch (Exception $e) {
    echo "Huh, that's probably really bad...";
}


$myid = $_REQUEST['id'];
$myname = $_REQUEST['name'];
$myprice = $_REQUEST['price'];
$mainIngred = $_REQUEST['ingredient'];
$vendorID = $_REQUEST['vendor'];
$textName = base64_decode($myname);
$maxInt = ($mysqli->query("SELECT COUNT(*) FROM products")) - 1;

//Perform validations
if (checkIntegerRange($myid, 0, $maxInt)) failValidation("Improper ID");
else if (validateString($myname)) failValidation("Error in String encoding");
else if (checkDecimalRange($myprice, 0, 999.99)) failValidation("Error in price of item");
else if(!checkIntegerRange($vendorID,1,2)) failValidation("Vendor outside of range");
else {
    if ($_REQUEST['name'] && $_REQUEST['csrf_token'] == $_SESSION['csrf_token']) {
        unset($_SESSION['csrf_token']);

        if (!($stmnt = $mysqli->prepare("UPDATE products SET name=?, price=? WHERE id=?"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        if (!$stmnt->bind_param("sdi", $myname, $myprice, $myid)) {
            echo "Binding parameters failed: (" . $stmnt->errno . ") " . $stmnt->error;
        }
        if (!$stmnt->execute()) {
            echo "Execute failed: (" . $stmnt->errno . ") " . $stmnt->error;
        }
    }

    if (!($stmnt = $mysqli->prepare("SELECT * FROM products WHERE id=?"))) echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    if (!$stmnt->bind_param("i", $myid)) echo "Binding parameters failed: (" . $stmnt->errno . ") " . $stmnt->error;
    if (!$stmnt->execute()) echo "Execute failed: (" . $stmnt->errno . ") " . $stmnt->error;
    if (!$result = $stmnt->get_result()) echo "Gathering result failed: (" . $stmnt->errno . ") " . $stmnt->error;
    $row = mysqli_fetch_assoc($result);
}
?>
<form action="update.php" method="post">
    <input type="hidden" name="csrf_token" value="<?php echo $_REQUEST['csrf_token']; ?>">

    <label for="id">ID: <label>
    <input type="number" id="id" name="id" value=""/>

    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value=""/>

    <label for="price">Price:</label>
    <input type="text" id="price" name="price" value=""/>

    <label for="ingredient">Main Ingredient:</label>
    <input type="text" id="ingredient" name="ingredient" value=""/>

    <label for="vendor">Vendor ID:</label>
    <input type="text" id="vendor" name="vendor" value=""/>

    <input type="submit" value="update"/>
</form>
</body>
</html>
