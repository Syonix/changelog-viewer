<?php

namespace Syonix\ChangelogViewer\Processor;

use Doctrine\Common\Collections\ArrayCollection;
use Syonix\ChangelogViewer\Translator\LabelTranslator;

interface Processor {
    /**
     * @return ArrayCollection
     */
    public function getVersions();

    /**
     * @return LabelTranslator
     */
    public function getTranslator();
}