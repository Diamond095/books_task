<?php
require_once 'config.php';


$sql = "SELECT * FROM tasks ORDER BY created_at DESC";
$result = $mysqli->query($sql);
if (!$result) {
    die('Ошибка запроса: ' . $mysqli->error);
}
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <title>Task Manager</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Список задач / книг</h1>
    <a href="add.php" class="btn btn-primary">Добавить задачу / книгу</a>
  </div>

  <table class="table table-striped table-bordered bg-white">
    <thead>
      <tr>
        <th>#</th>
        <th>Название</th>
        <th>Автор</th>
        <th>Год</th>
        <th>Описание</th>
        <th>Статус</th>
        <th>Создано</th>
        <th>Действия</th>
      </tr>
    </thead>
    <tbody>
<?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?php echo (int)$row['id']; ?></td>
        <td><?php echo htmlspecialchars($row['title'], ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8'); ?></td>
        <td><?php echo htmlspecialchars($row['author'] ?? '', ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8'); ?></td>
        <td><?php echo $row['year'] ? (int)$row['year'] : ''; ?></td>
        <td><?php echo nl2br(htmlspecialchars($row['description'] ?? '', ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8')); ?></td>
        <td><?php echo htmlspecialchars($row['status'], ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8'); ?></td>
        <td><?php echo htmlspecialchars($row['created_at'], ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8'); ?></td>
        <td>
          <a href="edit.php?id=<?php echo (int)$row['id']; ?>" class="btn btn-sm btn-secondary">Редактировать</a>
          <a href="delete.php?id=<?php echo (int)$row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Удалить запись?');">Удалить</a>
          <?php if ($row['status'] === 'не выполнена'): ?>
            <a href="update_status.php?id=<?php echo (int)$row['id']; ?>&action=done" class="btn btn-sm btn-success">Отметить выполненной</a>
          <?php else: ?>
            <a href="update_status.php?id=<?php echo (int)$row['id']; ?>&action=undone" class="btn btn-sm btn-warning">Отметить невыполненной</a>
          <?php endif; ?>
        </td>
      </tr>
<?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
