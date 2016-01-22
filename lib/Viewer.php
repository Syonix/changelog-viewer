<?php

namespace Syonix\ChangelogViewer;

use cebe\markdown\Markdown;
use Jenssegers\Date\Date;

class Viewer {
    private $filePath;
    private $title = 'Changelog';
    private $locale = 'en';
    private $isModal = false;
    private $printFrame = true;
    private $printStyles = true;
    private $printScripts = true;
    private $printDownloadLinks = false;
    private $regex = array(
        'version' => '/^## \[(v.+)\]\((.+)\) - ([\d-]+)/',
        'changes_url' => '/^\[See full Changelog\]\((.+)\)/',
        'label' => '/^### (.+)/',
        'change' => '/^- (.+)/',
    );
    private $labels = [];

    public function __construct($filepath) {
        if(!is_readable($filepath)) throw new \InvalidArgumentException('File not found.');
        $this->readLocales();
        $this->filePath = $filepath;
        return $this;
    }

    public function title($val) {
        $this->title = $val;
        return $this;
    }

    public function locale($val) {
        if(!array_key_exists($val, $this->labels)) throw new \InvalidArgumentException('Invalid Locale "'.$val.'" given');
        $this->locale = $val;
        return $this;
    }

    public function modal($val = true) {
        $this->isModal = boolval($val);
        $this->printScripts = boolval($val);
        return $this;
    }

    public function frame($val) {
        $this->printFrame = boolval($val);
        return $this;
    }

    public function styles($val) {
        $this->printStyles = boolval($val);
        return $this;
    }

    public function scripts($val) {
        $this->printScripts = boolval($val);
        return $this;
    }

    public function downloadLinks($val) {
        $this->printDownloadLinks = boolval($val);
        return $this;
    }

    public function addLocale($locale, $labels) {
        if(!in_array('new', $labels) || !in_array('improved', $labels) || !in_array('fixed', $labels))
            throw new \InvalidArgumentException('Labels array needs values "new", "improved" and "fixed" with '.
                'corresponding localized keys. See built in locales for examples.');
        $this->labels[$locale] = $labels;
    }

    public function regex($val) {
        if(!is_array($val)) {
            throw new \InvalidArgumentException("Regex parameter must be an array");
        }
        if(
            !array_key_exists('version', $val)
            || !array_key_exists('changes_url', $val)
            || !array_key_exists('label', $val)
            || !array_key_exists('change', $val)
        ) {
            throw new \InvalidArgumentException('Regex array must contain keys "version", "changes_url", "label" and "change"');
        }
        $this->regex = $val;
        return $this;
    }

    private function readLocales() {
        $dir = realpath(__DIR__ . '/../locale');
        $locales = scandir($dir);
        foreach($locales as $locale) {
            if(!in_array($locale, array('.', '..'))) {
                $this->processLocaleFile($dir, $locale);
            }
        }
    }

    private function processLocaleFile($dir, $file) {
        $name = explode('.', $file)[0];
        $locale = array_flip(json_decode(file_get_contents($dir.'/'.$file), true));
        $this->addLocale($name, $locale);
    }

    public function build() {
        Date::setLocale($this->locale);

        if($this->printStyles) {
            $this->printStyles();
        }
        $hidden = $this->isModal ? ' style="display:none;"' : '';
        if($this->printFrame) {
            echo '<div id="changemodal-wrapper"'.$hidden.'><div id="changemodal"'.$hidden.'><div id="changemodal-frame"><h1>'.$this->title.'</h1>';
            if($this->isModal) echo '<i id="close-button" class="fa fa-times"></i>';
        } else {
            echo '<div id="changemodal">';
        }
        echo'<div class="changelog">';

        $parser = new Markdown();
        $labelOpen = false;
        $versionWrapperOpen = false;
        $currentLabel = null;
        $currentLabelText = "";
        foreach (new \SplFileObject($this->filePath) as $line) {
            if (preg_match($this->regex['version'], $line, $matches)) {
                $version = $matches[1];
                $versionUrl = $matches[2];
                $date = new Date($matches[3]);
                if($versionWrapperOpen) echo '</div>';
                echo '<h2 id="'.$version.'">'.$version.'</h2><h3 title="'.'">'.$date->ago().'</h3><div class="version-wrapper">';
                $versionWrapperOpen = true;
            } else if (preg_match($this->regex['changes_url'], $line, $matches)) {
                $fullChangelogUrl = $matches[0];
            } else if (preg_match($this->regex['label'], $line, $matches)) {
                $currentLabel = $this->translateLocalizedLabel($matches[1], $this->locale);
                $currentLabelText = ucfirst($matches[1]);
                if($labelOpen) echo '</ul>';
                echo '<ul class="'.$currentLabel.'">';
                $labelOpen = true;
            } else if (preg_match($this->regex['change'], $line, $matches)) {
                $change = preg_replace('/#\d+/', '', $matches[1]);
                echo '<li data-label="'.$currentLabelText.'">'.$parser->parseParagraph($change).'</li>';
            }
        }
        if($labelOpen) echo '</ul>';
        if($versionWrapperOpen) echo '</div>';
        echo '</div></div>';
        if($this->printFrame) echo '</div>';
        if($this->printScripts) {
            $this->printScripts();
        }
    }

    private function translateLocalizedLabel($label, $locale) {
        $label = strtolower($label);

        if(array_key_exists($locale, $this->labels)) {
            $localizedLabels = $this->labels[$locale];
            if(array_key_exists($label, $localizedLabels)) {
                return $localizedLabels[$label];
            }
        }
        return $label;
    }

    private function printStyles()
    {
        echo '<link rel="stylesheet" href="https://cdn.syonix.ch/fonts/font-awesome/4.5.0/css/font-awesome.min.css" type="text/css" media="all" />';
        echo '<link rel="stylesheet" href="https://cdn.syonix.ch/css/animate.css/3.2.0/animate.css" type="text/css" media="all" />';
        echo '<style>'.file_get_contents(__DIR__.'/../res/screen.css').'</style>';
    }

    private function printScripts()
    {
        echo '<script src="https://cdn.polyfill.io/v2/polyfill.min.js"></script>';
        echo '<script>'.file_get_contents(__DIR__.'/../res/bliss.js').'</script>';
        echo '<script>'.file_get_contents(__DIR__.'/../res/scripts.js').'</script>';
    }
}
