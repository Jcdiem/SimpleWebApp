<?php require_once $_SERVER['DOCUMENT_ROOT']."/admin/db.php" ?>
<?php require_once $_SERVER['DOCUMENT_ROOT']."/admin/force_login.php"?>
<?php require_once $_SERVER['DOCUMENT_ROOT']."/validation.php" ?>
<html>
<body>

<?php

$prodID = $_REQUEST['id'];
$maxInt = ($mysqli->query("SELECT COUNT(*) FROM products"));

//Perform validations
if(!checkIntegerRange($prodID,0,$maxInt)) failValidation("Improper ID");
else {

    //Prepare the statement
    if (!($stmnt = $mysqli->prepare("DELETE FROM products WHERE id=(?)"))) echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    //Bind the item id
    if (!$stmnt->bind_param("i",$prodID)) echo "Binding parameters failed: (" . $stmnt->errno . ") " . $stmnt->error;
    //Execute the statement
    if (!$stmnt->execute()) echo "Execute failed: (" . $stmnt->errno . ") " . $stmnt->error;
    else echo "Item deleted without error.";
}

?>

</body>
</html>
