<?php

//  SQLite
$db = new PDO('sqlite:database.sqlite');
// create table
$db->exec('CREATE TABLE IF NOT EXISTS images (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    filename TEXT NOT NULL,
    text TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );
');

//  список загруженных изображений
$stmt = $db->query('SELECT * FROM images ORDER BY created_at DESC');
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Загрузка изображений</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<h1>Загрузка изображений</h1>
<form id="uploadForm">
    <input type="url" name="url" placeholder="Ссылка на страницу" required>
    <input type="text" name="text" placeholder="Текст для наложения" required>
    <button type="submit">Загрузить</button>
</form>

<div id="imageGallery">
    <?php foreach ($images as $image): ?>
        <div class="image-item">
            <img src="images/<?= htmlspecialchars($image['filename']) ?>" alt="Image">
            <p><?= htmlspecialchars($image['text']) ?></p>
        </div>
    <?php endforeach; ?>
</div>

<script src="app.js"></script>
</body>
</html>