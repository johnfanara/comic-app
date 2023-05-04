<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $_GET["volume"]; ?> - <?php echo $_GET["series"]; ?> - <?php echo $_GET["publisher"]; ?> - Comic Book Collection</title>
    <link rel="stylesheet" href="styles.css">
    <script src="scripts.js"></script>
    <script src="comic-viewer.js"></script>
</head>
<body>
    <header>
        <h1><?php echo $_GET["volume"]; ?></h1>
    </header>
    <nav>
        <a href="index.php">Home</a> >
        <a href="publisher.php?name=<?php echo $_GET["publisher"]; ?>"><?php echo $_GET["publisher"]; ?></a> >
        <a href="series.php?publisher=<?php echo $_GET["publisher"]; ?>&series=<?php echo $_GET["series"]; ?>"><?php echo $_GET["series"]; ?></a>
    </nav>
    <section>
        <h2>Issues:</h2>
        <ul>
            <?php
            function listFiles($dirPath) {
                $allowedExtensions = ['cbr', 'cbz'];
                if ($handle = opendir($dirPath)) {
                    while (false !== ($entry = readdir($handle))) {
                        if ($entry != "." && $entry != "..") {
                            $entryPath = $dirPath . '\\' . $entry;
                            if (is_dir($entryPath)) {
                                listFiles($entryPath);
                            } else {
                                $fileInfo = pathinfo($entry);
                                if (isset($fileInfo['extension']) && in_array(strtolower($fileInfo['extension']), $allowedExtensions)) {
                                    $issue = $fileInfo['filename'];
                                    // You can modify the link to open the comic book file, provide a download link, or show it in a viewer.
                                    echo "<li><a href='#' onclick='openComic(\"{$entryPath}\")'>{$issue}</a></li>";
                                }
                            }
                        }
                    }
                    closedir($handle);
                }
            }

            $publisher = $_GET["publisher"];
            $series = $_GET["series"];
            $volume = $_GET["volume"];
            $volumePath = 'D:\Comics\\' . $publisher . '\\' . $series . '\\' . $volume;

            listFiles($volumePath);
            ?>
        </ul>
    </section>
</body>
</html>