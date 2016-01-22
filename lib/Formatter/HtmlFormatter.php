<?php

namespace Syonix\ChangelogViewer\Formatter;

use Jenssegers\Date\Date;
use Syonix\ChangelogViewer\Processor\Processor;

class HtmlFormatter {
    private $processor;
    private $title = 'Changelog';
    private $locale;
    private $isModal = false;
    private $printFrame = true;
    private $printStyles = true;
    private $printScripts = true;
    private $printDownloadLinks = false;

    public function __construct(Processor $processor) {
        $this->processor = $processor;
        $this->locale = $processor->getTranslator()->getLocale();
        return $this;
    }

    public function title($val) {
        $this->title = $val;
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

        foreach ($this->processor->getVersions() as $version) {
            $date = new Date($version->getReleaseDate()->getTimestamp());
            echo '<h2 id="'.$version->getVersion().'">'.$version->getVersion().'</h2>';
            echo '<h3 title="'.'">'.$date->ago().'</h3>';
            echo '<div class="version-wrapper">';
            foreach($version->getChanges() as $label => $changes) {
                echo '<ul class="'.$label.'">';
                foreach($changes as $change) {
                    echo '<li data-label="'.ucfirst($this->processor->getTranslator()->translateTo($label)).'">'.$change.'</li>';
                }
                echo '</ul>';
            }
            echo '</div>';
        }
        echo '</div></div>';
        if($this->printFrame) echo '</div>';
        if($this->printScripts) {
            $this->printScripts();
        }
    }

    private function printStyles()
    {
        echo '<link rel="stylesheet" href="https://cdn.syonix.ch/fonts/font-awesome/4.5.0/css/font-awesome.min.css" type="text/css" media="all" />';
        echo '<link rel="stylesheet" href="https://cdn.syonix.ch/css/animate.css/3.2.0/animate.css" type="text/css" media="all" />';
        echo '<style>'.file_get_contents(__DIR__.'/../../res/screen.css').'</style>';
    }

    private function printScripts()
    {
        echo '<script src="https://cdn.polyfill.io/v2/polyfill.min.js"></script>';
        echo '<script>'.file_get_contents(__DIR__.'/../../res/bliss.js').'</script>';
        echo '<script>'.file_get_contents(__DIR__.'/../../res/scripts.js').'</script>';
    }
}
