<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comic Book Collection</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf_viewer.js"></script>
    <script src="js/libarchive.js"></script>
    <script src="js/jszip.min.js"></script>
</head>
<body>
    <header>
        <h1>My Comic Book Collection</h1>
    </header>
    <section>
        <h2>Publishers:</h2>
        <ul>
            <?php
            $comicsPath = 'D:\Comics';
            if ($handle = opendir($comicsPath)) {
                while (false !== ($publisher = readdir($handle))) {
                    if ($publisher != "." && $publisher != ".." && is_dir($comicsPath . '\\' . $publisher)) {
                        echo "<li><a href='publisher.php?name={$publisher}'>{$publisher}</a></li>";
                    }
                }
                closedir($handle);
            }
            ?>
        </ul>
    </section>
</body>
</html>
