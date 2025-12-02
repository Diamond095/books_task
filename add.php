<?php
require_once 'config.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $year = trim($_POST['year'] ?? '');

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

    if (empty($errors)) {
        $stmt = $mysqli->prepare("INSERT INTO tasks (title, description, author, year) VALUES (?, ?, ?, ?)");
        $year_val = $year === '' ? null : (int)$year;
        if ($year === '') {
            $stmt->bind_param('sss', $title, $description, $author);
            $stmt->close();
            $stmt = $mysqli->prepare("INSERT INTO tasks (title, description, author) VALUES (?, ?, ?)");
            $stmt->bind_param('sss', $title, $description, $author);
        } else {
            $stmt->bind_param('sssi', $title, $description, $author, $year_val);
        }

        if ($stmt->execute()) {
            $stmt->close();
            header('Location: index.php');
            exit;
        } else {
            $errors[] = 'Ошибка при добавлении: ' . $mysqli->error;
        }
        if ($stmt) $stmt->close();
    }
}
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <title>Добавить задачу</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <h1>Добавить задачу / книгу</h1>
  <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
      <ul>
      <?php foreach ($errors as $e): ?>
        <li><?php echo htmlspecialchars($e, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8'); ?></li>
      <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="post" novalidate>
    <div class="mb-3">
      <label class="form-label">Название</label>
      <input name="title" class="form-control" maxlength="255" required value="<?php echo isset($title) ? htmlspecialchars($title, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8') : ''; ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Автор</label>
      <input name="author" class="form-control" maxlength="255" value="<?php echo isset($author) ? htmlspecialchars($author, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8') : ''; ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Год</label>
      <input name="year" class="form-control" maxlength="10" value="<?php echo isset($year) ? htmlspecialchars($year, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8') : ''; ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Описание</label>
      <textarea name="description" class="form-control"><?php echo isset($description) ? htmlspecialchars($description, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8') : ''; ?></textarea>
    </div>
    <button class="btn btn-primary" type="submit">Добавить</button>
    <a href="index.php" class="btn btn-secondary">Отмена</a>
  </form>
</div>
</body>
</html>
