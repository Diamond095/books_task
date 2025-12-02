<?php
require_once 'config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die('Неверный id.');
}

$stmt = $mysqli->prepare('SELECT * FROM tasks WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$task = $res->fetch_assoc();
$stmt->close();

if (!$task) {
    die('Запись не найдена.');
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $year = trim($_POST['year'] ?? '');
    $status = $_POST['status'] ?? 'не выполнена';

    if ($title === '') {
        $errors[] = 'Название обязательно.';
    } elseif (mb_strlen($title) > 255) {
        $errors[] = 'Название не должно превышать 255 символов.';
    }
    if ($author !== '' && mb_strlen($author) > 255) {
        $errors[] = 'Автор не должен превышать 255 символов.';
    }
    if ($year !== '' && !ctype_digit($year)) {
        $errors[] = 'Год должен быть целым числом.';
    }
    if ($status !== 'не выполнена' && $status !== 'выполнена') {
        $errors[] = 'Некорректный статус.';
    }

    if (empty($errors)) {
        if ($year === '') {
            $stmt = $mysqli->prepare('UPDATE tasks SET title = ?, description = ?, author = ?, status = ? WHERE id = ?');
            $stmt->bind_param('ssssi', $title, $description, $author, $status, $id);
        } else {
            $year_val = (int)$year;
            $stmt = $mysqli->prepare('UPDATE tasks SET title = ?, description = ?, author = ?, year = ?, status = ? WHERE id = ?');
            $stmt->bind_param('sssisi', $title, $description, $author, $year_val, $status, $id);
        }
        if ($stmt->execute()) {
            $stmt->close();
            header('Location: index.php');
            exit;
        } else {
            $errors[] = 'Ошибка при обновлении: ' . $mysqli->error;
        }
        if ($stmt) $stmt->close();
    }
} else {
    $title = $task['title'];
    $description = $task['description'];
    $author = $task['author'];
    $year = $task['year'];
    $status = $task['status'];
}
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <title>Редактировать задачу</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <h1>Редактировать задачу / книгу</h1>
  <?php if (!empty($errors)): ?>
    <div class="alert alert-danger"><ul><?php foreach ($errors as $e): ?><li><?php echo htmlspecialchars($e, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8'); ?></li><?php endforeach; ?></ul></div>
  <?php endif; ?>

  <form method="post" novalidate>
    <div class="mb-3">
      <label class="form-label">Название</label>
      <input name="title" class="form-control" maxlength="255" required value="<?php echo htmlspecialchars($title, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8'); ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Автор</label>
      <input name="author" class="form-control" maxlength="255" value="<?php echo htmlspecialchars($author, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8'); ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Год</label>
      <input name="year" class="form-control" maxlength="10" value="<?php echo htmlspecialchars($year, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8'); ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Описание</label>
      <textarea name="description" class="form-control"><?php echo htmlspecialchars($description, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8'); ?></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label">Статус</label>
      <select name="status" class="form-select">
        <option value="не выполнена" <?php echo $status === 'не выполнена' ? 'selected' : ''; ?>>не выполнена</option>
        <option value="выполнена" <?php echo $status === 'выполнена' ? 'selected' : ''; ?>>выполнена</option>
      </select>
    </div>

    <button class="btn btn-primary" type="submit">Сохранить</button>
    <a href="index.php" class="btn btn-secondary">Отмена</a>
  </form>
</div>
</body>
</html>
