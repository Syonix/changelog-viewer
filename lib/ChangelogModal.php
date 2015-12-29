<?php

namespace Syonix\Util\ChangelogViewer;

use cebe\markdown\Markdown;
use Jenssegers\Date\Date;

class ChangelogModal {
    public static function generate($filepath, $locale = 'en', $title = 'Changelog', $styles = true, $closeable = false) {
        Date::setLocale($locale);
        $hidden = '';

        if($styles) {
            self::loadStyles();
        }
        if($closeable) {
            $hidden = ' style="display:none;"';
        }
        echo '<div id="changemodal-wrapper"'.$hidden.'><div id="changemodal"'.$hidden.'><h1>'.$title.'</h1>';
        if($closeable) echo '<i id="close-button" class="fa fa-times"></i>';
        echo'<div class="changelog">';
        $regex = array(
            'version' => '/^## \[(v.+)\]\((.+)\) - ([\d-]+)/',
            'changes_url' => '/^\[See full Changelog\]\((.+)\)/',
            'label' => '/^### (.+)/',
            'change' => '/^- (.+)/',
        );
        $colors = array(
            'new' => 'blue',
            'improved' => 'orange',
            'fixed' => 'red'
        );
        $parser = new Markdown();
        $labelOpen = false;
        $versionWrapperOpen = false;
        $currentLabel = null;
        $currentLabelText = "";
        foreach (new \SplFileObject($filepath) as $line) {
            if (preg_match($regex['version'], $line, $matches)) {
                $version = $matches[1];
                $versionUrl = $matches[2];
                $date = new Date($matches[3]);
                if($versionWrapperOpen) echo '</div>';
                echo '<h2 id="'.$version.'">'.$version.'</h2><h3 title="'.'">'.$date->ago().'</h3><div class="version-wrapper">';
                $versionWrapperOpen = true;
            } else if (preg_match($regex['changes_url'], $line, $matches)) {
                $fullChangelogUrl = $matches[0];
            } else if (preg_match($regex['label'], $line, $matches)) {
                $currentLabel = self::translateLocalizedLabel($matches[1], $locale);
                $currentLabelText = ucfirst($matches[1]);
                if($labelOpen) echo '</ul>';
                echo '<ul class="'.$colors[$currentLabel].'">';
                $labelOpen = true;
            } else if (preg_match($regex['change'], $line, $matches)) {
                $change = preg_replace('/#\d+/', '', $matches[1]);
                echo '<li data-label="'.$currentLabelText.'">'.$parser->parseParagraph($change).'</li>';
            }
        }
        if($labelOpen) echo '</ul>';
        if($versionWrapperOpen) echo '</div>';
        echo '</div></div></div>';
        if($closeable) {
            self::loadScripts();
        }
    }

    public static function generateCloseable($filepath, $locale = 'en', $title = 'Changelog') {
        self::generate($filepath, $locale, $title, true, true, true);
    }

    public static function generateFixed($filepath, $locale = 'en', $title = 'Changelog') {
        self::generate($filepath, $locale, $title, true, true, false);
    }

    public static function translateLocalizedLabel($label, $locale) {
        $label = strtolower($label);
        $labels = array(
            'de' => array(
                'neu' => 'new',
                'verbessert' => 'improved',
                'behoben' => 'fixed',
            )
        );

        if(array_key_exists($locale, $labels)) {
            $localizedLabels = $labels[$locale];
            if(array_key_exists($label, $localizedLabels)) {
                return $localizedLabels[$label];
            }
        }
        return $label;
    }

    public static function loadStyles()
    {
        echo '<link rel="stylesheet" href="https://cdn.syonix.ch/fonts/font-awesome/4.5.0/css/font-awesome.min.css" type="text/css" media="all" />';
        echo '<link rel="stylesheet" href="https://cdn.syonix.ch/css/animate.css/3.2.0/animate.css" type="text/css" media="all" />';
        echo '<style>'.file_get_contents(__DIR__.'/../res/screen.css').'</style>';
    }

    public static function loadScripts()
    {
        echo '<script src="https://cdn.polyfill.io/v2/polyfill.min.js"></script>';
        echo '<script>'.file_get_contents(__DIR__.'/../res/bliss.js').'</script>';
        echo '<script>'.file_get_contents(__DIR__.'/../res/scripts.js').'</script>';
    }
}
