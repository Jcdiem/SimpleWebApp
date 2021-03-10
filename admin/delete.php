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

$prodID = $_REQUEST['id'];
$maxInt = ($mysqli->query("SELECT COUNT(*) FROM products"));

//Perform validations
if(!checkIntegerRange($prodID,0,$maxInt)) failValidation("Improper ID");
else if ($_REQUEST['csrf_token'] == $_SESSION['csrf_token']){
    unset($_SESSION['csrf_token']);

    //Prepare the statement
    if (!($stmnt = $mysqli->prepare("DELETE FROM products WHERE id=(?)"))) echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    //Bind the item id
    if (!$stmnt->bind_param("i",$prodID)) echo "Binding parameters failed: (" . $stmnt->errno . ") " . $stmnt->error;
    //Execute the statement
    if (!$stmnt->execute()) echo "Execute failed: (" . $stmnt->errno . ") " . $stmnt->error;
    else echo "Item deleted without error.";
}
else echo "ERROR - Probably bad CSRF token";

?>
<form>
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <label for="removeID">ID to remove: </label>
    <input id="removeID" name="id" type="number">
</form>

</body>
</html>
