<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Файловый менеджер</title>
	<style>
        body { font-family: Arial, sans-serif; }
        ul { list-style-type: none; }
        li { margin-bottom: 10px; }
        img { max-width: 200px; max-height: 200px; }
	</style>
</head>
<body>
<?php
// Определяем корневую директорию (рабочую папку)
$base_dir = realpath(__DIR__); // __DIR__ указывает на папку, где находится скрипт
$current_dir = isset($_GET['dir']) ? realpath($base_dir . '/' . $_GET['dir']) : $base_dir;

// Проверяем, что пользователь не выходит за пределы корневой директории
if (strpos($current_dir, $base_dir) !== 0) {
	$current_dir = $base_dir;
}

// Функция для проверки расширения файла (должны быть только изображения)
function isImage($file) {
	$allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
	$extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
	return in_array($extension, $allowed_extensions);
}

// Получаем список файлов и папок в текущей директории
$items = scandir($current_dir);

// Функция для генерации ссылок на папки
function createLink($path, $name) {
	return '<a href="?dir=' . urlencode($path) . '">' . htmlspecialchars($name) . '</a>';
}
?>
<h1>Файловый менеджер</h1>

<h2>Текущая директория: <?php echo htmlspecialchars($current_dir); ?></h2>

<ul>
	<!-- Ссылка на родительскую папку, если не находимся в корне -->
	<?php if ($current_dir != $base_dir): ?>
		<li><?php echo createLink(dirname($_GET['dir']), '.. (Назад)'); ?></li>
	<?php endif; ?>

	<!-- Отображение папок и изображений -->
	<?php foreach ($items as $item): ?>
		<?php
		$item_path = $current_dir . '/' . $item;

		if ($item === '.' || $item === '..') continue; // Пропускаем специальные папки
		if (is_dir($item_path)): ?>
			<li>
				<?php echo createLink((isset($_GET['dir']) ? $_GET['dir'] . '/' : '') . $item, $item); ?> (Папка)
			</li>
		<?php elseif (isImage($item)): ?>
			<li>
				<?php echo htmlspecialchars($item); ?>
				<br>
				<img src="<?php echo (isset($_GET['dir']) ? $_GET['dir'] . '/' : '') . $item; ?>"
				     alt="<?php echo htmlspecialchars($item); ?>"
				>
			</li>
		<?php endif; ?>
	<?php endforeach; ?>
</ul>
</body>
</html>
