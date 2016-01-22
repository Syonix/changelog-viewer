<?php

namespace Syonix\ChangelogViewer\Processor;

use cebe\markdown\Markdown;
use Doctrine\Common\Collections\ArrayCollection;
use Syonix\ChangelogViewer\Translator\LabelTranslator;
use Syonix\ChangelogViewer\Version;

class MarkdownProcessor implements Processor {
    private $filePath;
    private $translator;
    private $removeIssues;
    private $versions;
    private $regex = array(
        'version' => '/^## \[(v.+)\]\((.+)\) - ([\d-]+)/',
        'changes_url' => '/^\[See full Changelog\]\((.+)\)/',
        'label' => '/^### (.+)/',
        'change' => '/^- (.+)/',
    );

    public function __construct($filePath, LabelTranslator $translator = null, $removeIssues = true)
    {
        if($translator === null) $translator = new LabelTranslator();
        $this->translator = $translator;
        if(!is_readable($filePath)) throw new \InvalidArgumentException('File "'.$filePath.'" not found.');
        $this->filePath = $filePath;
        $this->translator = $translator;
        $this->removeIssues = $removeIssues;
        $this->versions = new ArrayCollection();
    }

    public function setRegex($regex) {
        if(!is_array($regex)) {
            throw new \InvalidArgumentException("Regex parameter must be an array");
        }
        if(
            !array_key_exists('version', $regex)
            || !array_key_exists('changes_url', $regex)
            || !array_key_exists('label', $regex)
            || !array_key_exists('change', $regex)
        ) {
            throw new \InvalidArgumentException('Regex array must contain keys "version", "changes_url", "label" and "change"');
        }
        $this->regex = $regex;
        return $this;
    }

    public function getVersions()
    {
        $parser = new Markdown();
        $currentVersion = null;
        $currentLabel = null;

        foreach (new \SplFileObject($this->filePath) as $line) {
            if (preg_match($this->regex['version'], $line, $matches)) {
                if(null !== $currentVersion) {
                    $this->versions->add($currentVersion);
                }
                $currentVersion = new Version();
                $currentVersion->setVersion($matches[1]);
                $currentVersion->setUrl($matches[2]);
                $currentVersion->setReleaseDate(new \DateTime($matches[3]));
            } else if (preg_match($this->regex['changes_url'], $line, $matches)) {
                $fullChangelogUrl = $matches[0]; // Currently not used
            } else if (preg_match($this->regex['label'], $line, $matches)) {
                $currentLabel = $this->translator->translateFrom($matches[1]);
            } else if (preg_match($this->regex['change'], $line, $matches)) {
                $change = $this->removeIssues ? preg_replace('/#\d+/', '', $matches[1]) : $matches[1];
                $currentVersion->addChange($currentLabel, $parser->parseParagraph($change));
            }
        }

        return $this->versions;
    }

    public function getTranslator() {
        return $this->translator;
    }
}