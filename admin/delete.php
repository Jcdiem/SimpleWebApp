<?php require_once "db.php" ?>
<?php require_once "force_login.php"?>
<?php require_once "validation.php" ?>
<html>
<body>

<?php

$myid = $_REQUEST['id'];
$maxInt = ($mysqli->query("SELECT COUNT(*) FROM products"));

//Perform validations
if(!checkIntegerRange($myid,0,$maxInt)) failValidation("Improper ID");
else {

    $sql = "DELETE FROM products WHERE id=$myid";

    // This is the object-oriented style to query the database
    if($mysqli->query($sql) === TRUE)  {
        echo "Successfully deleted.";
    } else {
        echo "Error: $sql <br />" . $mysqli->error;
    }
}

?>

</body>
</html>
