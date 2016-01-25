<?php

namespace Syonix\ChangelogViewer\Translator;

class LabelTranslator {
    private $locale;
    private $labels = [];

    public function __construct($locale = 'en')
    {
        $this->locale = $locale;
        if('en' !== $locale) {
            $path = realpath(__DIR__ . '/../../locale/'.$locale.'.json');
            if(!is_readable($path)) {
                throw new \InvalidArgumentException('Locale file for "'.$locale.'" not found');
            }
            $labels = array_flip(json_decode(file_get_contents($path), true));
            if(!in_array('new', $labels) || !in_array('improved', $labels) || !in_array('fixed', $labels))
                throw new \InvalidArgumentException('Labels array needs values "new", "improved" and "fixed" with '.
                    'corresponding localized keys. See built in locales for examples.');
            $this->labels = $labels;
        }
    }

    public function translateFrom($label) {
        if('en' !== $this->locale && array_key_exists($label, $this->labels)) {
            return $this->labels[$label];
        }
        return $label;
    }

    public function translateTo($label) {
        $labelsReverse = array_flip($this->labels);

        if('en' !== $this->locale && array_key_exists($label, $labelsReverse)) {
            return $labelsReverse[$label];
        }
        return $label;
    }

    public function getLocale()
    {
        return $this->locale;
    }
}
