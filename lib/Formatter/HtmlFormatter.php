<?php

namespace Syonix\ChangelogViewer\Formatter;

use Jenssegers\Date\Date;
use Syonix\ChangelogViewer\Processor\ProcessorInterface;

class HtmlFormatter {
    private $processor;
    private $title = 'Changelog';
    private $locale;
    private $isModal = false;
    private $printFrame = true;
    private $printStyles = true;
    private $printScripts = false;
    private $printDownloadLinks = false;

    public function __construct(ProcessorInterface $processor) {
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

    public function downloadLinks($val = true) {
        $this->printDownloadLinks = boolval($val);
        return $this;
    }

    public function build() {
        $translator = $this->processor->getTranslator();
        $html = "";
        Date::setLocale($this->locale);

        if($this->printStyles) {
            $html .= $this->getStylesHtml();
        }
        $hidden = $this->isModal ? ' style="display:none;"' : '';
        if($this->printFrame) {
            $html .= '<div id="changemodal-wrapper"'.$hidden.'><div id="changemodal"'.$hidden.'><div id="changemodal-frame"><h1>'.$this->title.'</h1>';
            if($this->isModal) $html .= '<i id="close-button" class="fa fa-times"></i>';
        } else {
            $html .= '<div id="changemodal">';
        }
        $html .= '<div class="changelog">';

        try {
            foreach ($this->processor->getVersions() as $version) {
                $html .= '<h2 id="'.$version->getVersion().'"';
                if($version->isYanked()) $html .= ' class="yanked" title="'.$translator->translateTo('This release has been yanked.').'"';
                $html .= '>'.$version->getVersion().'</h2>';
                if($version->isReleased()) {
                    $date = new Date($version->getReleaseDate()->getTimestamp());
                    $html .= '<h3 title="'.'">'.$date->ago();
                    if($this->printDownloadLinks && !$version->isYanked()) $html .= ' <a href="'.$version->getUrl().'" target="_blank"><i class="fa fa-download"></i></a>';
                    $html .= '</h3>';
                }
                $html .= '<div class="version-wrapper">';
                foreach($version->getChanges() as $label => $changes) {
                    $html .= '<ul class="'.$label.'">';
                    foreach($changes as $change) {
                        $html .= '<li data-label="'.ucfirst($translator->translateTo($label)).'">'.$change.'</li>';
                    }
                    $html .= '</ul>';
                }
                $html .= '</div>';
            }

        } catch (\Exception $e) {
            $html .= '<div class="alert alert-danger" style="text-align: center" role="alert">'
                .'<i class="fa fa-lg fa-exclamation-triangle"></i><br>'
                .'<b>Could not get Changelog!</b><br>'
                .'Error: <em>'.$e->getMessage().'</em>'
                .'</div>';
        }
        $html .= '</div></div>';
        if($this->printFrame) $html .= '</div>';
        if($this->printScripts) {
            $html .= $this->getScriptsHtml();
        }
        return $html;
    }

    public function output() {
        echo $this->build();
    }

    private function getStylesHtml()
    {
        $html = '<link rel="stylesheet" href="https://cdn.syonix.ch/fonts/font-awesome/4.5.0/css/font-awesome.min.css" type="text/css" media="all" />';
        $html .= '<link rel="stylesheet" href="https://cdn.syonix.ch/css/animate.css/3.2.0/animate.css" type="text/css" media="all" />';
        $html .= '<style>'.file_get_contents(__DIR__.'/../../res/screen.css').'</style>';
        return $html;
    }

    private function getScriptsHtml()
    {
        $html = '<script src="https://cdn.polyfill.io/v2/polyfill.min.js"></script>';
        $html .= '<script>'.file_get_contents(__DIR__ . '/../../res/bliss.min.js').'</script>';
        $html .= '<script>'.file_get_contents(__DIR__.'/../../res/scripts.js').'</script>';
        return $html;
    }
}
