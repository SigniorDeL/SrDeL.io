<?php
if (isset($_GET['name'])) {
    $videoName = htmlspecialchars($_GET['name']);
    $videoPath = "uploads/" . $videoName;
    if (file_exists($videoPath)) {
        echo "<h1>$videoName</h1>";
        echo "<video controls src='$videoPath'></video>";
        echo "<h2>Комментарии</h2>";
        // Здесь можно добавить код для отображения комментариев
        echo "<form action='comment.php' method='POST'>
                <input type='hidden' name='videoName' value='$videoName'>
                <textarea name='comment' required></textarea>
                <input type='submit' value='Оставить комментарий'>
              </form>";
    } else {
        echo "Видео не найдено.";
    }
} else {
    echo "Нет данных для отображения.";
}
?>
