<?php
session_start();

require_once "db.php";

$myusername = $_REQUEST['username'];
$mypassword = $_REQUEST['password'];

$sql = "SELECT * FROM users WHERE username='$myusername' AND password=SHA2('$mypassword',256)";

$result = mysqli_query($mysqli, $sql);

$row = mysqli_fetch_array($result);

// This is what happens when a user successfully authenticates
if(!empty($row)) {
	session_destroy();
	session_start();

	$_SESSION['username'] = $row['username'];

	
// This is what happens when the username and/or password doesn't match
} else {
	echo "<p>Incorrect username OR password</p>";
}

if($_SESSION['username']) {
	echo "<p>Welcome {$_SESSION[username]}</p>";

	header("Location: {$_REQUEST['redirect']}");
	exit();

} else {
?>
<html>
<body>

<form>
	<input type="hidden" name="redirect" value="<?= $_REQUEST['redirect'] ?>" />

	<label>Username:</label>
	<input type="text" name="username" />

	<label>Password:</label>
	<input type="password" name="password" />

	<input type="submit" value="Log In" />
</form>

<?php
}
?>

</body>
</html>
