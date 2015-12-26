<?php
    require __DIR__ . '/vendor/autoload.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Changelog</title>
        <link href="res/bootstrap/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                background: #2b3d50;
                font-size: 1em;
            }
        </style>
    </head>
    <body>
        <?php \Syonix\Util\ChangelogViewer\ChangelogModal::generate(__DIR__.'/changelogs/CHANGELOG.md', 'de', true, true); ?>
    </body>
</html>