<?php

namespace Syonix\ChangelogViewer\Factory;

use Syonix\ChangelogViewer\Formatter\HtmlFormatter;
use Syonix\ChangelogViewer\Processor\MarkdownProcessorInterface;
use Syonix\ChangelogViewer\Translator\LabelTranslator;

class ViewerFactory {
    public static function createMarkdownHtmlViewer($path, $locale = null)
    {
        if(null !== $locale) {
            $locale = new LabelTranslator($locale);
            $processor = new MarkdownProcessorInterface($path,$locale);
        } else {
            $processor = new MarkdownProcessorInterface($path);
        }
        return new HtmlFormatter($processor);
    }
}