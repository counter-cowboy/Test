<?php
header('Content-Type: application/json');

// Подключение к SQLite
$db = new PDO('sqlite:database.sqlite');

// Получаем данные из запроса
$url = $_POST['url'];
$text = $_POST['text'];

// Получаем HTML-код страницы
$html = file_get_contents($url);

// Ищем все изображения на странице
preg_match_all('/<img[^>]+src="([^">]+)"/', $html, $matches);
$imageUrls = $matches[1];

// Обрабатываем каждое изображение
foreach ($imageUrls as $imageUrl) {
    // Загружаем изображение
    $imageData = file_get_contents($imageUrl);
    if ($imageData === false) continue;

    // Создаём объект изображения
    $image = imagecreatefromstring($imageData);
    if ($image === false) continue;

    // Проверяем размеры изображения
    $width = imagesx($image);
    $height = imagesy($image);
    if ($width < 200 || $height < 200) continue;

    // Уменьшаем и обрезаем изображение
    $newHeight = 200;
    $newWidth = 200;
    $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    // Накладываем текст
    $textColor = imagecolorallocate($resizedImage, 255, 255, 255);
    imagettftext($resizedImage, 20, 0, 10, 30, $textColor, 'arial.ttf', $text);

    // Сохраняем изображение
    $filename = uniqid() . '.jpg';
    imagejpeg($resizedImage, 'images/' . $filename);

    // Освобождаем память
    imagedestroy($image);
    imagedestroy($resizedImage);

    // Сохраняем информацию в базу данных
    $stmt = $db->prepare('INSERT INTO images (filename, text) VALUES (?, ?)');
    $stmt->execute([$filename, $text]);
}

echo json_encode(['status' => 'success']);