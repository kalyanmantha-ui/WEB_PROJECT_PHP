<?php



require_once('db.php'); // Include DB connection file

$id = $_GET['id'];
$stmt = $mysqli->prepare("DELETE FROM events WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
header('Location: list.php');
exit;
?>
