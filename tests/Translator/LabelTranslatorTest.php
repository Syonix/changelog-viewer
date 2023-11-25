<?php

namespace Syonix\ChangelogViewer\Translator;

use PHPUnit\Framework\TestCase;

/** @covers \Syonix\ChangelogViewer\Translator\LabelTranslator */
class LabelTranslatorTest extends TestCase
{
    public function setUp(): void
    {
        $this->translator = new LabelTranslator('de');
    }

    public function testTranslateFrom(): void
    {
        $label = $this->translator->translateFrom('neu');
        $this->assertEquals('new', $label);
    }

    public function testTranslateTo(): void
    {
        $label = $this->translator->translateTo('new');
        $this->assertEquals('neu', $label);
    }
}
