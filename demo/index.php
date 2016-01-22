<?php
    require __DIR__ . '/../vendor/autoload.php';

    use Syonix\ChangelogViewer\Translator\LabelTranslator;
    use Syonix\ChangelogViewer\Formatter\HtmlFormatter;
    use Syonix\ChangelogViewer\Processor\MarkdownProcessor;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Changelog</title>
        <link href="../res/bootstrap/bootstrap.min.css" rel="stylesheet">
    </head>
    <body style="background: #ececec;">
        <a href="#" onclick="openChangelogModal();" style="width: 200px; text-align: center; display: block; margin: 50px auto 0 auto; font-size: 2rem;">Show Changes</a>
        <?php
            (new HtmlFormatter(new MarkdownProcessor(__DIR__ . '/changelogs/CHANGELOG.md',new LabelTranslator('de'))))
                //->modal(true)
                //->frame(false)
                ->build();
        ?>
    </body>
</html>