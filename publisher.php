<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $_GET["name"]; ?> - Comic Book Collection</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1><?php echo $_GET["name"]; ?></h1>
    </header>
    <nav>
        <a href="index.php">Home</a>
    </nav>
<section>
    <h2>Series/Characters:</h2>
    <ul>
        <?php
        $publisher = $_GET["name"];
        $publisherPath = 'D:\Comics\\' . $publisher;
        if ($handle = opendir($publisherPath)) {
            while (false !== ($serie = readdir($handle))) {
                if ($serie != "." && $serie != ".." && is_dir($publisherPath . '\\' . $serie)) {
                    echo "<li><a href='series.php?publisher={$publisher}&series={$serie}'>{$serie}</a></li>";
                }
            }
            closedir($handle);
        }
        ?>
    </ul>
</section>
</body>
</html>