//<?php require_once "force_login.php"?>
<?php require_once "db.php" ?>
<html>
<body>

<h1>Products</h1>

<?php

$sql = "SELECT * FROM products";

// This is the procedural style to query the database
$result = mysqli_query($mysqli, $sql);

while($row = mysqli_fetch_array($result)) {
	echo "{$row['name']} {$row['price']} {$row['ingredient']} {$row['vendorid']}
		<a href='update.php?id={$row['id']}'>update</a> 
		<a href='delete.php?id={$row['id']}'>delete</a>
		<br />";
}

?>

</body>
</html>
