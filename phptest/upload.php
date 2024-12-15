<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Папка для загрузки видео
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["videoFile"]["name"]);
    
    // Проверяем, является ли файл видео
    $videoFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowedTypes = ["mp4", "avi", "mov", "wmv"];
    if (!in_array($videoFileType, $allowedTypes)) {
        die("Ошибка: Неподдерживаемый формат видео.");
    }
    
    // Перемещаем загруженный файл в папку
    if (move_uploaded_file($_FILES["videoFile"]["tmp_name"], $target_file)) {
        // Обновляем список видео
        $videoList = json_decode(file_get_contents('videos.json'), true);
        $videoList[] = $_FILES["videoFile"]["name"];
        file_put_contents('videos.json', json_encode($videoList));

        echo "Файл ". htmlspecialchars(basename($_FILES["videoFile"]["name"])) . " был загружен.";
    } else {
        echo "Ошибка при загрузке файла.";
    }
}
?>
