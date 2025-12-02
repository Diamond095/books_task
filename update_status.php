<?php
require_once 'config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$action = $_GET['action'] ?? '';

if ($id <= 0) {
    header('Location: index.php');
    exit;
}

if ($action === 'done') {
    $status = 'выполнена';
} else if ($action === 'undone') {
    $status = 'не выполнена';
} else {
    header('Location: index.php');
    exit;
}

$stmt = $mysqli->prepare('UPDATE tasks SET status = ? WHERE id = ?');
$stmt->bind_param('si', $status, $id);
$stmt->execute();
$stmt->close();

header('Location: index.php');
exit;
?>