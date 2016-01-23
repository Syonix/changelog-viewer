<?php

namespace Syonix\ChangelogViewer\Factory;

use Syonix\ChangelogViewer\Formatter\HtmlFormatter;
use Syonix\ChangelogViewer\Processor\MarkdownProcessor;
use Syonix\ChangelogViewer\Translator\LabelTranslator;

class ViewerFactory {
    public static function createMarkdownHtmlViewer($path, $locale = null)
    {
        if(null !== $locale) {
            $locale = new LabelTranslator($locale);
            $processor = new MarkdownProcessor($path,$locale);
        } else {
            $processor = new MarkdownProcessor($path);
        }
        return new HtmlFormatter($processor);
    }
}
