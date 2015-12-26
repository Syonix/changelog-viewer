<?php

namespace Syonix\Util\ChangelogViewer;

use cebe\markdown\Markdown;
use Jenssegers\Date\Date;

class ChangelogModal {
    public static function generate($filepath, $locale = 'en') {
        Date::setLocale($locale);
        echo '<div id="changemodal"><h1>Changelog</h1><div class="changelog">';
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
        echo '</div></div>';
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
}