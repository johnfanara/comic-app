<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $_GET["series"]; ?> - <?php echo $_GET["publisher"]; ?> - Comic Book Collection</title>
    <link rel="stylesheet" href="styles.css">
    <script src="libarchive.js"></script>
    <script src="pdf.js"></script>
    <script src="pdf.worker.js"></script>
    <script src="comic-viewer.js"></script>


</head>
<body>
    <header>
        <h1><?php echo $_GET["series"]; ?></h1>
    </header>
    <nav>
        <a href="index.php">Home</a> >
        <a href="publisher.php?name=<?php echo $_GET["publisher"]; ?>"><?php echo $_GET["publisher"]; ?></a>
    </nav>
    <section>
        <h2>Volumes:</h2>
        <ul>
            <?php
            $publisher = $_GET["publisher"];
            $series = $_GET["series"];
            $seriesPath = 'D:\Comics\\' . $publisher . '\\' . $series;

            $allowedExtensions = ['cbr', 'cbz'];
            $hasVolumes = false;

            if ($handle = opendir($seriesPath)) {
                while (false !== ($volume = readdir($handle))) {
                    if ($volume != "." && $volume != ".." && is_dir($seriesPath . '\\' . $volume)) {
                        $hasVolumes = true;
                        echo "<li><a href='volume.php?publisher={$publisher}&series={$series}&volume={$volume}'>{$volume}</a></li>";
                    }
                }

                // If there are no volume folders, display comic files directly
                if (!$hasVolumes) {
                    rewinddir($handle); // Reset the directory handle
                    echo "<h2>Issues:</h2>";
                    while (false !== ($file = readdir($handle))) {
                        $fileInfo = pathinfo($file);
                        if (isset($fileInfo['extension']) && in_array(strtolower($fileInfo['extension']), $allowedExtensions)) {
                            $issue = $fileInfo['filename'];
                            // You can modify the link to open the comic book file, provide a download link, or show it in a viewer.
                            echo "<li><a href='#' onclick='openComic(\"{$seriesPath}\\{$file}\")'>{$issue}</a></li>";
                            
                        }
                    }
                }

                closedir($handle);
            }
            ?>
        </ul>
        <div id="comic-container"></div>
    </section>
</body>
</html>