<?php
include 'db_connection.php';

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM expenses WHERE id = ?");
$stmt->execute([$id]);

header("Location: report.php");
exit();
