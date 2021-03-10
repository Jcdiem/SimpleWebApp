<?php require_once $_SERVER['DOCUMENT_ROOT']."/admin/db.php" ?>
<?php require_once $_SERVER['DOCUMENT_ROOT']."/admin/force_login.php"?>
<html>
<body>

<h1>Products</h1>

<?php

$sql = "SELECT * FROM products";

// This is the procedural style to query the database
$result = mysqli_query($mysqli, $sql);

while($row = mysqli_fetch_array($result)) {
	echo htmlspecialchars("{$row['name']} {$row['price']} {$row['ingredient']} {$row['vendorid']}");
		echo "<a href='update.php?id=";
		echo htmlspecialchars("{$row['id']}");
		echo "'>update</a>"; 
		echo "<a href='delete.php?id=";
		echo htmlspecialchars("{$row['id']}");
		echo "'>delete</a>
		<br />";
}

?>

</body>
</html>
